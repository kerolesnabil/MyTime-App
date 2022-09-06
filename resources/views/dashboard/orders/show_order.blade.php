@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('order.index') }}"> @lang('site.orders')</a></li>
                <li class="active">@lang('site.show')</li>
            </ol>
        </section>

        <section class="content">


            <div class="row">
                <div class="col-md-1">
                </div>


                <div class="col-md-10">
                    <div class="box box-primary">

                        <div class="box-header">
                            <h3 class="box-title">@lang('site_order.order_number')  <span style="color: #ff0005">  #{{$order->order_id}}</span></h3>
                        </div><!-- end of box header -->

                        <hr style="border-top: 1px solid #3c8dbc;">
                        <div class="box-body">

                            @include('partials._errors')


                            <form class="form-group">


                                <label class="margin-bottom" style="font-size: 22px; font-weight: bold; color: #ff0005;">@lang('site_order.order_basic_data')</label>
                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.user_name')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{$order->user_name}}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.vendor_name')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{$order->vendor_name}}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_phone')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{$order->order_phone}}</label>
                                    </div>

                                </div>
                                <div class="row mb-3 margin-bottom">

                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_type')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ __("site_order.order_type_$order->order_type") }}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_status')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ __("site_order.order_status_$order->order_status") }}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.is_rated')</label>
                                        <br>

                                        @if($order->is_rated == 0)
                                            <label style="color: #ff0005; font-size: 15px">{{ __("site_order.is_rated_$order->is_paid") }}</label>
                                        @else
                                            <label style="color: #30d24f; font-size: 16px">{{ __("site_order.is_rated_$order->is_paid") }}</label>

                                        @endif

                                    </div>
                                </div>
                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_date')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->order_date }}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_time')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->order_time }}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_created_at')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->order_created_at  }}</label>
                                    </div>

                                </div>
                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_total_items_price_before_discount')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->order_total_items_price_before_discount }}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_total_discount')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->order_total_discount }}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_total_items_price_after_discount')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->order_total_items_price_after_discount  }}</label>
                                    </div>

                                </div>
                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_taxes_rate')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ intval($order->order_taxes_rate) }} %</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_taxes_cost')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->order_taxes_cost }}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_total_price')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->order_total_price  }}</label>
                                    </div>

                                </div>
                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.payment_method')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->payment_method }} </label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.is_paid')</label>
                                        <br>

                                        @if($order->is_paid == 0)
                                            <label style="color: #ff0005; font-size: 16px">{{ __("site_order.is_paid_$order->is_paid") }}</label>
                                        @else
                                            <label style="color: #30d24f; font-size: 16px">{{ __("site_order.is_paid_$order->is_paid") }}</label>

                                        @endif

                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_order.order_app_profit')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $order->order_app_profit  }}</label>
                                    </div>

                                </div>

                                <hr style="border-top: 1px solid #D81B60; width: 97%">

                                <label class="margin-bottom" style="font-size: 22px; font-weight: bold; color: #ff0005;">@lang('site_order.order_items')</label>


                                @foreach($items as $key => $item)
                                    <div  style="background-color: #f1f4fc;padding: 4px; margin-bottom: 15px">
                                        <div class="row mb-3 margin-bottom">
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_order.service_name')</label>
                                                <br>
                                                <label style="color: #F39C12; font-size: 16px">{{$item['service_name']}}</label>
                                            </div>
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_order.item_price_before_discount')</label>
                                                <br>
                                                <label style="color: #F39C12; font-size: 16px">{{$item['item_price_before_discount']}}</label>
                                            </div>
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_order.item_price_after_discount')</label>
                                                <br>
                                                <label style="color: #F39C12; font-size: 16px">{{$item['item_price_after_discount']}}</label>
                                            </div>

                                        </div>
                                        <div class="row mb-3 ">
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_order.item_count')</label>
                                                <br>
                                                <label style="color: #f39c12; font-size: 16px; padding: 5px">{{$item['item_count']}}</label>
                                            </div>
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_order.item_total_price_before_discount')</label>
                                                <br>
                                                <label style="color: #f39c12; font-size: 16px">{{$item['item_total_price_before_discount']}}</label>
                                            </div>
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_order.item_total_price_after_discount')</label>
                                                <br>
                                                <label style="color: #f39c12; font-size: 16px">{{$item['item_total_price_after_discount']}}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <hr style="border-top: 1px solid #D81B60; width: 97%">


                                <label class="margin-bottom" style="font-size: 22px; font-weight: bold; color: #ff0005;">@lang('site_order.order_coupon')</label>
                                @if($coupon != null)
                                    <div style="background-color: #f1fcf4;padding: 4px; margin-bottom: 15px">
                                        <div class="row mb-3">
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_coupon.coupon_id')</label>
                                                <br>
                                                <label style="color: #F39C12; font-size: 16px">{{$coupon->coupon_id }}</label>
                                            </div>
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_coupon.coupon_type')</label>
                                                <br>
                                                <label style="color: #F39C12; font-size: 16px">{{ __("site_coupon.coupon_type_$coupon->coupon_type") }}</label>
                                            </div>
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_coupon.coupon_value')</label>
                                                <br>
                                                <label style="color: #F39C12; font-size: 16px">{{$coupon->coupon_value }}</label>
                                            </div>

                                        </div>
                                    </div>
                                @else
                                    <div class="row mb-3 margin-bottom">
                                        <div class="col-md-4 pr-md-1">
                                            <label style="font-size: 18px">@lang('site_coupon.no_order_coupon')</label>
                                        </div>
                                    </div>
                                @endif




                            </form><!-- end of form -->



                        </div><!-- end of box body -->

                    </div><!-- end of box -->
                </div>

            </div>


        </section><!-- end of content -->


    </div><!-- end of content wrapper -->
@endsection
