@extends('layouts.admin.app')
@section('content')
    @include('shopdiscounts::admin.breadcrumbs')
    @include('admin.notify')
    <form method="POST" action="{{route('discounts.update',['id'=>$discount->id])}}">
        <div class="row">
            <div class="col-xs-12">
                @csrf
                @include('admin.partials.on_edit.form_actions_top')
            </div>
            <div class="col-md-6 col-xs-12">
                @include('admin.partials.on_edit.form_fields.input_text_without_lang', ['fieldName' => 'name', 'label' => trans('shop::admin.discounts.name'), 'required' => true, 'model' => $discount])

                <div class="form-group">
                    <label for="valid_from" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.valid_from')</label>

                    <input id="valid_from" type="date" class="form-control @error('valid_from') is-invalid @enderror" name="valid_from" value="{{ $discount->valid_from }}" autocomplete="valid_from" autofocus>

                    @error('valid_from')
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

                <div class="form-group">
                    <label for="product_id" class="control-label p-b-10"><span class="text-purple">* </span>@lang('shop::admin.discounts.choose_product')</label>

                    <select id="product_id" class="should-be-required form-control @error('product_id') is-invalid @enderror" name="product_id" autofocus>
                        @foreach($products as $product)
                            <option value="{{$product->id}}" {{$discount->product_id==$product->id ? 'selected':''}}>{{__('Product ').$product->id}}</option>
                        @endforeach
                    </select>

                    @error('product_id')
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
            </div>
            <div class="col-md-12">
                <table class="table table-striped" id="discounts-prices-table">
                    <thead>
                    <tr>
                        <td>{{__('shop::admin.discounts.from_quantity')}}</td>
                        <td>{{__('shop::admin.discounts.to_quantity')}}</td>
                        <td>{{__('shop::admin.discounts.quantity_price')}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $prices = json_decode(json_decode($discount->getPrices(), true), true);
                    @endphp
                    @if (!empty($prices))
                        @foreach ($prices as $i => $price)
                            <tr>
                                <td>
                                    <input name="prices[{{$i}}][from_quantity]" class="form-control @error('prices.'.$i.'.from_quantity') is-invalid @enderror" type="number" step="1" min="0" value="{{ $price['from_quantity'] }}">
                                    @error('prices.'.$i.'.from_quantity')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                    @enderror
                                </td>
                                <td>
                                    <input name="prices[{{$i}}][to_quantity]" class="form-control @error('prices.'.$i.'.to_quantity') is-invalid @enderror" type="number" step="1" min="0" value="{{ $price['to_quantity'] }}">
                                    @error('prices.'.$i.'.to_quantity')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                    @enderror
                                </td>
                                <td>
                                    <input name="prices[{{$i}}][price]" class="form-control @error('prices.'.$i.'.price') is-invalid @enderror" type="number" step="0.01" min="0" value="{{ $price['price'] }}">
                                    @error('prices.'.$i.'.price')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                @include('admin.partials.on_edit.form_actions_bottom')
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        <div class="card-body">

                            <div class="form-group row">
                                <div class="col-md-8" style="margin: 0 auto">

                                </div>

                                <div class="col-md-12">
                                    @error('prices')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>

            function deletePrice(el) {
                $(el).parent().parent().remove();
            }
        </script>
    </form>
@endsection
