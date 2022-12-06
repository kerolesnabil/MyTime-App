@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.settings')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.homepage') }}"> @lang('site.settings')</a></li>
                <li><a href="{{ route('setting.social_media') }}"> @lang('site_setting.social_media')</a></li>
                <li class="active"><?php echo $operation == 'edit'? __('site.edit') : __('site.add') ?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> <?php  echo $operation == 'edit' ? __('site.edit') : __('site.add') ?> </h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')

                    <form class="form-group" action="{{ route('setting.save_social_media') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        @if($operation == 'edit')
                            <label>@lang('site_setting.setting_name')</label>
                            <div class="row mb-3 margin-bottom">
                                @foreach($langs as $lang)
                                    <div class="col-md-6 pr-md-1">
                                        <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                        <input style="font-size: 18px" type="text" name="setting_name[{{$lang['lang_symbol']}}]" class="form-control" value="{{$social_media['setting_name'][$lang['lang_symbol']]}}" readonly>
                                    </div>
                                @endforeach
                            </div>
                        @endif


                        @if($operation == 'edit' && !empty($social_media['setting_value']))

                            @foreach($social_media['setting_value'] as $key => $item)
                                <div  style="background-color: #f1f4fc;padding: 4px; margin-bottom: 15px">
                                    <div class="row mb-3 margin-bottom">
                                        <div class="col-md-4 pr-md-1">
                                            <label style="font-size: 18px">@lang('site_setting.social_media_name')</label>
                                            <input style="font-size: 18px" type="text" name="setting_value[{{$key}}][name]" class="form-control" value="{{$item['name']}}">
                                        </div>
                                        <div class="col-md-4 pr-md-1">
                                            <label style="font-size: 18px">@lang('site_setting.social_media_link')</label>
                                            <input style="font-size: 18px" type="text" name="setting_value[{{$key}}][link]" class="form-control" value="{{$item['link']}}">
                                        </div>

                                        <div class="col-md-4 pr-md-1">
                                            <label style="font-size: 18px">@lang('site_setting.social_media_class')</label>
                                            <select style="font-size: 18px" class="form-select form-control" name="setting_value[{{$key}}][class]" >
                                                <option style="font-size: 18px" value="bi bi-twitter"   {{$item['class'] == 'bi bi-twitter' ? 'selected = "selected"': ''}} >Twitter</option>
                                                <option style="font-size: 18px" value="bi bi-facebook"  {{$item['class'] == 'bi bi-facebook' ? 'selected = "selected"': ''}} >Facebook</option>
                                                <option style="font-size: 18px" value="bi bi-youtube"   {{$item['class'] == 'bi bi-youtube' ? 'selected = "selected"': ''}} >Youtube</option>
                                                <option style="font-size: 18px" value="bi bi-messenger" {{$item['class'] == 'bi bi-messenger' ? 'selected = "selected"': ''}} >Messenger</option>
                                                <option style="font-size: 18px" value="bi bi-instagram" {{$item['class'] == 'bi bi-instagram' ? 'selected = "selected"': ''}} >Instagram</option>
                                                <option style="font-size: 18px" value="bi bi-whatsapp"  {{$item['class'] == 'bi bi-whatsapp' ? 'selected = "selected"': ''}} >Whatsapp</option>
                                                <option style="font-size: 18px" value="bi bi-telegram"  {{$item['class'] == 'bi bi-telegram' ? 'selected = "selected"': ''}} >Telegram</option>
                                                <option style="font-size: 18px" value="bi bi-snapchat"  {{$item['class'] == 'bi bi-snapchat' ? 'selected = "selected"': ''}} >Snapchat</option>
                                                <option style="font-size: 18px" value="bi bi-discord"   {{$item['class'] == 'bi bi-discord' ? 'selected = "selected"': ''}} >Discord</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @else
                            <div class="row mb-3 margin-bottom">
                                <div class="col-md-4 pr-md-1">
                                    <label style="font-size: 18px">@lang('site_setting.social_media_name')</label>
                                    <input style="font-size: 18px" type="text" name="setting_value[name]" class="form-control">
                                </div>
                                <div class="col-md-4 pr-md-1">
                                    <label style="font-size: 18px">@lang('site_setting.social_media_link')</label>
                                    <input style="font-size: 18px" type="text" name="setting_value[link]" class="form-control">
                                </div>
                                <div class="col-md-4 pr-md-1">
                                    <label style="font-size: 18px">@lang('site_setting.social_media_class')</label>
                                    <select class="form-select form-control" name="setting_value[class]" >
                                        <option style="font-size: 18px" value="bi bi-twitter"  >Twitter</option>
                                        <option style="font-size: 18px" value="bi bi-facebook" >Facebook</option>
                                        <option style="font-size: 18px" value="bi bi-youtube"  >Youtube</option>
                                        <option style="font-size: 18px" value="bi bi-messenger">Messenger</option>
                                        <option style="font-size: 18px" value="bi bi-instagram">Instagram</option>
                                        <option style="font-size: 18px" value="bi bi-whatsapp" >Whatsapp</option>
                                        <option style="font-size: 18px" value="bi bi-telegram" >Telegram</option>
                                        <option style="font-size: 18px" value="bi bi-snapchat" >Snapchat</option>
                                        <option style="font-size: 18px" value="bi bi-discord"  >Discord</option>
                                    </select>
                                </div>
                            </div>
                        @endif


                        <div class="row margin-bottom">
                            <div class="col-md-6 pl-md-1">
                                <input hidden name="operation" type="text" value="<?php  echo $operation == 'edit' ? 'edit' : 'add' ?>">
                                <button type="submit" class="btn btn-success"></i> @lang('site.save')</button>                            </div>
                            </div>
                        </div>


                    </form><!-- end of form -->



                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->



@endsection
