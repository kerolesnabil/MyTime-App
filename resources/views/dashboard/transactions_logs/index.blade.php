@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.transactions_logs')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.transactions_logs')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.transactions_logs')</h3>

                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('payment_method.get_payment_method') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                        </div>

                    </div>

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($logs->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >#</th>
                                <th>@lang('site_user.user_name')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_transaction_log.transaction_type')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_transaction_log.amount')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_transaction_log.status')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_transaction_log.transaction_notes')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_transaction_log.created_at')</th>

                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach ($logs as $index => $log)
                                <tr>
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $index + 1 }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $log->user_name }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $log->transaction_type }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $log->amount }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $log->status }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $log->transaction_notes }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $log->created_at }}</td>

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

