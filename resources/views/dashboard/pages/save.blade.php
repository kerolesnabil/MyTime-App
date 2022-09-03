@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.pages')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('page.index') }}"> @lang('site.pages')</a></li>
                <li class="active"><?php echo isset($page->page_id)? __('site.edit') : __('site.add') ?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> <?php echo isset($page->page_id)? __('site.edit') : __('site.add') ?> </h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <?php isset($page->page_id)?$id = $page->page_id : $id = null?>
                    <form class="form-group" action="{{ route('page.save_page', $id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <?php
                            if(isset($page->page_id)){
                                echo '<input hidden type="text" name="page_id" value="'.$page->page_id.'">';
                            }
                        ?>

                        <label>@lang('site_page.page_title')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input type="text" name="page_title[{{$lang['lang_symbol']}}]" class="form-control" value="<?php echo isset($page->page_id) && isset($page->page_title[$lang['lang_symbol']])?  $page->page_title[$lang['lang_symbol']] : ''?>">
                                </div>
                            @endforeach
                        </div>


                        <label>@lang('site_page.page_content')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <textarea  name="page_content[{{$lang['lang_symbol']}}]" class="form-control ckeditor" rows="5"> <?php echo isset($page->page_id) && isset($page->page_content[$lang['lang_symbol']]) ?  $page->page_content[$lang['lang_symbol']] : ''?></textarea>
                                </div>
                            @endforeach
                        </div>


                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_page.show_in_user_app')</label>
                                <select class="form-select form-control" name="show_in_user_app" data-placeholder="Select a State">
                                    <option value="1" {{isset($page->show_in_user_app) &&  $page->show_in_user_app == 1 ? 'selected = "selected"': ''}} >@lang('site.show')</option>
                                    <option value="0" {{isset($page->show_in_user_app) &&  $page->show_in_user_app == 0 ? 'selected = "selected"': ''}} >@lang('site.hide')</option>
                                </select>
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_page.show_in_vendor_app')</label>
                                <select class="form-select form-control" name="show_in_vendor_app" data-placeholder="Select a State">
                                    <option value="1" {{isset($page->show_in_vendor_app) &&  $page->show_in_vendor_app == 1 ? 'selected = "selected"': ''}} >@lang('site.show')</option>
                                    <option value="0" {{isset($page->show_in_vendor_app) &&  $page->show_in_vendor_app == 0 ? 'selected = "selected"': ''}} >@lang('site.hide')</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_page.page_position')</label>
                                <select class="form-select form-control" name="page_position" data-placeholder="Select a State">
                                    <option value="first" {{isset($page->page_position) &&  $page->page_position == 'first' ? 'selected = "selected"': ''}} >@lang('site_page.page_position_first')</option>
                                    <option value="last" {{isset($page->page_position) &&  $page->page_position == 'last' ? 'selected = "selected"': ''}} >@lang('site_page.page_position_last')</option>
                                </select>
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_page.is_active')</label>
                                <select class="form-select form-control" name="is_active" data-placeholder="Select a State">
                                    <option value="1" {{isset($page->is_active) &&  $page->is_active == 1 ? 'selected = "selected"': ''}} >@lang('site.activeBtn')</option>
                                    <option value="0" {{isset($page->is_active) &&  $page->is_active == 0 ? 'selected = "selected"': ''}} >@lang('site.deactivateBtn')</option>
                                </select>
                            </div>
                        </div>


                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_page.img')</label>
                                <div class="custom-file">
                                    <input type="file" name="img" class="custom-file-input" id="img">
                                </div>
                            </div>

                            <div class="col-md-6 pr-md-1">
                                <div id="img_holder" style="">
                                    <?php
                                    if(isset($page->page_id)){
                                        //edit
                                        $img_url = asset("$page->img");
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


                        {{--<div class="form-group">
                            <img src="{{ $page->image_path }}" style="width: 100px" class="img-thumbnail image-preview" alt="">
                        </div>--}}

                        <div class="row margin-bottom">


                        </div>

                        <div class="row margin-bottom">
                            <div class="col-md-6 pl-md-1">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> <?php echo !isset($page->page_id)?  __('site.add') : __('site.edit')?></button>
                            </div>
                        </div>


                    </form><!-- end of form -->



                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

    <script>
        avatar_img_preview($('#img'), $('#img_holder'), 'img_preview', 'avatar_img')
    </script>

@endsection
