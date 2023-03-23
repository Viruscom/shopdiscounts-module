<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('shopdiscounts.index') }}" class="text-black">@lang('shopdiscounts::admin.shopdiscounts.index')</a>
        </li>
        @if(url()->current() === route('shopdiscounts.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('shopdiscounts.create') }}" class="text-purple">@lang('shopdiscounts::admin.shopdiscounts.create')</a>
            </li>
        @elseif(url()->current() === route('admin.banners.edit', ['id' => Request::segment(3)]))
           <li>
{{--                <i class="fa fa-angle-right"></i>
{{--                <a href="{{ route('ad-boxes.edit') }}" class="text-purple">@lang('shopdiscounts::admin.shopdiscounts.edit')</a>
{{--            </li>--}}
        @endif
    </ul>
</div>

<div class="breadcrumbs">
    <ul>
        @if(url()->current() === route('admin.shopdiscounts.index'))
            <li>
                <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{ route('admin.shopdiscounts.index') }}" class="text-black">@lang('admin.shopdiscounts.index')</a>
            </li>
        @elseif(url()->current() === route('admin.banners.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.shopdiscounts.create') }}" class="text-purple">@lang('admin.shopdiscounts.create')</a>
            </li>
        @elseif(url()->current() === route('admin.shopdiscounts.edit', ['id' => Request::segment(3)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.shopdiscounts.edit', ['id' => Request::segment(3)]) }}" class="text-purple">@lang('admin.shopdiscounts.edit')</a>
            </li>
        @endif
    </ul>
</div>

