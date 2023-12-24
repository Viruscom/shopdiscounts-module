@php use Modules\ShopDiscounts\Entities\Discount; @endphp@extends('layouts.admin.app')

@section('content')
    @include('shopdiscounts::admin.breadcrumbs')
    @include('admin.notify')
    @include('admin.partials.modals.delete_confirm')
    <div class="col-xs-12 p-0">
        <div class="bg-grey top-search-bar">
            <div class="checkbox-all pull-left p-10 p-l-0">
                <div class="pretty p-default p-square">
                    <input type="checkbox" id="selectAll" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="{{ __('admin.common.mark_demark_all_elements') }}" data-trigger="hover"/>
                    <div class="state p-primary">
                        <label></label>
                    </div>
                </div>
            </div>
            <div class="collapse-buttons pull-left p-7">
                <a class="btn btn-xs expand-btn"><i class="fas fa-angle-down fa-2x tooltips" data-toggle="tooltip" data-placement="right" data-original-title="{{ __('admin.common.expand_all_marked_elements') }}"></i></a>
                <a class="btn btn-xs collapse-btn hidden"><i class="fas fa-angle-up fa-2x tooltips" data-toggle="tooltip" data-placement="right" data-original-title="{{ __('admin.common.collapse_all_marked_elements') }}"></i></a>
            </div>
            <div class="search pull-left hidden-xs">
                <div class="input-group">
                    <input type="text" name="search" class="form-control input-sm search-text" placeholder="{{ __('admin.common.search') }}">
                    <span class="input-group-btn">
					<button class="btn btn-sm submit"><i class="fa fa-search"></i></button>
				</span>
                </div>
            </div>

            <div class="action-mass-buttons pull-right">
                <div class="dropdown">
                    <button class="btn btn-lg tooltips green dropdown-toggle" type="button" id="discountsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Създай нов">
                        <i class="fas fa-plus"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="discountsDropdown">
                        <li><a href="{{ route('discounts.create', ['type' => Discount::$FIXED_AMOUNT_TYPE_ID]) }}">@lang('shop::admin.discounts.type_fixed_amount')</a></li>
                        <li><a href="{{ route('discounts.create', ['type' => Discount::$FIXED_PERCENT_TYPE_ID]) }}">@lang('shop::admin.discounts.type_fixed_percent')</a></li>
                        <li><a href="{{ route('discounts.create', ['type' => Discount::$FIXED_FREE_DELIVERY_TYPE_ID]) }}">@lang('shop::admin.discounts.type_fixed_free_delivery')</a></li>
                        <li><a href="{{ route('discounts.create', ['type' => Discount::$QUANTITY_TYPE_ID]) }}">@lang('shop::admin.discounts.type_quantity')</a></li>
                        <li><a href="{{ route('discounts.create', ['type' => Discount::$BONUS_ON_ITEM_TYPE_ID]) }}">@lang('shop::admin.discounts.type_bonus_on_item')</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('shop::admin.discounts.name') }}</th>
                        <th>{{ __('shop::admin.discounts.type') }}</th>
                        <th>{{ __('shop::admin.discounts.created_at') }}</th>
                        <th class="text-right">{{ __('admin.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($discounts))
                        @foreach($discounts as $discount)
                            <tr>
                                <td>{{$discount->name}}</td>
                                <td>{{$discount->getHumanReadableType()}}</td>
                                <td>{{$discount->created_at}}</td>
                                <td class="text-right">
                                    <a href="{{route('discounts.edit',['id'=>$discount->id])}}" class="btn green tooltips" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.edit') }}"><i class="fas fa-pencil-alt"></i></a>
                                    @if(!$discount->active)
                                        <a href="{{ route('discounts.changeStatus', ['id'=> $discount->id, 'active'=>1]) }}" role="button" class="btn light-grey-eye visibility-activate tooltips" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.show') }}"><i class="far fa-eye-slash"></i></a>
                                    @else
                                        <a href="{{ route('discounts.changeStatus', ['id'=> $discount->id, 'active'=>0]) }}" role="button" class="btn grey-eye visibility-unactive tooltips" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.hide') }}"><i class="far fa-eye"></i></a>
                                    @endif
                                    <a href="{{ route('discounts.delete', ['id' => $discount->id]) }}" class="btn red btn-delete-confirm tooltips" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.delete') }}"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="4" class="no-table-rows">{{ __('shop::admin.discounts.no-discounts') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="no-table-rows">{{ __('shop::admin.discounts.no-discounts') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
