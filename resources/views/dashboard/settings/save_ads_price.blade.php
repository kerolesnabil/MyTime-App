@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.settings')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.homepage') }}"> @lang('site.settings')</a></li>
                <li><a href="{{ route('setting.get_app_images') }}"> @lang('site_setting.ads_prices')</a></li>
                <li class="active"><?php echo __('site.edit')?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h1 class="box-title" style="color: red; font-size: 20px; font-weight: bold"> <?php echo __('site.edit')?> @lang('site_setting.ads_prices')</h1>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')
                    <form class="form-group" action="{{ route('setting.save_ad_price') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}


                        <label style="font-size: 18px">@lang('site_setting.setting_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input style="font-weight: bold; font-size: 14px;" type="text" name="ad_price_in_homepage[setting_name][{{$lang['lang_symbol']}}]" class="form-control" value="{{$ad_price_in_homepage['setting_name'][$lang['lang_symbol']]}}">
                                </div>
                            @endforeach
                        </div>

                        <label style="font-size: 18px">@lang('site_setting.ad_price_value')</label>
                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <input style="font-weight: bold; font-size: 14px;" type="text" name="ad_price_in_homepage[setting_value]" class="form-control" value="{{$ad_price_in_homepage['setting_value']}}">
                            </div>
                        </div>
                        <hr style=" border-top: 1px solid #4481ff;">

                        <label style="font-size: 18px">@lang('site_setting.setting_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input style="font-weight: bold; font-size: 14px;" type="text" name="ad_price_in_discover_page[setting_name][{{$lang['lang_symbol']}}]" class="form-control" value="{{$ad_price_in_discover_page['setting_name'][$lang['lang_symbol']]}}">
                                </div>
                            @endforeach
                        </div>

                        <label style="font-size: 18px">@lang('site_setting.ad_price_value')</label>
                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <input style="font-weight: bold; font-size: 14px;" type="text" name="ad_price_in_discover_page[setting_value]" class="form-control" value="{{$ad_price_in_discover_page['setting_value']}}">
                            </div>
                        </div>



                        <div class="row margin-bottom">
                            <div class="col-md-6 pl-md-1">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> {{__('site.edit')}}</button>
                            </div>
                        </div>


                    </form><!-- end of form -->



                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
