@extends('shopdiscounts::layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">CREATE {{$type}}
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                   
                    <form method="POST" action="{{route('discounts.store',['type'=>$type])}}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}*</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="client_group_id" class="col-md-4 col-form-label text-md-right">{{ __('Client group')}}*</label>

                            <div class="col-md-6">
                                <select id="client_group_id" class="form-control @error('client_group_id') is-invalid @enderror" name="client_group_id" autofocus required>
                                     <option value="" {{old('client_group_id')=='' ? 'selected':''}}>{{__('Choose')}}</option>
                                    @foreach($clientGroups as $clientGroup)
                                        <option value="{{$clientGroup}}" {{old('client_group_id')==$clientGroup ? 'selected':''}}>{{__('client group ').$clientGroup}}</option>
                                    @endforeach
                                </select>

                                @error('client_group_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="valid_from" class="col-md-4 col-form-label text-md-right">{{ __('Valid from') }}</label>

                            <div class="col-md-6">
                                <input id="valid_from" type="date" class="form-control @error('valid_from') is-invalid @enderror" name="valid_from" value="{{ old('valid_from') }}" autocomplete="valid_from" autofocus>

                                @error('valid_from')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="valid_until" class="col-md-4 col-form-label text-md-right">{{ __('Valid until') }}</label>

                            <div class="col-md-6">
                                <input id="valid_until" type="date" class="form-control @error('valid_until') is-invalid @enderror" name="valid_until" value="{{ old('valid_until') }}" autocomplete="valid_until" autofocus>

                                @error('valid_until')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="max_uses" class="col-md-4 col-form-label text-md-right">{{ __('Max uses') }}</label>

                            <div class="col-md-6">
                                <input id="max_uses" type="number" min="0" class="form-control @error('max_uses') is-invalid @enderror" name="max_uses" value="{{ old('max_uses') }}" autocomplete="max_uses" autofocus>

                                @error('max_uses')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                         <div class="form-group row">
                            <label for="active" class="col-md-4 col-form-label text-md-right">{{ __('Active') }}</label>

                            <div class="col-md-6">
                                <select id="active" class="form-control @error('active') is-invalid @enderror" name="active" required autofocus>
                                        <option value="0" {{old('active')==0 ? 'selected':''}}>{{__('No')}}</option>
                                        <option value="1" {{old('active')==1 ? 'selected':''}}>{{__('Yes')}}</option>
                                </select>

                                @error('active')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="applies_to" class="col-md-4 col-form-label text-md-right">{{ __('Applies to') }}*</label>

                            <div class="col-md-6">
                                <select id="applies_to" class="form-control @error('applies_to') is-invalid @enderror" name="applies_to" required autofocus onchange="showInput(this)">
                                    @foreach($applications as $application)
                                        <option value="{{$application}}" {{old('applies_to')==$application ? 'selected':''}}>{{__('applies to ').$application}}</option>
                                    @endforeach
                                </select>

                                @error('applies_to')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div style="display:none" class="form-group row applies-to-input applies-to-input-{{Modules\ShopDiscounts\Entities\Discount::$PRODUCT_APPLICATION}}">
                            <label for="product_id" class="col-md-4 col-form-label text-md-right">{{ __('Product') }}*</label>

                            <div class="col-md-6">
                                <select id="product_id" class="should-be-required form-control @error('product_id') is-invalid @enderror" name="product_id" autofocus>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}" {{old('product_id')==$product->id ? 'selected':''}}>{{__('Product ').$product->id}}</option>
                                    @endforeach
                                </select>

                                @error('product_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div  style="display:none" class="form-group row applies-to-input applies-to-input-{{Modules\ShopDiscounts\Entities\Discount::$BRAND_APPLICATION}}">
                            <label for="brand_id" class="col-md-4 col-form-label text-md-right">{{ __('Brand') }}*</label>

                            <div class="col-md-6">
                                <select id="brand_id" class="should-be-required  form-control @error('brand_id') is-invalid @enderror" name="brand_id" autofocus>
                                    @foreach($brands as $brand)
                                        <option value="{{$brand->id}}" {{old('brand_id')==$brand->id ? 'selected':''}}>{{__('Brand ').$brand->id}}</option>
                                    @endforeach
                                </select>

                                @error('brand_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div style="display:none" class="form-group row applies-to-input applies-to-input-{{Modules\ShopDiscounts\Entities\Discount::$CATEGORY_APPLICATION}}">
                            <label for="categories_id" class="col-md-4 col-form-label text-md-right">{{ __('Categories') }}*</label>

                            <div class="col-md-6">
                                <select multiple id="categories_ids" class="should-be-required form-control @error('categories_ids.*') is-invalid @enderror" name="categories_ids[]" autofocus>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{is_array(old('categories_ids')) && in_array($category->id , old('categories_ids')) ? 'selected':''}}>{{__('Category ').$category->id}}</option>
                                    @endforeach
                                </select>

                                @error('categories_ids.*')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div style="display:none" class="form-group row applies-to-input applies-to-input-{{Modules\ShopDiscounts\Entities\Discount::$ABOVE_ORDER_VALUE_APPLICATION}}">
                            <label for="order_value" class="col-md-4 col-form-label text-md-right">{{ __('Order value') }}*</label>

                            <div class="col-md-6">
                                <input id="order_value" type="number" min="0" step="0.01" class="should-be-required form-control @error('order_value') is-invalid @enderror" name="order_value" value="{{ old('order_value') }}" autocomplete="order_value" autofocus >

                                @error('order_value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if($type != Modules\ShopDiscounts\Entities\Discount::$FIXED_FREE_DELIVERY_TYPE_ID)
                        <div class="form-group row">
                            <label for="value" class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}*</label>

                            <div class="col-md-6">
                                <input id="value" type="number" min="0" step="0.01" class="form-control @error('value') is-invalid @enderror" name="value" value="{{ old('value') }}" autocomplete="value" autofocus required>

                                @error('value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <label for="promo_code" class="col-md-4 col-form-label text-md-right">{{ __('Promo code') }}</label>

                            <div class="col-md-6">
                                <input id="promo_code" type="text" class="form-control @error('promo_code') is-invalid @enderror" name="promo_code" value="{{ old('promo_code') }}" autocomplete="promo_code" autofocus>

                                @error('promo_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary float-right">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        showInput("#applies_to");
    })
function showInput(el){
    $(".applies-to-input").hide();
    $('.should-be-required').removeAttr('required');
    if($(".applies-to-input-"+$(el).val()).length>0){
        $(".applies-to-input-"+$(el).val()).show();   
        $('.applies-to-input-'+$(el).val()).find('.should-be-required').attr('required','required');
    }
}
</script>
@endsection
