@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.langs')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('lang.index') }}"> @lang('site.langs')</a></li>
                <li class="active"><?php echo isset($lang->lang_id)? __('site.edit') : __('site.add') ?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> <?php echo isset($lang->lang_id)? __('site.edit') : __('site.add') ?> </h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <?php isset($lang->lang_id)?$id = $lang->lang_id : $id = null?>
                    <form class="form-group" action="{{ route('lang.save_lang', $id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <?php
                            if(isset($lang->lang_id)){
                                echo '<input hidden type="text" name="lang_id" value="'.$lang->lang_id.'">';
                            }
                        ?>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_lang.lang_symbol')</label>
                                <input type="text" name="lang_symbol" class="form-control" value="<?php echo isset($lang->lang_id)?  $lang->lang_symbol : ''?>">
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_lang.lang_name')</label>
                                <input type="text" name="lang_name" class="form-control" value="<?php echo isset($lang->lang_id)?  $lang->lang_name: ''?>">
                            </div>
                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_lang.lang_direction')</label>
                                <select class="form-select form-control" name="lang_direction" data-placeholder="Select a State">
                                    <option value="rtl" {{isset($lang->lang_direction) &&  $lang->lang_direction == 'rtl' ? 'selected = "selected"': ''}} >@lang('site_lang.rtl')</option>
                                    <option value="ltr" {{isset($lang->lang_direction) &&  $lang->lang_direction == 'ltr' ? 'selected = "selected"': ''}} >@lang('site_lang.ltr')</option>
                                </select>

                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_lang.lang_is_active')</label>
                                <select class="form-select form-control" name="lang_is_active" data-placeholder="Select a State">
                                    <option value="1" {{isset($lang->lang_is_active) &&  $lang->lang_is_active == 1 ? 'selected = "selected"': ''}} >@lang('site.activeBtn')</option>
                                    <option value="0" {{isset($lang->lang_is_active) &&  $lang->lang_is_active == 0 ? 'selected = "selected"': ''}} >@lang('site.deactivateBtn')</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_lang.lang_img')</label>
                                <div class="custom-file">
                                    <input type="file" name="lang_img" class="custom-file-input" id="img">
                                </div>
                            </div>

                            <div class="col-md-6 pr-md-1">
                                <div id="img_holder" style="">
                                    <?php
                                    if(isset($lang->lang_id)){
                                        //edit
                                        $img_url = asset("$lang->lang_img");
                                    }
                                    else{
                                        // create view
                                        $img_url = asset('/images/default_ad_img.jpg');
                                    }
                                    ?>

                                    <img class="img_preview"  id="avatar_img" src="{{$img_url}}">
                                </div>
                            </div>
                        </div>


                        <div class="row margin-bottom">
                            <div class="col-md-6 pl-md-1">
                                <button type="submit" class="btn btn-success"></i> @lang('site.save')</button>                            </div>
                            </div>
                        </div>


                    </form><!-- end of form -->



                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

    <script>
        avatar_img_preview($('#images'), $('#img_holder'), 'img_preview', 'avatar_img')
    </script>

@endsection
