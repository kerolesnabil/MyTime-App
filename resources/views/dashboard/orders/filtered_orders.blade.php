<div class="box-body">

    <i class="fa fa-file-text-o" aria-hidden="true" style="font-size: 30px; display: inline"> <span style=" font-weight: bold;color: #0B90C4">@lang('site.report')</span></i>

    <hr>
    @if ($orders->count() > 0)

        <table class="table table-bordered table-hover" id="filtered-table">

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

    @else
        <h2>@lang('site.no_data_found')</h2>
    @endif

</div>
