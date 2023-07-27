<?php

namespace Modules\ShopDiscounts\Entities;

use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;
use Modules\Shop\Models\Admin\Brand;
use Modules\Shop\Models\Admin\ProductCategory\Category;
use Modules\Shop\Models\Admin\Products\Product;

class Discount extends Model
{
    use SoftDeletes;

    public static $FIXED_AMOUNT_TYPE_ID        = 1;
    public static $FIXED_PERCENT_TYPE_ID       = 2;
    public static $FIXED_FREE_DELIVERY_TYPE_ID = 3;
    public static $QUANTITY_TYPE_ID            = 4;
    public static $BONUS_ON_ITEM_TYPE_ID       = 5;

    public static $EVERY_PRODUCT_APPLICATION     = 1;
    public static $BRAND_APPLICATION             = 2;
    public static $CATEGORY_APPLICATION          = 3;
    public static $PRODUCT_APPLICATION           = 4;
    public static $ABOVE_ORDER_VALUE_APPLICATION = 5;

    public static $AMOUNT_VALUE_TYPE  = 1;
    public static $PERCENT_VALUE_TYPE = 2;

    protected $fillable = [
        'type_id',
        'name',
        'client_group_id',
        'valid_from',
        'valid_until',
        'max_uses',
        'current_uses',
        'active',
        'promo_code',
        'value',
        'applies_to',
        'product_id',
        'brand_id',
        'order_value',
        'data'
    ];

    protected $dates = ['deleted_at'];
    public static function getTypes()
    {
        return [
            self::$FIXED_AMOUNT_TYPE_ID,
            self::$FIXED_PERCENT_TYPE_ID,
            self::$FIXED_FREE_DELIVERY_TYPE_ID,
            self::$QUANTITY_TYPE_ID,
            self::$BONUS_ON_ITEM_TYPE_ID,
        ];
    }
    public static function getApplications()
    {
        return [
            self::$EVERY_PRODUCT_APPLICATION,
            self::$PRODUCT_APPLICATION,
            self::$CATEGORY_APPLICATION,
            self::$BRAND_APPLICATION,
            self::$ABOVE_ORDER_VALUE_APPLICATION,
        ];
    }
    public static function getValueTypes()
    {
        return [
            self::$AMOUNT_VALUE_TYPE,
            self::$PERCENT_VALUE_TYPE,
        ];
    }
    public static function hasSupplierPriceLimitError($typeId, $appliesTo, $identificators, $productDiscountsAmount)
    {
        $products = [];
        switch ($appliesTo) {
            case self::$PRODUCT_APPLICATION:
                $products = Product::where('id', $identificators['product_id'])->get();
                break;
            case self::$BRAND_APPLICATION:
                $products = Product::where('brand_id', $identificators['brand_id'])->get();
                break;
            case self::$CATEGORY_APPLICATION:
                $products = Product::whereIn('category_id', $identificators['categories_ids'])->get();
                break;
            case self::$EVERY_PRODUCT_APPLICATION:
            case self::$ABOVE_ORDER_VALUE_APPLICATION:
                $products = Product::all();
                break;
        }

        foreach ($products as $product) {
            if ($typeId == self::$FIXED_PERCENT_TYPE_ID) {
                $productDiscountsAmount = $product->price * ($productDiscountsAmount / 100);
            }

            $productDiscounts = Discount::where('active', true)
                ->whereIn('type_id', [self::$FIXED_AMOUNT_TYPE_ID, self::$FIXED_PERCENT_TYPE_ID])
                ->where(function ($q) use ($product) {
                    return $q->where(function ($qq) use ($product) {
                        return $qq->where('applies_to', self::$PRODUCT_APPLICATION)->where('discounts.product_id', $product->id);
                    })->orWhere(function ($qq) use ($product) {
                        return $qq->where('applies_to', self::$BRAND_APPLICATION)->where('discounts.brand_id', $product->brand_id);
                    })->orWhere(function ($qq) {
                        return $qq->whereIn('applies_to', [self::$ABOVE_ORDER_VALUE_APPLICATION, self::$EVERY_PRODUCT_APPLICATION]);
                    })->orWhere(function ($qq) use ($product) {
                        return $qq->where('applies_to', self::$CATEGORY_APPLICATION)->whereHas('categories', function ($qqq) use ($product) {
                            return $qqq->where('category_id', $product->category_id);
                        });
                    });
                })->get();

            foreach ($productDiscounts as $productDiscount) {
                if ($productDiscount->type_id == self::$FIXED_AMOUNT_TYPE_ID) {
                    $productDiscountsAmount += $productDiscount->value;
                } else if ($productDiscount->type_id == self::$FIXED_PERCENT_TYPE_ID) {
                    $productDiscountsAmount += $product->price * ($productDiscount->value / 100);
                }
            }

            if (($product->price - $productDiscountsAmount) <= $product->supplier_delivery_price) {
                return true;
            }
        }

        return false;
    }
    public static function generateFixedDiscountData($typeId, $requestData)
    {
        $data                    = [];
        $data['type_id']         = $typeId;
        $data['name']            = $requestData['name'];
        $data['client_group_id'] = $requestData['client_group_id'];
        $data['active']          = $requestData['active'];

        $data['applies_to'] = $requestData['applies_to'];
        if ($typeId != self::$FIXED_FREE_DELIVERY_TYPE_ID) {
            $data['value'] = $requestData['value'];
        } else {
            $data['value'] = null;
        }

        $data['valid_from'] = null;
        if (isset($requestData['valid_from'])) {
            $data['valid_from'] = $requestData['valid_from'];
        }

        $data['valid_until'] = null;
        if (isset($requestData['valid_until'])) {
            $data['valid_until'] = $requestData['valid_until'];
        }

        $isActive = self::calculateIsActive($data['valid_from'], $data['valid_until']);
        if (!$isActive && $data['active']) {
            $data['active'] = false;
        }

        if (isset($requestData['max_uses'])) {
            $data['max_uses'] = $requestData['max_uses'];
        }

        if (isset($requestData['promo_code'])) {
            $data['promo_code'] = $requestData['promo_code'];
        }

        $data['product_id']     = null;
        $data['brand_id']       = null;
        $data['categories_ids'] = [];
        $data['order_value']    = null;

        switch ($requestData['applies_to']) {
            case self::$PRODUCT_APPLICATION:
                $data['product_id'] = $requestData['product_id'];
                break;
            case self::$BRAND_APPLICATION:
                $data['brand_id'] = $requestData['brand_id'];
                break;
            case self::$CATEGORY_APPLICATION:
                $data['categories_ids'] = $requestData['categories_ids'];
                break;
            case self::$ABOVE_ORDER_VALUE_APPLICATION:
                $data['order_value'] = $requestData['order_value'];
                break;
        }

        return $data;
    }
    private static function calculateIsActive($validFrom, $validUntil)
    {
        $now = Carbon::now();
        $fc  = true;
        if (!is_null($validFrom)) {
            $fc = Carbon::parse($validFrom)->startOfDay()->lte($now);
        }

        $sc = true;
        if (!is_null($validUntil)) {
            $sc = Carbon::parse($validUntil)->endOfDay()->gte($now);
        }

        return $fc && $sc;
    }

