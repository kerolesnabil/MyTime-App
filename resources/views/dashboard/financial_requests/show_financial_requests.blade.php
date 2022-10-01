@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <?php

               if ( $request_type == 'deposit') {
                   $title = 'site_financial_transactions.deposit_requests';
               }
               else{
                   $title = 'site_financial_transactions.withdrawal_requests';
               }
            ?>
            <h1>@lang($title)</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang($title)</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang($title)</h3>

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($requests->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >#</th>
                                <th>@lang('site_user.user_name')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.amount')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.status')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.transaction_notes')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.created_at')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach ($requests as $index => $request)
                                <tr>
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $index + 1 }}</td>
                                    <td style="font-size: 18px; font-weight: bold">{{ $request->user_name }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $request->amount }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">
                                        <?php

                                            if (is_null($request->status)){
                                                echo __('site_financial_transactions.request_waiting');
                                            }
                                            elseif ($request->status == 0){
                                                echo __('site_financial_transactions.request_not_approved');
                                            }
                                            else{
                                                echo __('site_financial_transactions.request_approved');
                                            }

                                        ?>

                                    </td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $request->transaction_notes }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $request->created_at }}</td>

                                    <td style="text-align: center">

                                        @if (is_null($request->status))

                                            <form style="display: inline-block">
                                                {{csrf_field()}}
                                                <input type="hidden" name="status" value="1">
                                                <button style="font-size: 18px; font-weight: bold" type="submit" class="action_btn btn btn-block btn-success btn-sm"><i class="fa fa-check"> @lang('site_financial_transactions.agree')</i></button>
                                            </form>
                                            <form style="display: inline-block">
                                                {{csrf_field()}}
                                                <input type="hidden" name="status" value="0">
                                                <button style="font-size: 18px; font-weight: bold" type="submit" class="action_btn btn btn-block btn-danger btn-sm"><i class="fa fa-times"> @lang('site_financial_transactions.disagree')</i></button>
                                            </form>
                                        @endif
                                    </td>

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


        <script>
            $(document).ready(function () {
                $('body').on('click','.action_btn', function (e) {

                    e.preventDefault();
                    let formData = new FormData($(this).parent()[0]);
                    let form = $(this).parent();

                    $.ajax({
                        type: 'post',
                        enctype: 'multipart/form-data',
                        url: "{{route('financial_request.handle_action_financial_request')}}",
                        data : formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (data) {

                            console.log(data);
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    })

                });
            });



        </script>
    </div><!-- end of content wrapper -->


@endsection



