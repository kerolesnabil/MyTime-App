@extends('layouts.dashboard.app')

@section('content')


    <div class="content-wrapper">
        <section class="content-header">

        </section>

        <section class="content">

            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{$daily_orders}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.daily_orders')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('order.show_new_orders', 'daily') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{$weekly_orders}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.weekly_orders')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('order.show_new_orders', 'weekly') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{$monthly_orders}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.monthly_orders')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('order.show_new_orders', 'monthly') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{$yearly_orders}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.yearly_orders')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('order.show_new_orders', 'yearly') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{$all_orders}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.all_orders')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('order.show_new_orders', 'all') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{$daily_users}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.daily_users')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('user.show_new_users', 'daily') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{$weekly_users}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.weekly_users')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('user.show_new_users', 'weekly') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{$monthly_users}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.monthly_users')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('user.show_new_users', 'monthly') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{$yearly_users}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.yearly_users')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('user.show_new_users', 'yearly') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{$all_users}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.all_users')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('user.show_new_users', 'all') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{$daily_vendors}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.daily_vendors')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-scissors"></i>
                        </div>
                        <a href="{{ route('vendor.show_new_vendors', 'daily') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{$weekly_vendors}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.weekly_vendors')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-scissors"></i>
                        </div>
                        <a href="{{ route('vendor.show_new_vendors', 'weekly') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{$monthly_vendors}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.monthly_vendors')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-scissors"></i>
                        </div>
                        <a href="{{ route('vendor.show_new_vendors', 'monthly') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{$yearly_vendors}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.yearly_vendors')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-scissors"></i>
                        </div>
                        <a href="{{ route('vendor.show_new_vendors', 'yearly') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{$all_vendors}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.all_vendors')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-scissors"></i>
                        </div>
                        <a href="{{ route('vendor.show_new_vendors', 'all') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-3 col-xs-6" style="width: 20%">
                    <!-- small box -->
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3>{{$vendors_wallets_value}}</h3>
                            <p style="font-size: 15px; font-weight: bold">@lang('site.vendors_wallet_value')</p>
                        </div>

                        <div class="icon">
                            <i class="ion ion-cash"></i>
                        </div>

                        <a href="{{ route('vendor.show_new_vendors', 'all') }}" class="small-box-footer">@lang('site.more_info') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>


            </div>

            <div class="row margin-bottom">

                <div class="col-md-12">

                    <div class="box" style="border-top-color: #4c4984">
                        <div class="box-body">
                            <form class="form-group" action="{{ route('admin.homepage') }}" method="GET" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="col-md-4">
                                    <label style="font-size: 16px; color: #000000">@lang('site.order_count_daily_form_month') :</label>
                                    <input
                                        type="month"
                                        name="order_count_daily_form_month"
                                        class="form-control"
                                        value="<?php echo request()->order_count_daily_form_month == "" ? now()->format('Y-m') : request()->order_count_daily_form_month ?>"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label style="font-size: 16px; color: #000000">@lang('site.order_count_monthly_form_year') :</label>
                                    <input
                                        type="text"
                                        name="order_count_monthly_form_year"
                                        class="form-control"
                                        value="<?php echo request()->order_count_monthly_form_year == ""? now()->format('Y') : request()->order_count_monthly_form_year ?>"

                                    >
                                </div>

                                <div class="col-md-3" style="margin-top: 26px">
                                    <button style="font-size: 16px;" type="submit" class="btn btn-success"><i class="fa fa-search"></i> @lang('site.search')</button>
                                </div>
                            </form><!-- end of form -->
                        </div>

                        <div class="box-footer">

                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">عدد الطلبات اليومية</div>
                <div class="panel-body">
                    <div id="daily_orders_count_chart" data-value="{{json_encode($chart_orders_daily)}}" style="width: 100%"></div>
                </div>
            </div>


            <div class="panel panel-info">
                <div class="panel-heading">عدد الطلبات الشهرية</div>
                <div class="panel-body">
                    <div id="monthly_orders_count_chart" data-value="{{json_encode($chart_orders_monthly)}}"></div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">عدد الطلبات السنوية</div>
                <div class="panel-body">
                    <div id="yearly_orders_count_chart" data-value="{{json_encode($chart_orders_yearly)}}"></div>
                </div>
            </div>


        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
