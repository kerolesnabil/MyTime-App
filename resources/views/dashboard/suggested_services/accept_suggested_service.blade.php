@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.services')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('service.index') }}"> @lang('site.suggested_services')</a></li>
                <li class="active">@lang('site_service.add_suggested_service')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site_service.add_suggested_service')</h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')


                    <div class="col-md-12" style="margin-bottom: 10px">

                        <div class="row bg-info"  style="padding: 10px">

                            <div class="col-md-4">
                                <label class="box-title">@lang('site_service.suggested_service_main_cat')</label>
                                <p style="color: #605ca8; font-size: 16px">{{$service->main_cat_suggested}}</p>
                            </div>

                            <div class="col-md-4">
                                <label class="box-title">@lang('site_service.suggested_service_sub_cat')</label>
                                <p style="color: #605ca8; font-size: 16px">{{$service->sub_cat_suggested}}</p>
                            </div>


                            <div class="col-md-4">
                                <label class="box-title">@lang('site_service.suggested_service_name')</label>
                                <p style="color: #605ca8; font-size: 16px">{{$service->service_suggested_name}}</p>
                            </div>

                        </div>

                    </div>

                    <form class="form-group" action="{{ route('service.save_service') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <?php
                            if(isset($service['service_id'])){
                                echo '<input hidden type="text" name="service_id" value="'.$service['service_id'].'">';
                            }
                        ?>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-12 pr-md-1">
                                <label>@lang('site_service.cat_name')</label>
                                <select class="form-select form-control" name="cat_id" data-placeholder="Select a State">
                                    @foreach($cats as $cat)
                                        <option
                                            value="{{$cat['cat_id']}}"
                                            {{ stripos($service->sub_cat_suggested, $cat['cat_name']) !== false || stripos($service->main_cat_suggested, $cat['cat_name']) !== false? 'selected = "selected"': ''}} >{{$cat['cat_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <label>@lang('site_service.service_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)

                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input type="text" name="service_name[{{$lang['lang_symbol']}}]" class="form-control" value="{{$lang['lang_symbol'] == 'ar' ? $service->service_suggested_name : ''}}">
                                </div>
                            @endforeach
                        </div>


                        <div class="row margin-bottom">
                            <div class="col-md-6 pl-md-1">
                                <button type="submit" class="btn btn-success"></i> @lang('site.save')</button>
                            </div>
                        </div>


                    </form><!-- end of form -->



                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
