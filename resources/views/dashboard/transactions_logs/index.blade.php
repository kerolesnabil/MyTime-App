@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site_financial_transactions.transactions_log')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site_financial_transactions.transactions_log')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px; color: #605ca8;">@lang('site_financial_transactions.transactions_log')</h3>
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
                                        <label style="font-size: 16px; color: #000000">@lang('site_financial_transactions.transaction_type') :</label>

                                        <select class="form-control" name="transaction_operation">
                                            <option value="all">@lang('site_financial_transactions.request_all')</option>
                                            <option value="increase">@lang('site_financial_transactions.increase')</option>
                                            <option value="decrease">@lang('site_financial_transactions.decrease')</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" style="margin-top: 26px">
                                        <button style="font-size: 16px;" type="submit" class="report_btn btn btn-success"><i class="fa fa-search"></i> @lang('site.search')</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form><!-- end of form -->
                </div>
                <div class="box-body">
                    @if ($logs->count() > 0)

                        <table class="table display table-responsive table_with_buttons_without_paging table-hover">

                            <thead class="bg-black">
                            <tr>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >#</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_user.user_name')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_user.user_type')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.transaction_type')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.amount')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.transaction_notes')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_financial_transactions.created_at')</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach ($logs as $index => $log)


                                <?php
                                    if($log->transaction_operation == 'increase'){
                                        $style = "background-color: #99f8af;";
                                    }
                                    else{
                                        $style = "background-color: #ff8894;";
                                    }
                                ?>

                                <tr style="{{$style}}">
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $index + 1 }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $log->user_name }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">@lang("site.user_type_$log->user_type") </td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">@lang("site_financial_transactions.$log->transaction_operation") </td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $log->amount }}</td>
                                    <td style="font-size: 18px; font-weight: bold">{{ $log->transaction_notes }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $log->log_created_at }}</td>
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

