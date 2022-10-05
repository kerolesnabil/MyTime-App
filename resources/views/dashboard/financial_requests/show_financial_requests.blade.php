@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <?php

               if ( $request_type == 'deposit') {
                   $title = 'site_financial_transactions.deposit_requests';
                   $showRoute = '';
               }
               else{
                   $title = 'site_financial_transactions.withdrawal_requests';
                   $showRoute = '';
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

                    <form id='filter_form'>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site.date_from') :</label>
                                        <input type="date" name="date_from" class="form-control" value="{{ request()->date_from }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site.date_to') :</label>
                                        <input type="date" name="date_to" class="form-control" value="{{ request()->date_to }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label style="font-size: 16px; color: #000000">@lang('site_financial_transactions.status') :</label>

                                        <select class="form-control" name="status">
                                            <option value="all">@lang('site_financial_transactions.request_all')</option>
                                            <option value="null">@lang('site_financial_transactions.request_waiting')</option>
                                            <option value="0">@lang('site_financial_transactions.request_not_approved')</option>
                                            <option value="1">@lang('site_financial_transactions.request_approved')</option>
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

                <div class="box-body">

                    @if ($requests->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >#</th>
                                <th>@lang('site_user.user_name')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.amount')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.status')</th>
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
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $request->created_at }}</td>

                                    <td style="text-align: center">

                                        <a style='font-size: 17px' href="{{ route('financial_request.update_financial_request', $request->f_t_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> @lang('site.show')</a>
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

    </div><!-- end of content wrapper -->


@endsection



