<?php

    namespace Modules\ShopDiscounts\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;
    use Modules\ShopDiscounts\Entities\Discount;

    class BonusOnItemDiscountRequest extends FormRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules()
        {
            $rules = [
                'name'               => 'required|min:2|max:255',
                'client_group_id'    => 'nullable|in:' . implode(',', ShopRegisteredUser::getClientGroups()),
                'valid_from'         => 'nullable|date',
                'valid_until'        => 'nullable|date',
                'max_uses'           => 'nullable|integer|min:1',
                'active'             => 'integer|in:0,1',
                'product_id'         => 'required|exists:products,id',
                'result_product_id'  => 'required|exists:products,id',
                'value'              => 'required|numeric|min:0',
                'value_type_id'      => 'required|numeric|in:' . implode(',', Discount::getValueTypes()),
                'quantity'           => 'required|numeric|min:1',
                'max_uses_per_order' => 'nullable|numeric|min:1',
            ];

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
