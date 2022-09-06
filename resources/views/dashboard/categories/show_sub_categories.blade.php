@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site_category.show_sub_cats')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('category.index') }}"> @lang('site.categories')</a></li>
                <li class="active">@lang('site_category.show_sub_cats')</li>
            </ol>
        </section>

        <section class="content">


            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="box box-primary">

                        <div class="box-header">
                            <h2 class="box-title" style="font-size: 22px; font-weight: bold; color: red">@lang('site_category.category_sub_categories_info')</h2>
                        </div><!-- end of box header -->

                        <hr style="border-top: 1px solid #3c8dbc;">

                        <div class="box-body">

                            @include('partials._errors')


                            <form class="form-group">

                                @foreach($sub_cats as $sub_cat)
                                    <div style="background-color: #f0f0f0">
                                        <div class="row mb-3 margin-bottom" style="padding: 8px">
                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_category.sub_cat_id')</label>
                                                <br>
                                                <label style="color: #4481ff; font-size: 18px">{{$sub_cat['sub_cat_id']}}</label>
                                            </div>

                                            <div class="col-md-4 pr-md-1">
                                                <label style="font-size: 18px">@lang('site_category.sub_category_name')</label>
                                                <br>
                                                <label style="color: #4481ff; font-size: 18px">{{$sub_cat['sub_category_name']}}</label>
                                            </div>

                                            <div class="col-md-4 pr-md-1">
                                                <img class="img_preview"  src="{{$sub_cat['cat_img']}}" style="width: 150px; height: 150px">
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
