@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site_category.show_category_services')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('category.index') }}"> @lang('site.categories')</a></li>
                <li class="active">@lang('site_category.show_category_services')</li>
            </ol>
        </section>

        <section class="content">


            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-primary">

                        <div class="box-header">
                            <h2 class="box-title" style="font-size: 22px; font-weight: bold; color: red">@lang('site_category.category_services_info')</h2>
                        </div><!-- end of box header -->

                        <hr style="border-top: 1px solid #3c8dbc;">

                        <div class="box-body">

                            @include('partials._errors')


                            <form class="form-group">


                                @foreach($services as $service)
                                    <div style="background-color: #f0f0f0">
                                        <div class="row mb-3 margin-bottom" style="padding: 6px">
                                            <div class="col-md-6 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_category.service_id')</label>
                                                <br>
                                                <label style="color: #ffc606; font-size: 16px">{{$service['service_id']}}</label>
                                            </div>

                                            <div class="col-md-6 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_category.service_name')</label>
                                                <br>
                                                <label style="color: #ffc606; font-size: 16px">{{$service['service_name']}}</label>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach



                            </form><!-- end of form -->



                        </div><!-- end of box body -->

                    </div><!-- end of box -->
                </div>

            </div>


        </section><!-- end of content -->


    </div><!-- end of content wrapper -->
@endsection
