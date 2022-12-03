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
                    <h3 class="box-title" style="margin-bottom: 15px; color: #605ca8;">@lang('site.orders')</h3>

                    <form class="form-group" action="{{ route('order.index') }}" method="GET" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mb-3">

                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site_order.user_name')</label>
                                        <select name="user_id" class="form-control selectpicker" data-show-subtext="false" data-live-search="true">
                                            <option value="all">@lang('site.all')</option>
                                            <?php if (count($users) > 0): ?>
                                                @foreach($users as $user)
                                                    <option value="{{$user->user_id}}">{{$user->user_name}}</option>
                                                @endforeach
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site_order.vendor_name')</label>
                                        <select name="vendor_id" class="form-control selectpicker" data-show-subtext="false" data-live-search="true">

                                            <option value="all">@lang('site.all')</option>
                                            <?php if (count($vendors) > 0): ?>
                                                @foreach($vendors as $vendor)
                                                    <option value="{{$vendor->user_id}}">{{$vendor->user_name}}</option>
                                                @endforeach
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site_order.order_type')</label>
                                        <select name="order_type" class="form-control selectpicker" data-show-subtext="false" data-live-search="true">
                                            <option value="all">@lang('site.all')</option>
                                            <option value="home">@lang('site_order.order_type_home')</option>
                                            <option value="salon">@lang('site_order.order_type_salon')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site_order.order_status') :</label>
                                        <select name="order_status" class="form-control selectpicker"  data-show-subtext="false" data-live-search="true">
                                            <option value="all">@lang('site.all')</option>
                                            <option value="pending">@lang('site_order.order_status_pending')</option>
                                            <option value="accepted">@lang('site_order.order_status_accepted')</option>
                                            <option value="done">@lang('site_order.order_status_done')</option>
                                            <option value="reschedule">@lang('site_order.order_status_reschedule')</option>
                                            <option value="canceled">@lang('site_order.order_status_canceled')</option>
                                            <option value="rejected">@lang('site_order.order_status_rejected')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site_order.order_id')</label>
                                        <input type="text" name="order_id" class="form-control" value="{{ request()->order_id }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site_order.order_phone')</label>
                                        <input type="text" name="order_phone" class="form-control" value="{{ request()->order_phone }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site.date_from') :</label>
                                        <input type="date" name="date_from" class="form-control" value="{{ request()->date_from }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site.date_to') :</label>
                                        <input type="date" name="date_to" class="form-control" value="{{ request()->date_to }}">
                                    </div>





                                    <div class="col-md-3" style="margin-top: 26px">
                                        <button style="font-size: 16px;" type="submit" class="btn btn-success"><i class="fa fa-search"></i> @lang('site.search')</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($orders->count() > 0)

                        <table class="table display table-responsive table_with_buttons_without_paging table-hover">

                            <thead class="bg-black">
                            <tr style="font-size: 17px">
                                <th>#</th>
                                <th>@lang('site_order.order_id')</th>
                                <th>@lang('site_order.user_name')</th>
                                <th>@lang('site_order.order_phone')</th>
                                <th style="text-align: center">@lang('site_order.vendor_name')</th>
                                <th style="text-align: center">@lang('site_order.order_type')</th>
                                <th style="text-align: center">@lang('site_order.order_status')</th>
                                <th style="text-align: center">@lang('site_order.payment_method')</th>
                                <th style="text-align: center">@lang('site_order.is_paid')</th>
                                <th style="text-align: center">@lang('site_order.order_total_price')</th>
                                <th style="text-align: center">@lang('site_order.order_app_profit')</th>
                                <th style="text-align: center">@lang('site_order.order_created_at')</th>
                                <th style="text-align: center">@lang('site.action')</th>

                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                $activeBtn = __("site.activeBtn");
                                $deactivateBtn = __("site.deactivateBtn");
                            ?>
                            @foreach ($orders as $index => $order)
                                <tr style="font-size: 17px">

                                    <td>{{ $index }}</td>
                                    <td>{{ $order->order_id  }}</td>

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
                                    <td>{{ $order->order_app_profit}}</td>
                                    <td>{{ $order->order_created_at}}</td>

                                    <td>
                                        <a style="font-size: 17px" href="{{ route('order.show_order', $order->order_id) }}" class="btn btn-success btn-sm"><i class="fa  fa-eye"></i> @lang('site.show')</a>
                                    </td>

                                </tr>

                            @endforeach
                            </tbody>

                        </table>
                        {!! $orders->links() !!}


                        <p>
                            {{$orders->count()}} {{__('site.of')}} {{$orders->total()}}
                        </p>
                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->




@endsection

