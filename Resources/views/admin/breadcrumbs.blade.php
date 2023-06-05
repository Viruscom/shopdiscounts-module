<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('discounts.index') }}" class="text-black">@lang('shop::admin.discounts.index')</a>
        </li>
{{--        @if(url()->current() === route('discounts.create', ['type' => Request::segment(4)]))--}}
{{--            <li>--}}
{{--                <i class="fa fa-angle-right"></i>--}}
{{--                <a href="{{ route('discounts.create', ['type' => Request::segment(4)]) }}" class="text-purple">@lang('shop::admin.discounts.create')</a>--}}
{{--            </li>--}}
{{--        @elseif(url()->current() === route('admin.banners.edit', ['id' => Request::segment(3)]))--}}
{{--           <li>--}}
{{--                <i class="fa fa-angle-right"></i>--}}
{{--                <a href="{{ route('ad-boxes.edit') }}" class="text-purple">@lang('shopdiscounts::admin.shopdiscounts.edit')</a>--}}
{{--            </li>--}}
{{--        @endif--}}
    </ul>
</div>
