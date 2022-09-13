@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.ads')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('ad.index') }}"> @lang('site.ads')</a></li>
                <li class="active">@lang('site.show')</li>
            </ol>
        </section>

        <section class="content">


            <div class="row">
                <div class="col-md-1">
                </div>


                <div class="col-md-10">
                    <div class="box box-primary">

                        <div class="box-header">
                            <h3 class="box-title">@lang('site_ad.ad_number')  <span style="color: #ff0005">  #{{$ad->ad_id}}</span></h3>
                        </div><!-- end of box header -->

                        <hr style="border-top: 1px solid #3c8dbc;">
                        <div class="box-body">

                            @include('partials._errors')


                            <form class="form-group">


                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_ad.ad_title')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{$ad->ad_title}}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_ad.vendor_name')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{$ad->vendor_name}}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_ad.ad_cost')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{$ad->ad_cost}}</label>
                                    </div>

                                </div>
                                <div class="row mb-3 margin-bottom">

                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_ad.ad_days')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{$ad->ad_days}}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_ad.ad_start_at')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{$ad->ad_start_at}}</label>
                                    </div>
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_ad.ad_end_at')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{$ad->ad_end_at}}</label>
                                    </div>

                                </div>
                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_ad.ad_at_homepage')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $ad->ad_at_homepage == 1? __('site.yes') : __('site.no') }}</label>
                                    </div>

                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_ad.ad_at_discover_page')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px">{{ $ad->ad_at_discover_page == 1? __('site.yes') : __('site.no') }}</label>
                                    </div>

                                </div>

                                <div class="row mb-3 margin-bottom">

                                    <div class="col-md-6 pr-md-1">
                                        <label style="font-size: 18px">@lang('site_ad.ad_img')</label>
                                        <div id="img_holder" style="">
                                            <img class="img_preview"  id="avatar_img" src="{{$ad->ad_img}}" style="height: 250px; width: 250px">
                                        </div>
                                    </div>
                                </div>
                            </form><!-- end of form -->



                        </div><!-- end of box body -->

                    </div><!-- end of box -->
                </div>
                <script>
                    avatar_img_preview($('#img'), $('#img_holder'), 'img_preview', 'avatar_img')
                </script>


            </div>


        </section><!-- end of content -->


    </div><!-- end of content wrapper -->
@endsection
