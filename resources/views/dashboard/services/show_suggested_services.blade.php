@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.suggested_services')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.suggested_services')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 15px; font-size: 20px; color: red; font-weight: bold">@lang('site.suggested_services')</h3>
                </div><!-- end of box header -->

                <div id="filtered-data-holder">

                </div>

                <div class="box-body">

                    @if (count($services) > 0)

                        <table class="table table-bordered table-hover margin-bottom">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr style="font-size: 17px">
                                <th>#</th>
                                <th>@lang('site_service.vendor_name')</th>
                                <th>@lang('site_service.main_cat_suggested')</th>
                                <th>@lang('site_service.sub_cat_suggested')</th>
                                <th style="text-align: center">@lang('site_service.service_suggested_name')</th>
                                <th style="text-align: center">@lang('site_service.created_at')</th>


                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($services as $index => $service)
                                <tr style="font-size: 17px">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $service->vendor_name }}</td>
                                    <td>{{ $service->main_cat_suggested }}</td>
                                    <td>{{ $service->sub_cat_suggested }}</td>
                                    <td>{{ $service->service_suggested_name }}</td>
                                    <td style="text-align: center">{{ date_format($service->created_at, "Y-m-d")}}</td>
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

