<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">

                <img src="{{ asset(auth()->user()->user_img) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->user_name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">



            <li style="font-size: 16px; font-weight: bold"><a href="{{route('admin.homepage')}}"><i class="fa fa-dashboard"></i><span>@lang('site.dashboard')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('admin.index')}}"><i class="fa fa-users" aria-hidden="true"></i><span>@lang('site.admins')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('user.index')}}"><i class="fa fa-users" aria-hidden="true"></i><span>@lang('site.users')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('vendor.index')}}"><i class="fa fa-users" aria-hidden="true"></i><span>@lang('site.vendors')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('category.index')}}"><i class="fa fa-list" aria-hidden="true"></i><span>@lang('site.categories')</span></a></li>

            <li style="font-size: 16px; font-weight: bold"><a href="{{route('service.index')}}"><i class="fa fa-cogs" aria-hidden="true"></i><span>@lang('site.services')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('service.show_suggested_services')}}"><i class="fa fa-list" aria-hidden="true"></i><span>@lang('site.suggested_services')</span></a></li>


            <li style="font-size: 16px; font-weight: bold"><a href="{{route('order.index')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>@lang('site.orders')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('order_rejection_reason.index')}}"><i class="fa fa-file-text" aria-hidden="true"></i><span>@lang('site.order_rejection_reason')</span></a></li>
            <li class="treeview" style="font-size: 16px; font-weight: bold">
                <a href="#">
                    <i class="fa fa-money"></i><span>@lang('site.financial_transactions')</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="font-size: 13px; font-weight: bold">
                    <li ><a href="{{ route('transaction_log.index') }}"><i class="fa fa-circle-o"></i>@lang('site_financial_transactions.transactions_log')</a></li>
                    <li ><a href="{{ route('financial_request.show_deposit_requests') }}"><i class="fa fa-circle-o"></i>@lang('site_financial_transactions.deposit_requests')</a></li>
                    <li ><a href="{{ route('financial_request.show_withdrawal_requests') }}"><i class="fa fa-circle-o"></i>@lang('site_financial_transactions.withdrawal_requests')</a></li>

                </ul>

            </li>



            <li style="font-size: 16px; font-weight: bold"><a href="{{route('ad.index')}}"><i class="fa fa-bullhorn" aria-hidden="true"></i><span>@lang('site.ads')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('coupon.index')}}"><i class="fa fa-tags" aria-hidden="true"></i><span>@lang('site.coupons')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('lang.index')}}"><i class="fa fa-language"></i><span>@lang('site.langs')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('page.index')}}"><i class="fa fa-file-text" aria-hidden="true"></i><span>@lang('site.pages')</span></a></li>
            <li style="font-size: 16px; font-weight: bold"><a href="{{route('payment_method.index')}}"><i class="fa fa-money" aria-hidden="true"></i><span>@lang('site.payment_methods')</span></a></li>

            <li class="treeview" style="font-size: 16px; font-weight: bold">
                <a href="#">
                    <i class="fa fa-cogs"></i><span>@lang('site.settings')</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="font-size: 13px; font-weight: bold">
                    <li ><a href="{{ route('setting.social_media') }}"><i class="fa fa-circle-o"></i><span>@lang('site_setting.social_media')</span></a></li>
                    <li ><a href="{{ route('setting.get_app_images') }}"><i class="fa fa-circle-o"></i>@lang('site_setting.app_images')</a></li>
                    <li ><a href="{{ route('setting.get_ad_price') }}"><i class="fa fa-circle-o"></i>@lang('site_setting.ads_prices')</a></li>
                    <li ><a href="{{ route('setting.get_diameter_search') }}"><i class="fa fa-circle-o"></i>@lang('site_setting.diameter_search')</a></li>
                    <li ><a href="{{ route('setting.get_bank_account_details') }}"><i class="fa fa-circle-o"></i>@lang('site_setting.app_bank_account')</a></li>
                </ul>
            </li>
        </ul>

    </section>

</aside>

