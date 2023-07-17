@extends('layouts.admin.app')

@section('content')
    @include('shopdiscounts::admin.breadcrumbs')
    @include('admin.notify')
    <form method="POST" action="{{route('discounts.store',['type'=>$type])}}">
        <div class="col-xs-12 p-0">
            @csrf
            @include('admin.partials.on_create.form_actions_top')
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-12">
                @include('admin.partials.on_create.form_fields.input_text', ['fieldName' => 'name', 'label' => trans('shop::admin.discounts.name'), 'required' => true])

                <div class="form-group">
                    <label for="valid_from" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.valid_from')</label>

                    <input id="valid_from" type="date" class="form-control @error('valid_from') is-invalid @enderror" name="valid_from" value="{{ old('valid_from') }}" autocomplete="valid_from">
                    @error('valid_from')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="active" class="form-label">{{ __('Active') }}</label>

                    <div>
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

                <div class="form-group">
                    <label for="product_id" class="form-label">{{ __('Product') }}*</label>

                    <div>
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

                <div class="form-group">
                    <label for="quantity" class="form-label">{{ __('Quantity') }}*</label>

                    <div>
                        <input id="quantity" type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity') }}" autocomplete="quantity" autofocus required>

                        @error('quantity')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="result_product_id" class="form-label">{{ __('End Product') }}*</label>

                    <div>
                        <select id="result_product_id" class="should-be-required form-control @error('result_product_id') is-invalid @enderror" name="result_product_id" autofocus>
                            @foreach($products as $product)
                                <option value="{{$product->id}}" {{old('result_product_id')==$product->id ? 'selected':''}}>{{__('Product ').$product->id}}</option>
                            @endforeach
                        </select>

                        @error('result_product_id')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="value" class="form-label">{{ __('Amount') }}*</label>

                    <div>
                        <input id="value" type="number" min="0" step="0.01" class="form-control @error('value') is-invalid @enderror" name="value" value="{{ old('value') }}" autocomplete="value" autofocus required>

                        @error('value')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="value_type_id" class="form-label">{{ __('Amount type')}}*</label>

                    <div>
                        <select id="value_type_id" class="form-control @error('value_type_id') is-invalid @enderror" name="value_type_id" autofocus required>
                            @foreach($valueTypes as $valueType)
                                <option value="{{$valueType}}" {{old('value_type_id')==$valueType ? 'selected':''}}>{{__('value type ').$valueType}}</option>
                            @endforeach
                        </select>

                        @error('value_type_id')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="max_uses_per_order" class="form-label">{{ __('Max uses per order') }}</label>

                    <div>
                        <input id="max_uses_per_order" type="number" min="1" class="form-control @error('max_uses_per_order') is-invalid @enderror" name="max_uses_per_order" value="{{ old('max_uses_per_order') }}" autocomplete="max_uses_per_order" autofocus>

                        @error('result_product_id')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="client_group_id" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.client_group'):</label>
                    <select id="client_group_id" class="form-control select2" name="client_group_id" required>
                        <option value="" {{old('client_group_id')=='' ? 'selected':''}}>{{__('admin.common.please_select')}}</option>
                        @foreach($clientGroups as $clientGroup)
                            <option value="{{$clientGroup}}" {{old('client_group_id')==$clientGroup ? 'selected':''}}>{{__('shop::admin.discounts.client_group_'.$clientGroup)}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="valid_until" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.valid_to')</label>

                    <input id="valid_until" type="date" class="form-control @error('valid_until') is-invalid @enderror" name="valid_until" value="{{ old('valid_until') }}" autocomplete="valid_until">

                    @error('valid_until')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="max_uses" class="control-label p-b-10">@lang('shop::admin.discounts.max_uses')</label>

                    <input id="max_uses" type="number" min="0" class="form-control @error('max_uses') is-invalid @enderror" name="max_uses" value="{{ old('max_uses') }}" autocomplete="max_uses">

                    @error('max_uses')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

            </div>

            <div class="col-md-12">
                @include('admin.partials.on_create.form_actions_bottom')
            </div>
        </div>
    </form>
@endsection