    //time zone should be properly set because it compares to current datetime
    public static function generateQuantityDiscountData($requestData)
    {
        $data                    = [];
        $data['type_id']         = self::$QUANTITY_TYPE_ID;
        $data['name']            = $requestData['name'];
        $data['client_group_id'] = $requestData['client_group_id'];
        $data['active']          = $requestData['active'];
        $data['valid_from']      = null;
        if (isset($requestData['valid_from'])) {
            $data['valid_from'] = $requestData['valid_from'];
        }
        $data['valid_until'] = null;
        if (isset($requestData['valid_until'])) {
            $data['valid_until'] = $requestData['valid_until'];
        }

        $isActive = self::calculateIsActive($data['valid_from'], $data['valid_until']);
        if (!$isActive && $data['active']) {
            $data['active'] = false;
        }

        if (isset($requestData['max_uses'])) {
            $data['max_uses'] = $requestData['max_uses'];
        }

        $data['data']           = json_encode($requestData['prices']);
        $data['product_id']     = $requestData['product_id'];
        $data['brand_id']       = null;
        $data['categories_ids'] = [];
        $data['order_value']    = null;

        return $data;
    }
    public static function generateBonusDiscountData($requestData)
    {
        $data                    = [];
        $data['type_id']         = self::$BONUS_ON_ITEM_TYPE_ID;
        $data['name']            = $requestData['name'];
        $data['client_group_id'] = $requestData['client_group_id'];
        $data['active']          = $requestData['active'];
        $data['valid_from']      = null;
        if (isset($requestData['valid_from'])) {
            $data['valid_from'] = $requestData['valid_from'];
        }
        $data['valid_until'] = null;
        if (isset($requestData['valid_until'])) {
            $data['valid_until'] = $requestData['valid_until'];
        }

        $isActive = self::calculateIsActive($data['valid_from'], $data['valid_until']);
        if (!$isActive && $data['active']) {
            $data['active'] = false;
        }

        if (isset($requestData['max_uses'])) {
            $data['max_uses'] = $requestData['max_uses'];
        }

        $data['data'] = json_encode([
                                        'quantity'           => $requestData['quantity'],
                                        'result_product_id'  => $requestData['result_product_id'],
                                        'value_type_id'      => $requestData['value_type_id'],
                                        'max_uses_per_order' => isset($requestData['max_uses_per_order']) ? $requestData['max_uses_per_order'] : null,
                                    ]);

        $data['value']          = $requestData['value'];
        $data['product_id']     = $requestData['product_id'];
        $data['brand_id']       = null;
        $data['categories_ids'] = [];
        $data['order_value']    = null;

        return $data;
    }
    public static function getGlobalFixed($basket)
    {
        if (!isset($basket->total)) {
            $basket->total = 0;
        }

        return Discount::getBaseQuery()
            ->where(function ($q) use ($basket) {
                $qq = $q->whereNull('promo_code');
                if (!is_null($basket->promo_code)) {
                    $qq = $qq->where('promo_code', $basket->promo_code);
                }

                return $qq;
            })
            ->where('applies_to', Discount::$ABOVE_ORDER_VALUE_APPLICATION)
            ->where('order_value', '<=', $basket->total)
            ->whereIn('type_id', [Discount::$FIXED_AMOUNT_TYPE_ID, Discount::$FIXED_PERCENT_TYPE_ID])
            ->orderBy('order_value', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get()
            ->first();
    }
    public static function getBaseQuery()
    {

        return Discount::where('active', true)->where('client_group_id', (Auth::guard('shop')->check() ? Auth::guard('shop')->user()->client_group_id : ShopRegisteredUser::$DEFAULT_CLIENT_GROUP_ID))
            ->where(function ($q) {
                return $q->whereNull('valid_from')->orWhere('valid_from', '>=', Carbon::now()->startOfDay());
            })->where(function ($q) {
                return $q->whereNull('valid_until')->orWhere('valid_until', '<=', Carbon::now()->endOfDay());
            })->where(function ($q) {
                return $q->whereNull('max_uses')->orWhereRaw('max_uses-current_uses > 0');
            });
    }
    public static function getGlobalDelivery($basket)
    {
        if (!isset($basket->total)) {
            $basket->total = 0;
        }

        return Discount::getBaseQuery()
            ->where(function ($q) use ($basket) {
                $qq = $q->whereNull('promo_code');
                if (!is_null($basket->promo_code)) {
                    $qq = $qq->where('promo_code', $basket->promo_code);
                }

                return $qq;
            })
            ->where('applies_to', Discount::$ABOVE_ORDER_VALUE_APPLICATION)
            ->where('order_value', '<=', $basket->total)
            ->whereIn('type_id', [Discount::$FIXED_FREE_DELIVERY_TYPE_ID])
            ->orderBy('order_value', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get()
            ->first();
    }
    public static function getQuantityDiscount($basketProduct)
    {
        $quantityDiscount = Discount::getBaseQuery()->where('type_id', Discount::$QUANTITY_TYPE_ID)
            ->where('product_id', $basketProduct->product->id)->orderBy('created_at', 'desc')->first();

        if (is_null($quantityDiscount)) {
            return null;
        }

        $data = json_decode($quantityDiscount->data, true);
        foreach ($data as $ranges) {
            if ($basketProduct->product_quantity >= $ranges['from_quantity'] && $basketProduct->product_quantity <= $ranges['to_quantity']) {
                $quantityDiscount->product_price = $ranges['price'];

                return $quantityDiscount;
            }
        }
    }
   
    public static function getFreeDeliveryDiscount($basketProduct, $promoCode)
    {
        return Discount::getBaseQuery()->where('type_id', Discount::$FIXED_FREE_DELIVERY_TYPE_ID)
            ->where(function ($q) use ($promoCode) {
                $qq = $q->whereNull('promo_code');
                if (!is_null($promoCode)) {
                    $qq = $qq->where('promo_code', $promoCode);
                }

                return $qq;
            })->where(function ($q) use ($basketProduct) {
                return $q->where(function ($qq) {
                    return $qq->where('applies_to', Discount::$EVERY_PRODUCT_APPLICATION);
                })->orWhere(function ($qq) use ($basketProduct) {
                    return $qq->where('applies_to', Discount::$PRODUCT_APPLICATION)->where('product_id', $basketProduct->product->id);
                })->orWhere(function ($qq) use ($basketProduct) {
                    return $qq->where('applies_to', Discount::$CATEGORY_APPLICATION)->whereHas('categories', function ($qqq) use ($basketProduct) {
                        return $qqq->where('category_id', $basketProduct->product->category_id);
                    });
                })->orWhere(function ($qq) use ($basketProduct) {
                    return $qq->where('applies_to', Discount::$BRAND_APPLICATION)->where('brand_id', $basketProduct->product->brand->id);
                });
            })->get()->first();
    }

    public static function getFixedDiscounts($basketProduct, $promoCode)
    {
        $fixedDiscounts = Discount::getBaseQuery()->whereIn('type_id', [Discount::$FIXED_PERCENT_TYPE_ID, Discount::$FIXED_AMOUNT_TYPE_ID])
            ->where(function ($q) use ($promoCode) {
                $qq = $q->whereNull('promo_code');
                if (!is_null($promoCode)) {
                    $qq = $qq->where('promo_code', $promoCode);
                }

                return $qq;
            })->where(function ($q) use ($basketProduct) {
                return $q->where(function ($qq) {
                    return $qq->where('applies_to', Discount::$EVERY_PRODUCT_APPLICATION);
                })->orWhere(function ($qq) use ($basketProduct) {
                    return $qq->where('applies_to', Discount::$PRODUCT_APPLICATION)->where('product_id', $basketProduct->product->id);
                })->orWhere(function ($qq) use ($basketProduct) {
                    return $qq->where('applies_to', Discount::$CATEGORY_APPLICATION)->whereHas('categories', function ($qqq) use ($basketProduct) {
                        return $qqq->where('category_id', $basketProduct->product->category_id);
                    });
                })->orWhere(function ($qq) use ($basketProduct) {
                    return $qq->where('applies_to', Discount::$BRAND_APPLICATION)->where('brand_id', $basketProduct->product->brand->id);
                });
            })->get();

        $discounts        = [];
        $currentAppliesTo = null;
        foreach ($fixedDiscounts as $fixedDiscount) {
            if (count($discounts) == 0) {
                $discounts[$fixedDiscount->id] = $fixedDiscount;
                $currentAppliesTo              = $fixedDiscount->applies_to;
            } else {
                if ($currentAppliesTo == Discount::$PRODUCT_APPLICATION) {
                    if ($fixedDiscount->applies_to == Discount::$PRODUCT_APPLICATION) {
                        $discounts[$fixedDiscount->id] = $fixedDiscount;
                    }
                } else {
                    if ($currentAppliesTo == $fixedDiscount->applies_to) {
                        $discounts[$fixedDiscount->id] = $fixedDiscount;
                    } else if ($currentAppliesTo < $fixedDiscount->applies_to) {
                        $discounts                     = [];
                        $discounts[$fixedDiscount->id] = $fixedDiscount;
                        $currentAppliesTo              = $fixedDiscount->applies_to;
                    }
                }
            }
        }

        if (count($discounts) < 1) {
            return null;
        }

        return $discounts;
    }

    public static function getDiscountsAmount($discounts, $basketProductVatAppliedPrice)
    {
        $calcDiscountAmount = 0;
        if(!is_null($discounts)) {
            foreach ($discounts as $discountId => $discount) {
                if ($discount->type_id == Discount::$FIXED_AMOUNT_TYPE_ID) {
                    $calcDiscountAmount += $discount->value;
                } else {
                    $calcDiscountAmount += $basketProductVatAppliedPrice * ($discount->value / 100);
                }
            }
        }

        return $calcDiscountAmount;
    }
    protected static function newFactory()
    {
        return DiscountFactory::new();
    }
    public function getHumanReadableType()
    {
        $types = [
            self::$FIXED_AMOUNT_TYPE_ID        => trans('shop::admin.discounts.type_fixed_amount'),
            self::$FIXED_PERCENT_TYPE_ID       => trans('shop::admin.discounts.type_fixed_percent'),
            self::$FIXED_FREE_DELIVERY_TYPE_ID => trans('shop::admin.discounts.type_fixed_free_delivery'),
            self::$QUANTITY_TYPE_ID            => trans('shop::admin.discounts.type_quantity'),
            self::$BONUS_ON_ITEM_TYPE_ID       => trans('shop::admin.discounts.type_bonus_on_item'),

        ];

        return $types[$this->type_id];
    }
    public function updateActive()
    {
        $isActive = self::calculateIsActive($this->valid_from, $this->valid_until);
        if ($this->active != $isActive) {
            $this->update(['active' => $isActive]);
        }
    }
    public function getPrices()
    {
        return json_encode($this->data, true);
    }
    public function getQuantity()
    {
        try {
            return json_decode($this->data, true)['quantity'];
        } catch (Exception $e) {
            return null;
        }
    }
    public function getMaxUsesPerOrder()
    {
        try {
            return json_decode($this->data, true)['max_uses_per_order'];
        } catch (Exception $e) {
            return null;
        }
    }
    public function getResultProduct()
    {
        try {
            $productId = $this->getResultProductId();
            if ($productId == null) {
                return null;
            }

            return Product::find($productId);
        } catch (Exception $e) {
            return null;
        }
    }
    public function getResultProductId()
    {
        try {
            return json_decode($this->data, true)['result_product_id'];
        } catch (Exception $e) {
            return null;
        }
    }
    public function getValueTypeId()
    {
        try {
            return json_decode($this->data, true)['value_type_id'];
        } catch (Exception $e) {
            return null;
        }
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_discount');
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
