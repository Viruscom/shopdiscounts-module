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

class ForDelete
{
    public function kolichestvena()
    {
        $productQuantityDiscount = Discount::getQuantityDiscountAbstract($product);
        //tova shte ti dade koli4estvena otstupka za tozi product. Ako e null naì nqma ako ne e null mojesh da i prinitarsh data i ako iskash da pokajesh v stranicat ana produkta kakva to`no e koli4estvenata otstupka
        $data = json_decode($productQuantityDiscount->data, true);
        foreach ($data as $ranges) {
            //OT: $ranges['from_quantity'] DO: $ranges['to_quantity'] CENA:$ranges['price'];
        }
        //tozi forech 6te ti printira vsi4kite reidjove sus soutvetnite ceni za vseki reindjOkay? - da
    }


    public function freeDelivery()
    {
        //=================================================== =
        Discount::getFreeDeliveryDiscountAbstract($product);
        //ako e null nqma free deliveryako nee nul ima free delivery Okay?- da
    }

    public function fixed()
    {
        //=====================================================
        $discounts = Discount::getFixedDiscountsAbstract($product)
        //ako e null nqma fiksirani diskauntiako ne e null zna4i e arrayne znam kak shte ti e logikata za pokazvaneto na fiksiranti diskaunti no ako gorniq metod ti vurne array moje da go foreachnesh taka:
        foreach ($discounts as $discountId => $discount) {
            //ako e fiksiran amount 5-10-20 lv
            if ($discount->type_id == Discount::$FIXED_AMOUNT_TYPE_ID) {
                $discount->value;
                //-> tova e to4no kolko e 5-10-20lv
            } else {
                //ako ne e fiskiran amount ozna4ava 4e e procent ot cenata na produkta
                $price * ($discount->value / 100);
            }
        }
    }
}

//ami to tova sa za konkreten productve4e global fixed i global delivery sa za nivo koli4kaok, mersi

//$product->getVat($country, $city) trqbva da ti dade vata na produkta



