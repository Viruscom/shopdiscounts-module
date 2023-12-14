@extends('layouts.admin.app')

@section('content')
    @include('shopdiscounts::admin.breadcrumbs')
    @include('admin.notify')

    <form method="POST" action="{{route('discounts.update',['id'=>$discount->id])}}">
        @csrf

        <span class="hidden curr-editor"></span>
        <div class="col-xs-12 p-0">
            @csrf
            @include('admin.partials.on_edit.form_actions_top')
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h3>Редакция на отстъпка тип: <strong>{{ request()->segment(4) == 1 ? 'Фиксирана стойност' : 'Фиксиран процент' }}</strong></h3><br>
            </div>
            
            <div class="col-md-6 col-xs-12">
                @include('admin.partials.on_edit.form_fields.input_text_without_lang', ['fieldName' => 'name', 'label' => trans('shop::admin.discounts.name'), 'required' => true, 'model' => $discount])

                <div class="form-group">
                    <label for="valid_from" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.valid_from')</label>

                    <input id="valid_from" type="date" class="form-control @error('valid_from') is-invalid @enderror" name="valid_from" value="{{ old('valid_from') ?? $discount->valid_from }}" autocomplete="valid_from">
                    @error('valid_from')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                @if($discount->type_id != Modules\ShopDiscounts\Entities\Discount::$FIXED_FREE_DELIVERY_TYPE_ID)
                    <div class="form-group">
                        <label for="value" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.amount')</label>
                        <input id="value" type="number" min="0" step="0.01" class="form-control @error('value') is-invalid @enderror" name="value" value="{{ old('value') ?? $discount->value }}" autocomplete="value" required>
                        @error('value')
                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                        @enderror
                    </div>
                @endif

                <div class="form-group">
                    <label for="promo_code" class="control-label p-b-10">@lang('shop::admin.discounts.promo_code')</label>
                    <input id="promo_code" type="text" class="form-control @error('promo_code') is-invalid @enderror" name="promo_code" value="{{ old('promo_code') ?? $discount->promo_code }}" autocomplete="promo_code">
                    @error('promo_code')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="active" class="control-label p-b-10"><span class="text-purple">* </span>{{ __('shop::admin.discounts.active') }}</label>

                    <select id="active" class="form-control @error('active') is-invalid @enderror" name="active" required>
                        <option value="0" {{old('active')==0 || $discount->active==0 ? 'selected':''}}>{{ __('admin.common.no') }}</option>
                        <option value="1" {{old('active')==1 || $discount->active==1 ? 'selected':''}}>{{ __('admin.common.yes') }}</option>
                    </select>

                    @error('active')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="client_group_id" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.client_group'):</label>
                    <select id="client_group_id" class="form-control select2" name="client_group_id" required>
                        <option value="" {{old('client_group_id')=='' ? 'selected':''}}>{{__('admin.common.please_select')}}</option>
                        @foreach($clientGroups as $clientGroup)
                            <option value="{{$clientGroup}}" {{old('client_group_id')==$clientGroup || $discount->client_group_id==$clientGroup ? 'selected':''}}>{{__('shop::admin.discounts.client_group_'.$clientGroup)}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="valid_until" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.valid_to')</label>

                    <input id="valid_until" type="date" class="form-control @error('valid_until') is-invalid @enderror" name="valid_until" value="{{ old('valid_until') ?? $discount->valid_until }}" autocomplete="valid_until">

                    @error('valid_until')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="max_uses" class="control-label p-b-10">@lang('shop::admin.discounts.max_uses')</label>

                    <input id="max_uses" type="number" min="0" class="form-control @error('max_uses') is-invalid @enderror" name="max_uses" value="{{ old('max_uses') ?? $discount->max_uses }}" autocomplete="max_uses">

                    @error('max_uses')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="applies_to" class="control-label p-b-10">@lang('shop::admin.discounts.applies_to')*</label>

                    <select id="applies_to" class="form-control @error('applies_to') is-invalid @enderror" name="applies_to" required onchange="showInput(this)">
                        @foreach($applications as $application)
                            <option value="{{$application}}" {{old('applies_to')==$application || $discount->applies_to==$application ? 'selected':''}}>{{__('shop::admin.discounts.applies_to_'.$application)}}</option>
                        @endforeach
                    </select>

                    @error('applies_to')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div style="display:none" class="form-group applies-to-input applies-to-input-{{Modules\ShopDiscounts\Entities\Discount::$PRODUCT_APPLICATION}}">
                    <label for="product_id" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.choose_product')</label>

                    <select id="product_id" class="should-be-required form-control @error('product_id') is-invalid @enderror" name="product_id">
                        @foreach($products as $product)
                            <option value="{{$product->id}}" {{old('product_id')==$product->id || $discount->product_id==$product->id ? 'selected':''}}>{{$product->title}}</option>
                        @endforeach
                    </select>

                    @error('product_id')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div style="display:none" class="form-group applies-to-input applies-to-input-{{Modules\ShopDiscounts\Entities\Discount::$BRAND_APPLICATION}}">
                    <label for="brand_id" class="control-label p-b-10">@lang('shop::admin.discounts.choose_brand')*</label>

                    <select id="brand_id" class="should-be-required  form-control @error('brand_id') is-invalid @enderror" name="brand_id">
                        @foreach($brands as $brand)
                            <option value="{{$brand->id}}" {{old('brand_id')==$brand->id || $discount->brand_id==$brand->id ? 'selected':''}}>{{$brand->title}}</option>
                        @endforeach
                    </select>

                    @error('brand_id')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div style="display:none" class="form-group applies-to-input applies-to-input-{{Modules\ShopDiscounts\Entities\Discount::$CATEGORY_APPLICATION}}">
                    <label for="categories_id" class="control-label p-b-10">@lang('shop::admin.discounts.choose_categories')*</label>

                    <select multiple id="categories_ids" class="should-be-required form-control @error('categories_ids.*') is-invalid @enderror" name="categories_ids[]">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" {{is_array(old('categories_ids')) && in_array($category->id , old('categories_ids')) || $discount->categories->contains($category->id) ? 'selected':''}}>{{$category->title}}</option>
                        @endforeach
                    </select>

                    @error('categories_ids.*')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div style="display:none" class="form-group applies-to-input applies-to-input-{{Modules\ShopDiscounts\Entities\Discount::$ABOVE_ORDER_VALUE_APPLICATION}}">
                    <label for="order_value" class="control-label p-b-10">@lang('shop::admin.discounts.order_amount')*</label>

                    <input id="order_value" type="number" min="0" step="0.01" class="should-be-required form-control @error('order_value') is-invalid @enderror" name="order_value" value="{{ old('order_value') ?? $discount->order_value }}" autocomplete="order_value">

                    @error('order_value')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                @include('admin.partials.on_edit.form_actions_bottom')
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            showInput("#applies_to");
        })

        function showInput(el) {
            $(".applies-to-input").hide();
            $('.should-be-required').removeAttr('required');
            if ($(".applies-to-input-" + $(el).val()).length > 0) {
                $(".applies-to-input-" + $(el).val()).show();
                $('.applies-to-input-' + $(el).val()).find('.should-be-required').attr('required', 'required');
            }
        }
    </script>
@endsection
