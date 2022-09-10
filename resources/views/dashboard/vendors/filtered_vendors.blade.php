<div class="box-body">
    <i class="fa fa-file-text-o" aria-hidden="true" style="font-size: 30px; display: inline"> <span style=" font-weight: bold;color: #0B90C4">@lang('site.report')</span></i>

    <hr>
    @if ($vendors->count() > 0)

        <table class="table table-bordered table-hover">

            <thead style="background-color: rgba(0,0,0,0.88); color: white">
            <tr>
                <th>#</th>
                <th>@lang('site_vendor.vendor_name')</th>
                <th>@lang('site_vendor.vendor_type')</th>
                <th>@lang('site_vendor.vendor_phone')</th>
                <th>@lang('site_vendor.vendor_email')</th>
                <th>@lang('site_vendor.vendor_address')</th>
                <th>@lang('site_vendor.vendor_orders_count')</th>
                <th style="text-align: center">@lang('site_vendor.vendor_is_active')</th>
                <th style="text-align: center">@lang('site.action')</th>
            </tr>
            </thead>

            <tbody>
            <?php
                $activeBtn = __("site.activeBtn");
                $deactivateBtn = __("site.deactivateBtn");
            ?>
            @foreach ($vendors as $index => $vendor)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $vendor->user_name }}</td>
                    <td>{{ $vendor->vendor_type}}</td>
                    <td>{{ $vendor->user_phone }}</td>
                    <td>{{ $vendor->user_email }}</td>
                    <td>{{ $vendor->user_address }}</td>
                    <td>{{ $vendor->orders_count }}</td>
                    <td id="user_status_{{$vendor->user_id}}" style="text-align: center">
                        <?php
                            echo $vendor->user_is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                        ?></td>
                    <td>

                        <a style="text-align: center" href="{{ route('vendor.show_vendor', $vendor->user_id) }}" class="btn btn-primary btn-sm"><i class="fa  fa-eye"></i> @lang('site.show')</a>

                    </td>
                </tr>

            @endforeach
            </tbody>

        </table><!-- end of table -->

    @else

        <h2>@lang('site.no_data_found')</h2>

    @endif

</div>
