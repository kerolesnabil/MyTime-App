@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.vendor_details')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('vendor.index') }}"> @lang('site.vendor_details')</a></li>
                <li class="active">@lang('site.show')</li>
            </ol>
        </section>

        <section class="content">


            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">

                        <div class="box-header">
                            <h3 class="box-title">@lang('site.vendor_details')</h3>
                        </div><!-- end of box header -->

                        <div class="box-body">

                            @include('partials._errors')


                            <form class="form-group">


                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-6 pr-md-1">
                                        <label>@lang('site_vendor.vendor_address')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 14px">{{$vendor->vendor_address}}</label>
                                    </div>

                                    <div class="col-md-6 pr-md-1">
                                        <label>@lang('site_vendor.vendor_phone')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 14px">{{$vendor->vendor_phone}}</label>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-6 pr-md-1">
                                        <label>@lang('site_vendor.vendor_start_time')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 14px">{{$vendor->vendor_start_time}}</label>
                                    </div>

                                    <div class="col-md-6 pr-md-1">
                                        <label>@lang('site_vendor.vendor_end_time')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 14px">{{$vendor->vendor_end_time}}</label>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-6 pr-md-1">
                                        <label>@lang('site_vendor.vendor_commercial_registration_num')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 14px">{{$vendor->vendor_commercial_registration_num}}</label>
                                    </div>

                                    <div class="col-md-6 pr-md-1">
                                        <label>@lang('site_vendor.vendor_tax_num')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 14px">{{$vendor->vendor_tax_num}}</label>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-6 pr-md-1">
                                        <label>@lang('site_vendor.vendor_description')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 14px">{{$vendor->vendor_description}}</label>
                                    </div>
                                </div>
                                <hr>

                                <label>@lang('site_vendor.vendor_slider')</label>
                                <div class="row mb-3 margin-bottom">
                                    @if(!is_null($vendor->vendor_slider))
                                        @foreach($vendor->vendor_slider as $img_url)
                                            <div class="col-md-3">
                                                <img class="img_preview"  src="{{$img_url}}" style="width: 220px; height: 220px">
                                            </div>
                                        @endforeach
                                    @else

                                        <div class="col-md-6 pr-md-1">
                                            <label style="color: #72afd2; font-size: 14px">@lang('site_vendor.not_vendor_slider')</label>
                                        </div>
                                    @endif


                                </div>

                            </form><!-- end of form -->



                        </div><!-- end of box body -->

                    </div><!-- end of box -->
                </div>
                <div class="col-md-4">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle" src="{{$vendor->vendor_logo}}" alt="Vendor profile picture" style="height: 250px; width: 250px">

                            <h3 class="profile-username text-center" style="color: #00a1ff">{{$vendor->vendor_name}}</h3>

                            <p class="text-muted text-center" style="color: red; font-weight: bold; font-size: 15px">{{$vendor->vendor_type}}</p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>@lang('site_vendor.vendor_rate')</b> <a class="pull-right" style="font-weight: bold; color: #00a1ff">{{$vendor->vendor_rate}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>@lang('site_vendor.vendor_reviews_count')</b> <a class="pull-right" style="font-weight: bold; color: #00a1ff">{{$vendor->vendor_reviews_count}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>@lang('site_vendor.vendor_views_count')</b> <a class="pull-right" style="font-weight: bold; color: #00a1ff">{{$vendor->vendor_views_count}}</a>
                                </li>
                                <li class="list-group-item">



                                    <?php

                                        if ( $vendor->vendor_wallet < 0){
                                            $color = "#ff0006";
                                        }
                                        else{
                                            $color = "#00a1ff";
                                        }
                                    ?>




                                    <b>@lang('site_vendor.wallet')</b> <a class="pull-right" style="font-weight: bold; color: {{$color}}">

                                        {{$vendor->vendor_wallet}}

                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>
            </div>


        </section><!-- end of content -->


    </div><!-- end of content wrapper -->
@endsection
