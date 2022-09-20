@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.order_rejection_reason')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.order_rejection_reason')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.order_rejection_reason')</h3>

                    <form action="{{ route('order_rejection_reason.index') }}" method="get">

                        <div class="row">


                            <div class="col-md-4">
                                <a href="{{ route('order_rejection_reason.get_order_rejection_reason') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($reasons->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >#</th>
                                <th style="font-size: 18px; font-weight: bold">@lang('site_order_rejection_reason.rejection_reason')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site.action')</th>

                            </tr>
                            </thead>

                            <tbody>

                            @foreach ($reasons as $index => $reason)
                                <tr>
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $index + 1 }}</td>
                                    <td style="font-size: 18px; font-weight: bold">{{ $reason->rejection_reason }}</td>


                                    <td style="text-align: center">

                                        <a style="font-size: 16px; font-weight: bold" href="{{ route('order_rejection_reason.get_order_rejection_reason', $reason->rejection_reason_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>


                                        <form action="{{ route('order_rejection_reason.destroy', $reason->rejection_reason_id) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button style="font-size: 16px; font-weight: bold" type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
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

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection

