@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.services')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.services')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 15px; font-size: 20px; color: red; font-weight: bold">@lang('site.services')</h3>

                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('service.get_service') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                        </div>
                    </div>
                </div><!-- end of box header -->



                <div class="box-body">

                    @if (count($services) > 0)

                        <table class="table table-bordered table-hover margin-bottom">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr style="font-size: 17px">
                                <th>#</th>
                                <th>@lang('site_service.service_name')</th>
                                <th>@lang('site_service.sub_cat_suggested')</th>
                                <th>@lang('site_service.main_cat_suggested')</th>
                                <th style="text-align: center">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($services as $index => $service)
                                <tr style="font-size: 17px">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $service->service_name }}</td>

                                    @if(is_null($service->main_cat_name))
                                        <td>------</td>
                                        <td>{{ $service->sub_cat_name }}</td>
                                    @else
                                        <td>{{ $service->sub_cat_name }}</td>
                                        <td>{{ $service->main_cat_name }}</td>
                                    @endif

                                    <td>
                                        <a style='font-size: 17px' href="{{ route('service.get_service', $service->service_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>

                                        <form action="{{ route('service.destroy', $service->service_id) }}" method="post" style="display: inline-block">
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


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->




@endsection

