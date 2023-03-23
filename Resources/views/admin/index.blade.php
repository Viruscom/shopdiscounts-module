@extends('layouts.admin.app')
@section('styles')
    <link href="{{ asset('admin/assets/css/select2.min.css') }}" rel="stylesheet"/>
@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bootstrap-confirmation.js') }}"></script>
    <script>
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            container: 'body',
        });
        $(".select2").select2({language: "bg"});

        $(document).ready(function () {
            $('[data-toggle="popover"]').popover({
                placement: 'auto',
                trigger: 'hover',
                html: true
            });
        });
    </script>
@endsection
@section('content')
    @include('shopdiscounts::admin.breadcrumbs')
    @include('admin.notify')
    <div class="col-xs-12 p-0">
        <div class="bg-grey top-search-bar">
            <div class="checkbox-all pull-left p-10 p-l-0">
                <div class="pretty p-default p-square">
                    <input type="checkbox" id="selectAll" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Маркира/Демаркира всички елементи" data-trigger="hover"/>
                    <div class="state p-primary">
                        <label></label>
                    </div>
                </div>
            </div>
            <div class="collapse-buttons pull-left p-7">
                <a class="btn btn-xs expand-btn"><i class="fas fa-angle-down fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Разпъва всички маркирани елементи"></i></a>
                <a class="btn btn-xs collapse-btn hidden"><i class="fas fa-angle-up fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Прибира всички маркирани елементи"></i></a>
            </div>
            <div class="search pull-left hidden-xs">
                <div class="input-group">
                    <input type="text" name="search" class="form-control input-sm search-text" placeholder="Търси">
                    <span class="input-group-btn">
							<button class="btn btn-sm submit"><i class="fa fa-search"></i></button>
						</span>
                </div>
            </div>

            <div class="action-mass-buttons pull-right">
                @can(\$MODULE_NAMESPACE$\$MODULE$\$NAME$::PREVIEW_AND_EDIT_PERMISSION)
                    <a href="{{ route('shopdiscounts.create') }}" role="button" class="btn btn-lg tooltips green" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="@lang('admin.common.create_new')">
                                    <i class="fas fa-plus"></i>
                                </a>

                <a href="{{ url('/admin/adboxes/active/multiple/0/') }}" class="btn btn-lg tooltips light-grey-eye mass-unvisible" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Маркирай всички селектирани като НЕ активни/видими">
                    <i class="far fa-eye-slash"></i>
                </a>
                <a href="{{ url('/admin/adboxes/active/multiple/1/') }}" class="btn btn-lg tooltips grey-eye mass-visible" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Маркирай всички селектирани като активни/видими">
                    <i class="far fa-eye"></i>
                </a>
                <a href="#" class="btn btn-lg tooltips red mass-delete">
                    <i class="fas fa-trash-alt"></i>
                </a>
                <div class="hidden" id="mass-delete-url">{{ route('shopdiscounts.delete-multiple') }}</div>
                @endcan
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-xs-12">
        <h3>@lang('shopdiscounts::admin.'shopdiscounts.index'): @lang('adboxes::'shopdiscounts.ad_boxes_type_1')</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <th class="width-2-percent"></th>
                <th class="width-2-percent">@lang('shopdiscounts::admin.number')</th>
                <th class="width-130">@lang('shopdiscounts::admin.type')</th>
                <th>@lang('shopdiscounts::admin.title')</th>
                <th class="width-220 text-right">@lang('shopdiscounts::admin.actions')</th>
                </thead>
                <tbody>
                <?php $i = 1;?>
                @forelse ($shopdiscountsAdmin as $$CLASS$)
                    <tr class="t-row row-{{$$CLASS$->id}}" data-toggle="popover" data-content='<img class="thumbnail img-responsive" src="{{ $$CLASS$->getFileUrl() }}"/>'>
                        <td class="width-2-percent">
                            <div class="pretty p-default p-square">
                                <input type="checkbox" class="checkbox-row" name="check[]" value="{{$$CLASS$->id}}"/>
                                <div class="state p-primary">
                                    <label></label>
                                </div>
                            </div>
                        </td>
                        <td class="width-2-percent">{{$i}}</td>
                        <td><label class="label btn-light-green">@lang('shopdiscounts::admin.ad_boxes_type_1')</label></td>
                        <td>{{ $$CLASS$->title }}</td>
                        <td class="pull-right">
                        @can(\$MODULE_NAMESPACE$\$MODULE$\$NAME$::PREVIEW_AND_EDIT_PERMISSION)
                            <a href="{{ route('ad-boxes.edit', ['id' => $$CLASS$->id]) }}" class="btn green" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="@lang('admin.edit')"><i class="fas fa-pencil-alt"></i></a>
                            @if(!$$CLASS$->active)
                                <a href="{{ route('ad-boxes.active', ['id' => $$CLASS$->id, 'active' => 1]) }}" role="button" class="btn light-grey-eye visibility-activate" data-placement="auto" title="" data-original-title="@lang('admin.common.activate')"><i class="far fa-eye-slash"></i></a>
                            @else
                                <a href="{{ route('ad-boxes.active', ['id' => $$CLASS$->id, 'active' => 0]) }}" role="button" class="btn grey-eye visibility-unactive" data-placement="auto" title="" data-original-title="@lang('admin.common.disactivate')"><i class="far fa-eye"></i></a>
                            @endif
                            @if($i !== 1)
                                <a href="{{ route('shopdiscounts.position-up', ['id' => $$CLASS$->id]) }}" role="button" class="move-up btn yellow" data-placement="auto" title="" data-original-title="@lang('admin.common.move_up')"><i class="fas fa-angle-up"></i></a>
                            @endif
                            @if($i != count($shopdiscountsAdmin))
                                <a href="{{ route('shopdiscounts.position-down', ['id' => $$CLASS$->id]) }}" role="button" class="move-down btn yellow" data-placement="auto" title="" data-original-title="@lang('admin.common.move_down')"><i class="fas fa-angle-down"></i></a>
                            @endif
                            <a href="{{ route('shopdiscounts.delete', ['id' => $$CLASS$->id]) }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
                        @endcan
                        </td>
                    </tr>
                    <tr class="t-row-details row-{{$$CLASS$->id}}-details hidden">
                        <td colspan="2"></td>
                        <td colspan="2">
                            <table class="table-details">
                                <tbody>
                                <tr>
                                    <td>
                                        <?php $l = 0;?>
                                        @foreach($languages as $language)
                                            <?php
                                            $adTrans = $$CLASS$->translate($language->code);
                                            if (is_null($adTrans)) {
                                                continue;
                                            }
                                            ?>
                                            @if($l <= 3)
                                                <p>
                                                    <span>Линк ({{$language->code}}): </span>
                                                    <span>
													<a href="{{ is_null($adTrans->url) ? "":(($adTrans->external_url) ? $adTrans->url : url($adTrans->url)) }}" class="text-purple" target="_blank">{{ is_null($adTrans->url) ? "":(($adTrans->external_url) ? $adTrans->url : url($adTrans->url)) }}</a>
												</span>
                                                </p>
                                            @endif
                                            <?php $l++; ?>
                                        @endforeach
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td class="width-220">
                            <img class="thumbnail img-responsive" src="{{ $$CLASS$->getFileUrl() }}"/>
                        </td>
                    </tr>
                    <?php $i++;?>
                @empty
                    <tr>
                        <td colspan="5" class="no-table-rows">@lang('shopdiscounts::admin.no_records_found')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
