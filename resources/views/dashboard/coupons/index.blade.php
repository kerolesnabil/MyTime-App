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

                    <h3 class="box-title" style="margin-bottom: 15px; font-size: 20px; color: red; font-weight: bold">@lang('site.coupons')</h3>

                    <form action="{{ route('coupon.index') }}" method="get">

                        <div class="row">
                            <div class="col-md-4">
                                <a style="font-size: 18px" href="{{ route('coupon.get_coupon') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if (!empty($coupons))

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr style="font-size: 18px">
                                <th>#</th>
                                <th>@lang('site_coupon.coupon_code')</th>
                                <th style="text-align: center">@lang('site_coupon.coupon_value')</th>
                                <th style="text-align: center">@lang('site_coupon.coupon_type')</th>
                                <th style="text-align: center">@lang('site_coupon.coupon_limited_num')</th>
                                <th style="text-align: center">@lang('site_coupon.coupon_used_times')</th>
                                <th style="text-align: center">@lang('site_coupon.coupon_start_at')</th>
                                <th style="text-align: center">@lang('site_coupon.coupon_end_at')</th>
                                <th style="text-align: center">@lang('site_coupon.is_active')</th>
                                <th style="text-align: center">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                $activeBtn = __("site.activeBtn");
                                $deactivateBtn = __("site.deactivateBtn");
                            ?>
                            @foreach ($coupons as $index => $coupon)
                                <tr style="font-size: 17px">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $coupon->coupon_code }}</td>
                                    <td>{{ intval($coupon->coupon_value) }}</td>
                                    <td>{{ __("site_coupon.coupon_type_$coupon->coupon_type") }}</td>
                                    <td>{{ $coupon->coupon_limited_num }}</td>
                                    <td>{{ $coupon->coupon_used_times }}</td>
                                    <td>{{ $coupon->coupon_start_at }}</td>
                                    <td>{{ $coupon->coupon_end_at }}</td>
                                    <td id="coupon_status_{{$coupon->coupon_id}}" style="text-align: center">
                                        <?php
                                            echo $coupon->is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>
                                    <td>

                                        <form  class="formData_activation" style="display: inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="coupon_id" value="{{$coupon->coupon_id}}">
                                            <?php
                                                echo $coupon->is_active == 1 ?
                                                    "<button style='font-size: 17px' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> $deactivateBtn</i></button>
                                                     <input type='hidden' id= 'hidden_btn_$coupon->coupon_id' name='active_status' value='false'>
                                                    "
                                                    :
                                                    "<button style='font-size: 17px' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> $activeBtn</button>
                                                     <input type='hidden' id= 'hidden_btn_$coupon->coupon_id' name='active_status' value='true'>
                                                    ";
                                            ?>
                                        </form>

                                        <a style='font-size: 17px' href="{{ route('coupon.get_coupon', $coupon->coupon_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>

                                        <form action="{{ route('coupon.destroy', $coupon->coupon_id) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button style='font-size: 17px' type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        </form>

                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table>

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


                            $.ajax({
                                type: 'post',
                                enctype: 'multipart/form-data',
                                url: "{{route('coupon.update_activation')}}",
                                data : formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {

                                    if (data['status'] == 'activate'){

                                        let coupon_status = 'coupon_status_' +data['coupon_id'];
                                        $('#'+coupon_status+'> i').remove();
                                        $('#'+coupon_status).append('<i class="fa fa-check" style="font-size:18px;color:green"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['coupon_id']).remove();
                                        form.append("<button style='font-size: 17px' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> <?php echo $deactivateBtn?></button>");
                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['coupon_id'] +"' name='active_status' value='false'>");

                                    }
                                    if (data['status'] == 'deactivate'){
                                        let coupon_status = 'coupon_status_' +data['coupon_id'];
                                        $('#'+coupon_status+'> i').remove();
                                        $('#'+coupon_status).append('<i class="fa fa-times" style="font-size:18px;color:red"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['coupon_id']).remove();
                                        form.append("<button style='font-size: 17px' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> <?php echo $activeBtn?></button>");

                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['coupon_id'] +"' name='active_status' value='true'>");
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

