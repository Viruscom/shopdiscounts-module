<?php

    namespace Modules\ShopDiscounts\Http\Requests;

    use App\Models\User;
    use Illuminate\Foundation\Http\FormRequest;
    use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;
    use Modules\ShopDiscounts\Entities\Discount;

    class FreeDeliveryDiscountRequest extends FormRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules()
        {
            $rules = [
                'name'            => 'required|min:2|max:255',
                'client_group_id' => 'nullable|in:' . implode(',', ShopRegisteredUser::getClientGroups()),
                'valid_from'      => 'nullable|date',
                'valid_until'     => 'nullable|date',
                'max_uses'        => 'nullable|integer|min:1',
                'active'          => 'integer|in:0,1',
                'applies_to'      => 'required|in:' . implode(',', Discount::getApplications()),
                'promo_code'      => 'nullable|min:2|max:255',

            ];

            if (isset($this->type) && $this->type != Discount::$FIXED_FREE_DELIVERY_TYPE_ID || isset($this->type_id) && $this->type_id != Discount::$FIXED_FREE_DELIVERY_TYPE_ID) {
                $rules['value'] = 'required|numeric|min:0';
            }

            switch ($this->applies_to) {
                case Discount::$PRODUCT_APPLICATION:
                    $rules['product_id'] = 'required|exists:products,id';
                    break;
                case Discount::$CATEGORY_APPLICATION:
                    $rules['categories_ids']   = 'required|array';
                    $rules['categories_ids.*'] = 'exists:categories,id';
                    break;
                case Discount::$BRAND_APPLICATION:
                    $rules['brand_id'] = 'required|exists:product_brands,id';
                    break;
                case Discount::$ABOVE_ORDER_VALUE_APPLICATION:
                    $rules['order_value'] = 'required|numeric|min:0';
                    break;
            }

            if (isset($this->valid_from) && !is_null($this->valid_from)
                && isset($this->valid_until) && !is_null($this->valid_until)) {
                $rules['valid_until'] = 'date|after_or_equal:' . $this->valid_from;
            }

            return $rules;
        }

        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize()
        {
            return true;
        }
    }
