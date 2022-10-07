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

                    <h3 class="box-title"  style="margin-bottom: 15px; font-size: 20px; color: red; font-weight: bold">@lang('site.vendors')</h3>

                    <form id='filter_form'>
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
                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site_order.order_status') :</label>
                                        <select style="font-size: 17px" class="form-select form-control" name="order_status" data-placeholder="Select a State">
                                            <option value="no_status">@lang('site_order.no_order_status')</option>
                                            <option value="pending">@lang('site_order.order_status_pending')</option>
                                            <option value="accepted">@lang('site_order.order_status_accepted')</option>
                                            <option value="done">@lang('site_order.order_status_done')</option>
                                            <option value="reschedule">@lang('site_order.order_status_reschedule')</option>
                                            <option value="canceled">@lang('site_order.order_status_canceled')</option>
                                            <option value="rejected">@lang('site_order.order_status_rejected')</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" style="margin-top: 26px">
                                        <button style="font-size: 16px;" type="submit" class="report_btn btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div id="filtered-data-holder"></div>

                <div class="box-body">

                    @if ($vendors->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr style="font-size: 17px">
                                <th>#</th>
                                <th>@lang('site_vendor.vendor_name')</th>
                                <th>@lang('site_vendor.vendor_type')</th>
                                <th>@lang('site_vendor.vendor_phone')</th>
                                <th>@lang('site_vendor.vendor_email')</th>
                                <th>@lang('site_vendor.vendor_address')</th>
                                <th>@lang('site_vendor.wallet')</th>
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
                                <tr style="font-size: 17px">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $vendor->user_name }}</td>
                                    <td>{{ $vendor->vendor_type}}</td>
                                    <td>{{ $vendor->user_phone }}</td>
                                    <td>{{ $vendor->user_email }}</td>
                                    <td>{{ $vendor->user_address }}</td>
                                    <?php

                                    if ( $vendor->user_wallet < 0){
                                        $class = "bg-danger";
                                    }
                                    else{
                                        $class = "";
                                    }
                                    ?>

                                    <td class="{{$class}}">
                                        {{ $vendor->user_wallet }}

                                    </td>
                                    <td id="user_status_{{$vendor->user_id}}" style="text-align: center">
                                        <?php
                                            echo $vendor->user_is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?></td>
                                    <td>

                                        <a style='font-size: 17px' href="{{ route('vendor.show_vendor', $vendor->user_id) }}" class="btn btn-primary btn-sm"><i class="fa  fa-eye"></i> @lang('site.show')</a>

                                        <form  class="formData_activation" style="display: inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="user_id" value="{{$vendor->user_id}}">
                                            <?php
                                                echo $vendor->user_is_active == 1 ?
                                                    "<button style='font-size: 17px' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> $deactivateBtn</i></button>
                                                     <input type='hidden' id= 'hidden_btn_$vendor->user_id' name='active_status' value='false'>
                                                    "
                                                    :
                                                    "<button style='font-size: 17px' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> $activeBtn</button>
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

                <script>
                    $(document).ready(function () {
                        $('form').on('click','.report_btn', function (e) {

                            e.preventDefault();
                            let formData = new FormData($('#filter_form')[0]);
                            $.ajax({
                                type: 'post',
                                enctype: 'multipart/form-data',
                                url: "{{route('vendor.report_vendors')}}",
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

