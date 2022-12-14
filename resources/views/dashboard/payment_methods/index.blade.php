@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.payment_methods')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.payment_methods')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px; color: #605ca8;">@lang('site.payment_methods')</h3>

                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('payment_method.get_payment_method') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.add')</a>
                        </div>

                    </div>

                </div><!-- end of box header -->

                <div class="table-responsive">

                    @if ($payment_methods->count() > 0)

                        <table class="table table_with_buttons_without_paging table-hover">

                            <thead class="bg-black">
                            <tr>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >#</th>
                                <th>@lang('site_payment_method.payment_method_name')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_payment_method.payment_method_type')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_payment_method.is_active')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                $activeBtn = __("site.activeBtn");
                                $deactivateBtn = __("site.deactivateBtn");
                            ?>
                            @foreach ($payment_methods as $index => $payment_method)
                                <tr>
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $index + 1 }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $payment_method->payment_method_name }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ __("site_payment_method.payment_method_type_$payment_method->payment_method_type") }}</td>
                                    <td id="payment_method_status_{{$payment_method->payment_method_id}}" style="text-align: center; font-size: 18px; font-weight: bold">
                                        <?php
                                            echo $payment_method->is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>
                                    <td>

                                        <form  class="formData_activation" style="display: inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="payment_method_id" value="{{$payment_method->payment_method_id}}">
                                            <?php
                                                echo $payment_method->is_active == 1 ?
                                                    "<button style='text-align: center; font-size: 18px;'  type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> $deactivateBtn</i></button>
                                                     <input type='hidden' id= 'hidden_btn_$payment_method->payment_method_id' name='active_status' value='false'>
                                                    "
                                                    :
                                                    "<button style='text-align: center; font-size: 18px;'  type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> $activeBtn</button>
                                                     <input type='hidden' id= 'hidden_btn_$payment_method->payment_method_id' name='active_status' value='true'>
                                                    ";
                                            ?>
                                        </form>

                                        <a style='text-align: center; font-size: 18px;'  href="{{ route('payment_method.get_payment_method', $payment_method->payment_method_id) }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>


                                        <form action="{{ route('payment_method.destroy', $payment_method->payment_method_id) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button style='text-align: center; font-size: 18px;'  type="submit" class="btn bg-purple delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
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
                                url: "{{route('payment_method.update_activation')}}",
                                data : formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {

                                    console.log(data);
                                    if (data['status'] == 'activate'){

                                        let page_status = 'payment_method_status_' +data['payment_method_id'];
                                        $('#'+page_status+'> i').remove();
                                        $('#'+page_status).append('<i class="fa fa-check" style="font-size:18px;color:green"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['payment_method_id']).remove();
                                        form.append("<button style='text-align: center; font-size: 18px; font-weight: bold'  type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> <?php echo $deactivateBtn?></button>");
                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['payment_method_id'] +"' name='active_status' value='false'>");

                                    }
                                    if (data['status'] == 'deactivate'){
                                        let page_status = 'payment_method_status_' +data['payment_method_id'];
                                        $('#'+page_status+'> i').remove();
                                        $('#'+page_status).append('<i class="fa fa-times" style="font-size:18px;color:red"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['payment_method_id']).remove();
                                        form.append("<button style='text-align: center; font-size: 18px; font-weight: bold'  type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> <?php echo $activeBtn?></button>");
                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['payment_method_id'] +"' name='active_status' value='true'>");
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

