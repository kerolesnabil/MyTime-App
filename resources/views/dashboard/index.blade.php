@extends('layouts.dashboard.app')

@section('content')


    <div class="content-wrapper">
        <section class="content-header">

        </section>

        <section class="content">

            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
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
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
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
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
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
                <div class="col-lg-3 col-xs-6">
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

            </div>

            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
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
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
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
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
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
                <div class="col-lg-3 col-xs-6">
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
            </div>


        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
