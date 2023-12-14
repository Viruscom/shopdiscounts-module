@extends('layouts.admin.app')

@section('content')
    @include('shopdiscounts::admin.breadcrumbs')
    @include('admin.notify')
    <form method="POST" action="{{route('discounts.update',['id'=>$discount->id])}}">
        @csrf
        @include('admin.partials.on_edit.form_actions_top')
        <div class="row">
            <div class="col-xs-12">
                <h3>Редактиране на отстъпка тип: <strong>Бонус върху продукт</strong></h3><br>
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

                <div class="form-group">
                    <label for="product_id" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.for_product')</label>

                    <select id="product_id" class="should-be-required form-control @error('product_id') is-invalid @enderror" name="product_id" autofocus>
                        @foreach($products as $product)
                            <option value="{{$product->id}}" {{$discount->product_id==$product->id ? 'selected':''}}>{{$product->title}}</option>
                        @endforeach
                    </select>

                    @error('product_id')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.quantity')</label>

                    <input id="quantity" type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ $discount->getQuantity() }}" autocomplete="quantity" autofocus required>

                    @error('quantity')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="result_product_id" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.end_product')</label>

                    <select id="result_product_id" class="should-be-required form-control @error('result_product_id') is-invalid @enderror" name="result_product_id" autofocus>
                        @foreach($products as $product)
                            <option value="{{$product->id}}" {{$discount->getResultProductId()==$product->id ? 'selected':''}}>{{$product->title}}</option>
                        @endforeach
                    </select>

                    @error('result_product_id')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="value_type_id" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.amount_type')</label>

                    <select id="value_type_id" class="form-control @error('value_type_id') is-invalid @enderror" name="value_type_id" autofocus required>
                        @foreach($valueTypes as $valueType)
                            <option value="{{$valueType}}" {{$discount->getValueTypeId()==$valueType ? 'selected':''}}>{{__('value type ').$valueType}}</option>
                        @endforeach
                    </select>

                    @error('value_type_id')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="value" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.amount')</label>

                    <input id="value" type="number" min="0" step="0.01" class="form-control @error('value') is-invalid @enderror" name="value" value="{{ $discount->value }}" autocomplete="value" autofocus required>

                    @error('value')
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

                    <input id="max_uses" type="number" min="0" class="form-control @error('max_uses') is-invalid @enderror" name="max_uses" value="{{ $discount->max_uses }}" autocomplete="max_uses" autofocus>

                    @error('max_uses')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="max_uses_per_order" class="control-label p-b-10">@lang('shop::admin.discounts.max_uses_per_order')</label>

                    <input id="max_uses_per_order" type="number" min="1" class="form-control @error('max_uses_per_order') is-invalid @enderror" name="max_uses_per_order" value="{{ $discount->getMaxUsesPerOrder() }}" autocomplete="max_uses_per_order" autofocus>

                    @error('result_product_id')
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

            <div class="col-md-12">
                @include('admin.partials.on_edit.form_actions_bottom')
            </div>
        </div>
    </form>
@endsection
