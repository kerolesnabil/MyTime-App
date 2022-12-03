@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.coupons')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.coupons')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px;  color: #605ca8; ">@lang('site_coupon.used_coupon_users')</h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @if (count($data) > 0)

                        <table class="table display table-responsive table_with_buttons_without_paging table-hover">

                            <thead class="bg-black">
                            <tr style="font-size: 18px">
                                <th>#</th>
                                <th>@lang('site_coupon.coupon_code')</th>
                                <th style="text-align: center">@lang('site_coupon.coupon_value')</th>
                                <th style="text-align: center">@lang('site_coupon.coupon_type')</th>
                                <th style="text-align: center">@lang('site_order.user_name')</th>
                                <th style="text-align: center">@lang('site_order.order_id')</th>
                                <th style="text-align: center">@lang('site_coupon.coupon_used_time')</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach (collect($data) as $index => $item)
                                <tr style="font-size: 17px">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->coupon_code }}</td>
                                    <td>{{ intval($item->coupon_value) }}</td>
                                    <td>{{ __("site_coupon.coupon_type_$item->coupon_type") }}</td>
                                    <td>{{ $item->user_name }}</td>
                                    <td>{{ $item->order_id }}</td>
                                    <td>{{ $item->created_at }}</td>
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

