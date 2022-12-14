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
                    <h3 class="box-title" style="margin-bottom: 15px; color: #605ca8;">@lang('site.suggested_services')</h3>
                </div><!-- end of box header -->

                <div id="filtered-data-holder">

                </div>

                <div class="table-responsive">

                    @if (count($services) > 0)

                        <table class="table table_with_buttons_without_paging">

                            <thead class="bg-black">
                            <tr style="font-size: 17px">
                                <th>#</th>
                                <th>@lang('site_service.vendor_name')</th>
                                <th>@lang('site_service.main_cat_suggested')</th>
                                <th>@lang('site_service.sub_cat_suggested')</th>
                                <th style="text-align: center">@lang('site_service.service_suggested_name')</th>
                                <th style="text-align: center">@lang('site_service.created_at')</th>
                                <th>@lang('site.action')</th>


                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($services as $index => $service)

                                <?php
                                    if ($service->service_suggested_status == 0 && !is_null($service->service_suggested_status)){
                                        $style = 'background-color: #ff8894';
                                    }
                                    elseif ($service->service_suggested_status == 1){
                                        $style = 'background-color: #99f8af';
                                    }
                                    else{
                                        $style = 'background-color: #fce59e';
                                    }
                                ?>

                                <tr style="{{$style}}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $service->vendor_name }}</td>
                                    <td>{{ $service->main_cat_suggested }}</td>
                                    <td>{{ $service->sub_cat_suggested }}</td>
                                    <td>{{ $service->service_suggested_name }}</td>
                                    <td style="text-align: center">{{ date_format($service->created_at, "Y-m-d")}}</td>
                                    <td>
                                        <?php if (is_null($service->service_suggested_status)): ?>
                                            <a style='font-size: 17px' href="{{ route('suggested_service.accept_suggested_services', $service->service_suggested_id) }}" class="btn  btn-success btn-sm"> @lang('site.accept')</a>
                                            <a style='font-size: 17px' href="{{ route('suggested_service.reject_suggested_service', $service->service_suggested_id) }}" class="btn bg-purple btn-sm"> @lang('site.reject')</a>

                                        <?php endif; ?>
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

