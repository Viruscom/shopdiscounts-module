<?php

    namespace Modules\ShopDiscounts\Http\Controllers;

    use App\Helpers\MainHelper;
    use App\Helpers\WebsiteHelper;
    use App\Models\Pages\Page;
    use App\Models\User;
    use Illuminate\Contracts\Support\Renderable;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Modules\Shop\Models\Admin\Brands\Brand;
    use Modules\Shop\Models\Admin\ProductCategory\Category;
    use Modules\Shop\Models\Admin\Products\Product;
    use Modules\ShopDiscounts\Entities\Discount;
    use Modules\ShopDiscounts\Http\Requests\BonusOnItemDiscountRequest;
    use Modules\ShopDiscounts\Http\Requests\FixedAmountDiscountRequest;
    use Modules\ShopDiscounts\Http\Requests\FixedPercentDiscountRequest;
    use Modules\ShopDiscounts\Http\Requests\FreeDeliveryDiscountRequest;
    use Modules\ShopDiscounts\Http\Requests\QuantityDiscountRequest;

    class ShopDiscountsController extends Controller
    {
        public function index()
        {
            $discounts = Discount::orderBy('type_id')->get();

            return view('shopdiscounts::admin.index', ['discounts' => $discounts]);
        }

        public function store($type, Request $request)
        {
            $discountData = [];
            switch ($type) {
                case Discount::$FIXED_AMOUNT_TYPE_ID;
                    $request = FixedAmountDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $request->validateSupplierPriceLimit();
                    if ($request->hasSupplierPriceLimitErrors()) {
                        return redirect()->back()->withInput()->withErrors($request->supplierPriceLimitErrors());
                    }
                    $discountData = Discount::generateFixedDiscountData($type, $request->all());

                    break;
                case Discount::$FIXED_PERCENT_TYPE_ID;
                    $request = FixedPercentDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $request->validateSupplierPriceLimit();
                    if ($request->hasSupplierPriceLimitErrors()) {
                        return redirect()->back()->withInput()->withErrors($request->supplierPriceLimitErrors());
                    }
                    $discountData = Discount::generateFixedDiscountData($type, $request->all());

                    break;
                case Discount::$FIXED_FREE_DELIVERY_TYPE_ID;
                    $request = FreeDeliveryDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $discountData = Discount::generateFixedDiscountData($type, $request->all());

                    break;
                case Discount::$QUANTITY_TYPE_ID;
                    $request = QuantityDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $request->validatePriceRanges();
                    if ($request->hasPriceErrors()) {
                        return redirect()->back()->withInput()->withErrors($request->priceErrors());
                    }

                    $discountData = Discount::generateQuantityDiscountData($request->all());
                    break;
                case Discount::$BONUS_ON_ITEM_TYPE_ID;
                    $request = BonusOnItemDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $discountData = Discount::generateBonusDiscountData($request->all());

                    break;
                default:
                    return abort(404);
            }

            $categoriesIds = $discountData['categories_ids'];
            unset($discountData['categories_ids']);
            $discount = Discount::create($discountData);
            $discount->categories()->sync($categoriesIds);

            return redirect(route('discounts.index'))->with('success-message', trans('admin.common.successful_create'));
        }

        public function create($type)
        {
            $products     = Product::all();
            $clientGroups = User::getClientGroups();

            switch ($type) {
                case Discount::$FIXED_AMOUNT_TYPE_ID;
                case Discount::$FIXED_PERCENT_TYPE_ID;
                case Discount::$FIXED_FREE_DELIVERY_TYPE_ID;
                    $applications = Discount::getApplications();
                    $brands       = Brand::all();
                    $categories   = Category::all();

                    return view('shopdiscounts::discounts.create_fixed', ['type' => $type, 'clientGroups' => $clientGroups, 'applications' => $applications, 'products' => $products, 'brands' => $brands, 'categories' => $categories]);
                case Discount::$QUANTITY_TYPE_ID;
                    return view('shopdiscounts::discounts.create_quantity', ['type' => $type, 'clientGroups' => $clientGroups, 'products' => $products]);
                case Discount::$BONUS_ON_ITEM_TYPE_ID;
                    $valueTypes = Discount::getValueTypes();

                    return view('shopdiscounts::discounts.create_bonus', ['type' => $type, 'clientGroups' => $clientGroups, 'products' => $products, 'valueTypes' => $valueTypes]);
                default:
                    return abort(404);
            }
        }
        /**
         * Show the specified resource.
         *
         * @param int $id
         *
         * @return Renderable
         */
        public function show($id)
        {
            return view('shopdiscounts::show');
        }

        public function edit($id)
        {
            $discount = Discount::find($id);
            WebsiteHelper::redirectBackIfNull($discount);
            $products     = Product::all();
            $clientGroups = User::getClientGroups();

            switch ($discount->type_id) {
                case Discount::$FIXED_AMOUNT_TYPE_ID;
                case Discount::$FIXED_PERCENT_TYPE_ID;
                case Discount::$FIXED_FREE_DELIVERY_TYPE_ID;
                    $applications = Discount::getApplications();
                    $brands       = Brand::all();
                    $categories   = Category::all();

                    return view('shopdiscounts::discounts.edit_fixed', ['discount' => $discount, 'clientGroups' => $clientGroups, 'applications' => $applications, 'products' => $products, 'brands' => $brands, 'categories' => $categories]);
                case Discount::$QUANTITY_TYPE_ID;
                    return view('shopdiscounts::discounts.edit_quantity', ['discount' => $discount, 'clientGroups' => $clientGroups, 'products' => $products]);
                case Discount::$BONUS_ON_ITEM_TYPE_ID;
                    $valueTypes = Discount::getValueTypes();

                    return view('shopdiscounts::discounts.edit_bonus', ['discount' => $discount, 'products' => $products, 'valueTypes' => $valueTypes, 'clientGroups' => $clientGroups]);
                default:
                    return abort(404);
            }
        }

        public function update($id, Request $request)
        {
            $discount = Discount::find($id);
            WebsiteHelper::redirectBackIfNull($discount);
            $discountData = [];
            switch ($discount->type_id) {
                case Discount::$FIXED_AMOUNT_TYPE_ID;
                    $request = FixedAmountDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $request->validateSupplierPriceLimit();
                    if ($request->hasSupplierPriceLimitErrors()) {
                        return redirect()->back()->withInput()->withErrors($request->supplierPriceLimitErrors());
                    }
                    $discountData = Discount::generateFixedDiscountData($discount->type_id, $request->all());

                    break;
                case Discount::$FIXED_PERCENT_TYPE_ID;
                    $request = FixedPercentDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $request->validateSupplierPriceLimit();
                    if ($request->hasSupplierPriceLimitErrors()) {
                        return redirect()->back()->withInput()->withErrors($request->supplierPriceLimitErrors());
                    }
                    $discountData = Discount::generateFixedDiscountData($discount->type_id, $request->all());

                    break;
                case Discount::$FIXED_FREE_DELIVERY_TYPE_ID;
                    $request = FreeDeliveryDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $discountData = Discount::generateFixedDiscountData($discount->type_id, $request->all());
                    $discount->update($discountData);

                    break;
                case Discount::$QUANTITY_TYPE_ID;
                    $request = QuantityDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $request->validatePriceRanges();
                    if ($request->hasPriceErrors()) {
                        return redirect()->back()->withInput()->withErrors($request->priceErrors());
                    }

                    $discountData = Discount::generateQuantityDiscountData($request->all());
                    break;
                case Discount::$BONUS_ON_ITEM_TYPE_ID;
                    $request = BonusOnItemDiscountRequest::createFrom($request);
                    $request->validate($request->rules());
                    $discountData = Discount::generateBonusDiscountData($request->all());

                    break;
                default:
                    return abort(404);
            }

            $categoriesIds = $discountData['categories_ids'];
            unset($discountData['categories_ids']);
            $discount->update($discountData);
            $discount->categories()->sync($categoriesIds);

            return redirect(route('discounts.index'))->with('success-message', trans('admin.common.successful_edit'));
        }

        public function destroy($id)
        {
            $discount = Discount::find($id);
            WebsiteHelper::redirectBackIfNull($discount);
            $discount->delete();

            return redirect(route('discounts.index'))->with('success-message', trans('admin.common.successful_delete'));
        }

        public function active($id, $active): RedirectResponse
        {
            $discount = Discount::find($id);
            WebsiteHelper::redirectBackIfNull($discount);

            $discount->update(['active' => $active]);

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
    }
