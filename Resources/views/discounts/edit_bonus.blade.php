@extends('shopdiscounts::layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">EDIT BONUS
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                   
                    <form method="POST" action="{{route('discounts.update',['id'=>$discount->id])}}">
                        @csrf

                          <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}*</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $discount->name }}" required autocomplete="name" autofocus>

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
                                        <option value="{{$clientGroup}}" {{$discount->client_group_id==$clientGroup ? 'selected':''}}>{{__('client group ').$clientGroup}}</option>
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
                                <input id="valid_from" type="date" class="form-control @error('valid_from') is-invalid @enderror" name="valid_from" value="{{ $discount->valid_from }}" autocomplete="valid_from" autofocus>

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
                                <input id="valid_until" type="date" class="form-control @error('valid_until') is-invalid @enderror" name="valid_until" value="{{ $discount->valid_until }}" autocomplete="valid_until" autofocus>

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
                                <input id="max_uses" type="number" min="0" class="form-control @error('max_uses') is-invalid @enderror" name="max_uses" value="{{ $discount->max_uses }}" autocomplete="max_uses" autofocus>

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
                                        <option value="0" {{$discount->active==0 ? 'selected':''}}>{{__('No')}}</option>
                                        <option value="1" {{$discount->active==1 ? 'selected':''}}>{{__('Yes')}}</option>
                                </select>

                                @error('active')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="product_id" class="col-md-4 col-form-label text-md-right">{{ __('Product') }}*</label>

                            <div class="col-md-6">
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

                        <div class="form-group row">
                            <label for="quantity" class="col-md-4 col-form-label text-md-right">{{ __('Quantity') }}*</label>

                            <div class="col-md-6">
                                <input id="quantity" type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ $discount->getQuantity() }}" autocomplete="quantity" autofocus required>

                                @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="result_product_id" class="col-md-4 col-form-label text-md-right">{{ __('End Product') }}*</label>

                            <div class="col-md-6">
                                <select id="result_product_id" class="should-be-required form-control @error('result_product_id') is-invalid @enderror" name="result_product_id" autofocus>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}" {{$discount->getResultProductId()==$product->id ? 'selected':''}}>{{__('Product ').$product->id}}</option>
                                    @endforeach
                                </select>

                                @error('result_product_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="value" class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}*</label>

                            <div class="col-md-6">
                                <input id="value" type="number" min="0" step="0.01" class="form-control @error('value') is-invalid @enderror" name="value" value="{{ $discount->value }}" autocomplete="value" autofocus required>

                                @error('value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="value_type_id" class="col-md-4 col-form-label text-md-right">{{ __('Amount type')}}*</label>

                            <div class="col-md-6">
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
                        </div>

                        <div class="form-group row">
                            <label for="max_uses_per_order" class="col-md-4 col-form-label text-md-right">{{ __('Max uses per order') }}</label>

                            <div class="col-md-6">
                                <input id="max_uses_per_order" type="number" min="1" class="form-control @error('max_uses_per_order') is-invalid @enderror" name="max_uses_per_order" value="{{ $discount->getMaxUsesPerOrder() }}" autocomplete="max_uses_per_order" autofocus>

                                @error('result_product_id')
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
@endsection
