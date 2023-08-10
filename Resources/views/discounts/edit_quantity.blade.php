@extends('layouts.admin.app')
@section('content')
    @include('shopdiscounts::admin.breadcrumbs')
    @include('admin.notify')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">EDIT QUANTITY
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
                                        <option value="" {{$discount->client_group_id=='' ? 'selected':''}}>{{__('Choose')}}</option>
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
                                <div class="col-md-8" style="margin: 0 auto">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <td>{{__('From')}}</td>
                                            <td>{{__('To')}}</td>
                                            <td>{{__('Price')}}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $prices = json_decode($discount->getPrices(), true);
                                            $pricesString = trim($discount->getPrices(), '"');
$pricesObject = json_decode($pricesString);

if ($pricesObject) {
    $fromQuantity = $pricesObject->from_quantity;
    $toQuantity = $pricesObject->to_quantity;
    $price = $pricesObject->price;

    // Do something with $fromQuantity, $toQuantity, and $price
    echo "From Quantity: $fromQuantity, To Quantity: $toQuantity, Price: $price<br>";
} else {
    echo "The decoded value is not an object.";
}
dd(123);
                                        @endphp
                                        @for($i=0;$i<count($prices);$i++)
                                            <tr>
                                                <td><input name="prices[{{$i}}][from_quantity]" class="form-control @error('prices.'.$i.'.from_quantity') is-invalid @enderror" type="number" step="1" min=0 value="{{isset($prices[$i]) ? $prices[$i]['from_quantity']:''}}">
                                                    @error('prices.'.$i.'.from_quantity')
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </td>
                                                <td><input name="prices[{{$i}}][to_quantity]" class="form-control @error('prices.'.$i.'.to_quantity') is-invalid @enderror" type="number" step="1" min=0 value="{{isset($prices[$i]) ? $prices[$i]['to_quantity']:''}}">
                                                    @error('prices.'.$i.'.to_quantity')
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </td>
                                                <td><input name="prices[{{$i}}][price]" class="form-control @error('prices.'.$i.'.price') is-invalid @enderror" type="number" step="0.01" min=0 value="{{isset($prices[$i]) ? $prices[$i]['price']:''}}">
                                                    @error('prices.'.$i.'.price')
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endfor
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12">
                                    @error('prices')
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

        function deletePrice(el) {
            $(el).parent().parent().remove();
        }
    </script>
@endsection
