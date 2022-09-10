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
                    <h3 class="box-title" style="margin-bottom: 15px; font-size: 20px; color: red; font-weight: bold">@lang('site.orders')</h3>

                    <form id='filter_form'>
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label style="font-size: 16px; color: #000000">@lang('site.date_from') :</label>
                                        <input type="date" name="date_from" class="form-control" value="{{ request()->date_from }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label style="font-size: 16px; color: #000000">@lang('site.date_to') :</label>
                                        <input type="date" name="date_to" class="form-control" value="{{ request()->date_to }}">
                                    </div>
                                    <div class="col-md-4" style="margin-top: 26px">
                                        <button style="font-size: 16px;" type="submit" class="report_btn btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div id="filtered-data-holder">

                </div>

                <div class="box-body">

                    @if ($orders->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr style="font-size: 17px">
                                <th>#</th>
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
                                    <td>{{ $order->order_app_profit}}</td>
                                    <td>{{ $order->order_created_at}}</td>

                                    <td>
                                        <a style="font-size: 17px" href="{{ route('order.show_order', $order->order_id) }}" class="btn btn-primary btn-sm"><i class="fa  fa-eye"></i> @lang('site.show')</a>
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

                <script>
                    $(document).ready(function () {
                        $('form').on('click','.report_btn', function (e) {

                            e.preventDefault();
                            let formData = new FormData($('#filter_form')[0]);
                            $.ajax({
                                type: 'post',
                                enctype: 'multipart/form-data',
                                url: "{{route('order.report_order')}}",
                                data : formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {
                                    if (data != false){
                                        $('.box-body').remove();
                                        $('#filtered-data-holder').append(data)
                                    }
                                },
                            })

                        });
                    });

                </script>



            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->




@endsection

