<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('dashboard_files/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">

            <li style="font-size: 16px; font-weight: bold"><a href="{{route('admin.homepage')}}"><i class="fa fa-dashboard"></i><span>@lang('site.dashboard')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('user.index')}}"><i class="fa fa-users" aria-hidden="true"></i><span>@lang('site.users')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('vendor.index')}}"><i class="fa fa-users" aria-hidden="true"></i><span>@lang('site.vendors')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('category.index')}}"><i class="fa fa-list" aria-hidden="true"></i><span>@lang('site.categories')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('page.index')}}"><i class="fa fa-file-text" aria-hidden="true"></i><span>@lang('site.pages')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('order.index')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>@lang('site.orders')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('lang.index')}}"><i class="fa fa-language"></i><span>@lang('site.langs')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('order_rejection_reason.index')}}"><i class="fa fa-file-text" aria-hidden="true"></i><span>@lang('site.order_rejection_reason')</span></a></li>







        </ul>

    </section>

</aside>

