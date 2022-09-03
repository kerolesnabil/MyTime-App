@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.orders')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.orders')</h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($orders->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr>
                                <th>#</th>
                                <th>@lang('site_order.user_name')</th>
                                <th>@lang('site_order.order_phone')</th>
                                <th style="text-align: center">@lang('site_order.vendor_name')</th>
                                <th style="text-align: center">@lang('site_order.order_type')</th>
                                <th style="text-align: center">@lang('site_order.order_status')</th>
                                <th style="text-align: center">@lang('site_order.payment_method')</th>
                                <th style="text-align: center">@lang('site_order.is_paid')</th>
                                <th style="text-align: center">@lang('site_order.order_total_price')</th>
                                <th style="text-align: center">@lang('site_order.order_taxes_cost')</th>
                                <th style="text-align: center">@lang('site_order.order_app_profit')</th>
                                <th style="text-align: center">@lang('site_order.order_date')</th>
                                <th style="text-align: center">@lang('site_order.order_time')</th>
                                <th style="text-align: center">@lang('site_order.order_created_at')</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                $activeBtn = __("site.activeBtn");
                                $deactivateBtn = __("site.deactivateBtn");
                            ?>
                            @foreach ($orders as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $order->user_name }}</td>
                                    <td>{{ $order->order_phone }}</td>
                                    <td>{{ $order->vendor_name}}</td>
                                    <td><?php echo  __("site_order.order_type_$order->order_type")?></td>
                                    <td><?php echo  __("site_order.order_status_$order->order_status")?></td>
                                    <td>{{ $order->payment_method}}</td>
                                    <td style="text-align: center">
                                        <?php
                                            echo $order->is_paid == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>
                                    <td>{{ $order->order_total_price}}</td>
                                    <td>{{ $order->order_taxes_cost}}</td>
                                    <td>{{ $order->order_app_profit}}</td>
                                    <td>{{ $order->order_date}}</td>
                                    <td>{{ $order->order_time}}</td>
                                    <td>{{ $order->order_created_at}}</td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table>

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->
            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection

