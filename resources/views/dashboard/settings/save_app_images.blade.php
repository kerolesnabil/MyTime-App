@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.settings')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.homepage') }}"> @lang('site.settings')</a></li>
                <li><a href="{{ route('setting.get_app_images') }}"> @lang('site_setting.app_images')</a></li>
                <li class="active"><?php echo __('site.edit')?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h1 class="box-title" style="color: red; font-size: 20px; font-weight: bold"> <?php echo __('site.edit')?> @lang('site_setting.app_images')</h1>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <form class="form-group" action="{{ route('setting.save_app_images') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}


                        <label>@lang('site_setting.setting_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>


                                    <input style="font-weight: bold; font-size: 14px;" type="text" name="user_splash[setting_name][{{$lang['lang_symbol']}}]" class="form-control" value="{{$user_splash['setting_name'][$lang['lang_symbol']]}}">
                                </div>
                            @endforeach
                        </div>
                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_setting.setting_img')</label>
                                <div class="custom-file">
                                    <input type="file" name="user_splash[setting_value]" class="custom-file-input" id="user_splash_img">
                                </div>
                            </div>

                            <div class="col-md-6 pr-md-1">
                                <div id="user_splash_img_holder">
                                    <?php
                                    if(!is_null($user_splash['setting_value'])){
                                        //edit
                                        $img_url = $user_splash['setting_value'];
                                        $img_url = asset("$img_url");
                                    }
                                    else{
                                        // create view
                                        $img_url = asset('/images/default_ad_img.jpg');
                                    }
                                    ?>

                                    <img class="img_preview"  id="user_splash_avatar_img" src="{{$img_url}}">
                                </div>
                            </div>
                        </div>
                        <hr style=" border-top: 1px solid #4481ff;">

                        <label>@lang('site_setting.setting_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input style="font-weight: bold; font-size: 14px;" type="text" name="user_logo[setting_name][{{$lang['lang_symbol']}}]" class="form-control" value="{{$user_logo['setting_name'][$lang['lang_symbol']]}}">
                                </div>
                            @endforeach
                        </div>
                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_setting.setting_img')</label>
                                <div class="custom-file">
                                    <input type="file" name="user_logo[setting_value]" class="custom-file-input" id="user_logo_img">
                                </div>
                            </div>

                            <div class="col-md-6 pr-md-1">
                                <div id="user_logo_img_holder">
                                    <?php
                                    if(!is_null($user_logo['setting_value'])){
                                        //edit
                                        $img_url = $user_logo['setting_value'];
                                        $img_url = asset("$img_url");
                                    }
                                    else{
                                        // create view
                                        $img_url = asset('/images/default_ad_img.jpg');
                                    }
                                    ?>

                                    <img class="img_preview"  id="user_logo_avatar_img" src="{{$img_url}}">
                                </div>
                            </div>
                        </div>
                        <hr style=" border-top: 1px solid #4481ff;">


                        <label>@lang('site_setting.setting_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input style="font-weight: bold; font-size: 14px;" type="text" name="vendor_splash[setting_name][{{$lang['lang_symbol']}}]" class="form-control" value="{{$vendor_splash['setting_name'][$lang['lang_symbol']]}}">
                                </div>
                            @endforeach
                        </div>
                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_setting.setting_img')</label>
                                <div class="custom-file">
                                    <input type="file" name="vendor_splash[setting_value]" class="custom-file-input" id="vendor_splash_img">
                                </div>
                            </div>

                            <div class="col-md-6 pr-md-1">
                                <div id="vendor_splash_img_holder">
                                    <?php
                                    if(!is_null($vendor_splash['setting_value'])){
                                        //edit
                                        $img_url = $vendor_splash['setting_value'];
                                        $img_url = asset("$img_url");
                                    }
                                    else{
                                        // create view
                                        $img_url = asset('/images/default_ad_img.jpg');
                                    }
                                    ?>

                                    <img class="img_preview"  id="vendor_splash_avatar_img" src="{{$img_url}}">
                                </div>
                            </div>
                        </div>
                        <hr style=" border-top: 1px solid #4481ff;">

                        <label>@lang('site_setting.setting_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input style="font-weight: bold; font-size: 14px;" type="text" name="vendor_logo[setting_name][{{$lang['lang_symbol']}}]" class="form-control" value="{{$vendor_logo['setting_name'][$lang['lang_symbol']]}}">
                                </div>
                            @endforeach
                        </div>
                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_setting.setting_img')</label>
                                <div class="custom-file">
                                    <input type="file" name="vendor_logo[setting_value]" class="custom-file-input" id="vendor_logo_img">
                                </div>
                            </div>

                            <div class="col-md-6 pr-md-1">
                                <div id="vendor_logo_img_holder">
                                    <?php
                                    if(!is_null($vendor_logo['setting_value'])){
                                        //edit
                                        $img_url = $vendor_logo['setting_value'];
                                        $img_url = asset("$img_url");
                                    }
                                    else{
                                        // create view
                                        $img_url = asset('/images/default_ad_img.jpg');
                                    }
                                    ?>

                                    <img class="img_preview"  id="vendor_logo_avatar_img" src="{{$img_url}}">
                                </div>
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

    <script>
        avatar_img_preview($('#user_splash_img'), $('#user_splash_img_holder'), 'img_preview', 'user_splash_avatar_img');
        avatar_img_preview($('#user_logo_img'), $('#user_logo_img_holder'), 'img_preview', 'user_logo_avatar_img');
        avatar_img_preview($('#vendor_splash_img'), $('#vendor_splash_img_holder'), 'img_preview', 'vendor_splash_avatar_img');
        avatar_img_preview($('#vendor_logo_img'), $('#vendor_logo_img_holder'), 'img_preview', 'vendor_logo_avatar_img');
    </script>

@endsection
