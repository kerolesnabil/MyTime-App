@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.vendors')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.vendors')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title"  style="margin-bottom: 15px; color: #605ca8">@lang('site.vendors')</h3>

                    <form class="form-group" action="{{ route('vendor.index') }}" method="GET" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site.date_from') :</label>
                                        <input style="font-size: 16px" type="date" name="date_from" class="form-control" value="{{ request()->date_from }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site.date_to') :</label>
                                        <input style="font-size: 16px" type="date" name="date_to" class="form-control" value="{{ request()->date_to }}">
                                    </div>

                                    <div class="col-md-3" style="margin-top: 26px">
                                        <button style="font-size: 16px;" type="submit" class="btn btn-success"><i class="fa fa-search"></i> @lang('site.search')</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div id="filtered-data-holder"></div>

                <div class="box-body">

                    <?php
                        $activeBtn = __("site.activeBtn");
                        $deactivateBtn = __("site.deactivateBtn");
                    ?>

                    @if ($vendors->count() > 0)

                        <table class="table display table-responsive table_with_buttons_without_paging table-hover">

                            <thead class="bg-black">
                            <tr style="font-size: 15px">
                                <th>#</th>
                                <th>@lang('site_vendor.vendor_name')</th>
                                <th>@lang('site_vendor.vendor_type')</th>
                                <th>@lang('site_vendor.vendor_phone')</th>
                                <th>@lang('site_vendor.vendor_email')</th>
                                <th>@lang('site_vendor.vendor_address')</th>
                                <th>@lang('site_vendor.vendor_total_orders')</th>
                                <th>@lang('site_vendor.vendor_done_orders')</th>
                                <th>@lang('site_vendor.wallet')</th>
                                <th>@lang('site_vendor.vendor_wallet_status')</th>
                                <th style="text-align: center">@lang('site_vendor.vendor_is_active')</th>
                                <th style="text-align: center">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach ($vendors as $index => $vendor)
                                <tr style="font-size: 17px">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $vendor->user_name }}</td>


                                    <td>@lang("site_vendor.vendor_type_$vendor->vendor_type")</td>
                                    <td>{{ $vendor->user_phone }}</td>
                                    <td>{{ $vendor->user_email }}</td>
                                    <td>{{ $vendor->user_address }}</td>
                                    <td>{{ $vendor->total_orders }}</td>
                                    <td>{{ $vendor->done_orders }}</td>
                                    <?php

                                        if ( $vendor->user_wallet < 0){
                                            $class = "bg-danger";
                                            $vendorWalletStatus = 'vendor_wallet_status_not_has';
                                        }
                                        else{
                                            $class = "";
                                            $vendorWalletStatus = 'vendor_wallet_status_has';

                                        }
                                    ?>

                                    <td class="{{$class}}">
                                        {{ $vendor->user_wallet }}
                                    </td>
                                    <td>
                                        @lang("site_vendor.$vendorWalletStatus")
                                    </td>


                                    <td id="user_status_{{$vendor->user_id}}" style="text-align: center">
                                        <?php
                                            echo $vendor->user_is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?></td>
                                    <td>
                                        <a style='font-size: 17px; margin: 1px' href="{{ route('vendor.show_vendor', $vendor->user_id) }}" class="btn btn-success btn-sm"><i class="fa  fa-eye"></i> @lang('site.show')</a>
                                        <a style='font-size: 17px; margin: 1px' href="{{ route('vendor.save_vendor_services', $vendor->user_id) }}" class="btn bg-purple btn-sm"><i class="fa  fa-plus"></i> @lang('site_vendor.add_vendor_services')</a>
                                        <a style='font-size: 17px; margin: 1px' href="{{ route('transaction_log.index', 'user_id='.$vendor->user_id) }}" class="btn btn-success btn-sm"><i class="fa  fa-eye"></i> @lang('site_vendor.show_log')</a>
                                        <a style='font-size: 17px; margin: 1px' href="{{ route('financial_request.show_deposit_requests', 'user_id='.$vendor->user_id) }}" class="btn bg-purple btn-sm"><i class="fa  fa-eye"></i> @lang('site_vendor.show_deposit_requests')</a>
                                        <a style='font-size: 17px; margin: 1px' href="{{ route('financial_request.show_withdrawal_requests', 'user_id='.$vendor->user_id) }}" class="btn btn-success btn-sm"><i class="fa  fa-eye"></i> @lang('site_vendor.show_withdraw_requests')</a>


                                        <form  class="formData_activation" style="display: inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="user_id" value="{{$vendor->user_id}}">
                                            <?php
                                                echo $vendor->user_is_active == 1 ?
                                                    "<button style='font-size: 17px; margin: 1px' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> $deactivateBtn</i></button>
                                                     <input type='hidden' id= 'hidden_btn_$vendor->user_id' name='active_status' value='false'>
                                                    "
                                                    :
                                                    "<button style='font-size: 17px; margin: 1px' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> $activeBtn</button>
                                                     <input type='hidden' id= 'hidden_btn_$vendor->user_id' name='active_status' value='true'>
                                                    ";
                                            ?>
                                        </form>




                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table>
                    {!! $vendors->links() !!}

                        {{ $vendors->appends(request()->query())->links() }}

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->

                <script>
                    $(document).ready(function () {
                        $('body').on('click','.activation_btn', function (e) {

                            e.preventDefault();
                            let formData = new FormData($(this).parent()[0]);
                            let form = $(this).parent();

                            console.log(form.find('#hidden_btn_'+7));
                            $.ajax({
                                type: 'post',
                                enctype: 'multipart/form-data',
                                url: "{{route('user.update_activation')}}",
                                data : formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {

                                    if (data['status'] == 'activate'){

                                        let user_status = 'user_status_' +data['user_id'];
                                        $('#'+user_status+'> i').remove();
                                        $('#'+user_status).append('<i class="fa fa-check" style="font-size:18px;color:green"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['user_id']).remove();
                                        form.append("<button style='font-size: 17px' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> <?php echo $deactivateBtn?></button>");
                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['user_id'] +"' name='active_status' value='false'>");

                                    }
                                    if (data['status'] == 'deactivate'){
                                        let user_status = 'user_status_' +data['user_id'];
                                        $('#'+user_status+'> i').remove();
                                        $('#'+user_status).append('<i class="fa fa-times" style="font-size:18px;color:red"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['user_id']).remove();
                                        form.append("<button style='font-size: 17px' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> <?php echo $activeBtn?></button>");

                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['user_id'] +"' name='active_status' value='true'>");
                                    }
                                },
                                error: function (data) {
                                    console.log(data);
                                }
                            })

                        });
                    });

                </script>


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection

