@extends('layouts.admin.app')

@section('content')
    @include('shopdiscounts::admin.breadcrumbs')
    @include('admin.notify')

    <form method="POST" action="{{route('discounts.store',['type'=>$type])}}">
        @csrf
        @include('admin.partials.on_create.form_actions_top')
        <div class="row">
            <div class="col-xs-12">
                <h3>Отстъпка тип: <strong>Количествена</strong></h3><br>
            </div>

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
                    <label for="active" class="control-label p-b-10"><span class="text-purple">* </span>{{ __('Active') }}</label>

                    <select id="active" class="form-control @error('active') is-invalid @enderror" name="active" required>
                        <option value="0" {{old('active')==0 ? 'selected':''}}>{{__('No')}}</option>
                        <option value="1" {{old('active')==1 ? 'selected':''}}>{{__('Yes')}}</option>
                    </select>

                    @error('active')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="product_id" class="control-label  p-b-10">{{ __('Product') }}*</label>

                    <select id="product_id" class="should-be-required form-control @error('product_id') is-invalid @enderror" name="product_id" autofocus>
                        @foreach($products as $product)
                            <option value="{{$product->id}}" {{old('product_id')==$product->id ? 'selected':''}}>{{$product->title}}</option>
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
                <style>
                    #discounts-prices-table > tbody > tr > td {
                        vertical-align: top;
                    }
                </style>
                <table class="table table-striped" id="discounts-prices-table">
                    <thead>
                    <tr>
                        <td style="width: 25px;"></td>
                        <td>{{__('shop::admin.discounts.from_quantity')}}</td>
                        <td>{{__('shop::admin.discounts.to_quantity')}}</td>
                        <td>{{__('shop::admin.discounts.quantity_price')}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $prices = [];
                        $count = 1;
                        if(!is_null(old('prices')) && count(old('prices'))>0){
                            $count = count(old('prices'));
                            $prices = old('prices');
                        }
                    @endphp
                    @for($i=0;$i<$count;$i++)
                        <tr data-row-index="{{ $i }}">
                            <td>
                                @if($i>=1)
                                    <div class="delete-row btn btn-sm btn-danger" data-row-index="{{$i}}">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                @endif
                            </td>
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

                <script>
                    $(document).ready(function() {
                        var tableBody = $('#discounts-prices-table tbody');
                        var count = {{ $count }}; // Initialize count variable with PHP value

                        function updateRowIndexes() {
                            tableBody.find('tr').each(function(index) {
                                var deleteButton = $(this).find('.delete-row');
                                if (deleteButton.length > 0) {
                                    $(this).attr('data-row-index', index);
                                    deleteButton.attr('data-row-index', index);

                                    var fromQuantityInput = $(this).find('input[name^="prices"][name$="[from_quantity]"]');
                                    var toQuantityInput = $(this).find('input[name^="prices"][name$="[to_quantity]"]');
                                    var priceInput = $(this).find('input[name^="prices"][name$="[price]"]');

                                    fromQuantityInput.attr('name', 'prices[' + index + '][from_quantity]');
                                    toQuantityInput.attr('name', 'prices[' + index + '][to_quantity]');
                                    priceInput.attr('name', 'prices[' + index + '][price]');
                                }
                            });
                        }

                        function deleteRow(rowIndex) {
                            console.log(rowIndex);
                            var rowToDelete = tableBody.find('tr[data-row-index="' + rowIndex + '"]');
                            if (rowToDelete.length > 0) {
                                rowToDelete.remove();
                                updateRowIndexes();
                            }
                        }

                        tableBody.on('click', '.delete-row', function() {
                            var rowIndex = parseInt($(this).attr('data-row-index'));
                            deleteRow(rowIndex);
                        });

                        $('#add-row-btn').click(function() {
                            var newRow = $('<tr>');
                            newRow.html(`
      <td>
        <div class="delete-row btn btn-sm btn-danger" data-row-index="${count}">
          <i class="fas fa-trash"></i>
        </div>
      </td>
      <td>
        <input name="prices[${count}][from_quantity]" class="form-control" type="number" step="1" min="0">
      </td>
      <td>
        <input name="prices[${count}][to_quantity]" class="form-control" type="number" step="1" min="0">
      </td>
      <td>
        <input name="prices[${count}][price]" class="form-control" type="number" step="0.01" min="0">
      </td>
    `);
                            newRow.attr('data-row-index', count);
                            tableBody.append(newRow);
                            updateRowIndexes();
                            count++;
                        });
                    });

                </script>

            </div>
        </div>
        <div class="row text-right">
            <div class="col-md-12">
                <div id="add-row-btn" class="btn btn-primary">Добави нов ред</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 m-t-20">
                @include('admin.partials.on_create.form_actions_bottom')
            </div>
        </div>
    </form>
    <script>

        function deletePrice(el) {
            $(el).parent().parent().remove();
        }
    </script>
@endsection
