<?php

namespace Modules\ShopDiscounts\Http\Requests;

use App\Models\User;
use Modules\ShopDiscounts\Entities\Discount;
use Illuminate\Foundation\Http\FormRequest;

class QuantityDiscountRequest extends FormRequest
{
    private $firstLPriceErorrs = [];
    private $secondLPriceErorrs = [];
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|min:2|max:255',
            'client_group_id' => 'nullable|in:'.implode(',', User::getClientGroups()),
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'max_uses' => 'nullable|integer|min:1',
            'active' => 'integer|in:0,1',
            'product_id' => 'required|exists:products,id',
            'prices' => 'required|array',
            'prices.*.from_quantity' => 'required|numeric|min:0',
            'prices.*.to_quantity' => 'required|numeric|min:1',
            'prices.*.price' => 'required|numeric|min:0',
        ];

        if(isset($this->valid_from) && !is_null($this->valid_from)
            && isset($this->valid_until) && !is_null($this->valid_until)){
            $rules['valid_until'] = 'date|after_or_equal:'.$this->valid_from;
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

    public function validatePriceRanges(){
        $this->firstLPriceErorrs = [];
        $this->secondLPriceErorrs = [];
        for($i=0;$i<count($this->prices);$i++){
            $price = $this->prices[$i];
            if($price['from_quantity']>$price['to_quantity']){
                $this->firstLPriceErorrs['prices.'.$i.'.to_quantity'] = 'The field prices.'.$i.'.to_quantity should be greater than prices.'.$i.'.from_quantity value';
            }

            for($j=0;$j<$i;$j++){
                $currPrice = $this->prices[$j];
                if($price['from_quantity']>=$currPrice['from_quantity'] && $price['from_quantity']<=$currPrice['to_quantity']){ 
                    $this->secondLPriceErorrs['prices.'.$i.'.from_quantity'] = 'Invalid range value';
                } 
                if($price['to_quantity']>=$currPrice['from_quantity'] && $price['to_quantity']<=$currPrice['to_quantity']){
                    $this->secondLPriceErorrs['prices.'.$i.'.to_quantity'] = 'Invalid range value';
                }
            }
        }
    }

    public function hasPriceErrors(){
        return count($this->firstLPriceErorrs)>0 || count($this->secondLPriceErorrs);
    }

    public function priceErrors(){
        if(count($this->firstLPriceErorrs)>0){
            return $this->firstLPriceErorrs;
        }


        if(count($this->secondLPriceErorrs)>0){
            return $this->secondLPriceErorrs;
        }

        return [];
    }
}
