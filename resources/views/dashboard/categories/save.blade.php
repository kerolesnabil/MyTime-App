@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.categories')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('category.index') }}"> @lang('site.categories')</a></li>
                <li class="active"><?php echo isset($category->cat_id)? __('site.edit') : __('site.add') ?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> <?php echo isset($category->cat_id)? __('site.edit') : __('site.add') ?> </h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <?php isset($category->cat_id)?$id = $category->cat_id : $id = null?>
                    <form class="form-group" action="{{ route('category.save_category', $id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <?php
                            if(isset($category->cat_id)){
                                echo '<input hidden type="text" name="cat_id" value="'.$category->cat_id.'">';
                            }
                        ?>

                        <label>@lang('site_category.cat_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input type="text" name="cat_name[{{$lang['lang_symbol']}}]" class="form-control" value="<?php echo isset($category->cat_id) && isset($category->cat_name[$lang['lang_symbol']])?  $category->cat_name[$lang['lang_symbol']] : ''?>">
                                </div>
                            @endforeach
                        </div>


                        <label>@lang('site_category.cat_description')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <textarea  name="cat_description[{{$lang['lang_symbol']}}]" class="form-control" rows="5"> <?php echo isset($category->cat_id) && isset($category->cat_description[$lang['lang_symbol']])?  $category->cat_description[$lang['lang_symbol']] : ''?></textarea>
                                </div>
                            @endforeach
                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_category.parent_cat_name')</label>
                                <select class="form-select form-control" name="parent_id" data-placeholder="Select a State" style="width: 100%">

                                    <option value="0" {{isset($category->parent_id) &&  $category->parent_id == 0 ? 'selected = "selected"': ''}} >@lang('site_category.none_parent')</option>
                                    @foreach($main_cats as $cat)
                                        <option value="{{$cat['cat_id']}}" {{isset($category->parent_id) &&  $category->parent_id == $cat['cat_id'] ? 'selected = "selected"': ''}} >{{$cat['cat_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_category.cat_is_active')</label>
                                <select class="form-select form-control" name="cat_is_active" data-placeholder="Select a State" style="width: 100%">
                                    <option value="1" {{isset($category->cat_is_active) &&  $category->cat_is_active == 1 ? 'selected = "selected"': ''}} >@lang('site.activeBtn')</option>
                                    <option value="0" {{isset($category->cat_is_active) &&  $category->cat_is_active == 0 ? 'selected = "selected"': ''}} >@lang('site.deactivateBtn')</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_category.cat_img')</label>
                                <div class="custom-file">
                                    <input type="file" name="cat_img" class="custom-file-input" id="cat_img">
                                    <div id="craftsman_img_holder">
                                        <div class="col-sm-6"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 pr-md-1">
                                <div id="img_holder" style="">
                                    <?php
                                    if(isset($category->cat_id)){
                                        //edit
                                        $img_url = asset("$category->cat_img");
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
                            <img src="{{ $category->image_path }}" style="width: 100px" class="img-thumbnail image-preview" alt="">
                        </div>--}}

                        <div class="row margin-bottom">


                        </div>

                        <div class="row margin-bottom">
                            <div class="col-md-6 pl-md-1">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> <?php echo !isset($category->cat_id)?  __('site.add') : __('site.edit')?></button>
                            </div>
                        </div>


                    </form><!-- end of form -->



                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

    <script>
        avatar_img_preview($('#cat_img'), $('#img_holder'), 'img_preview', 'avatar_img')
    </script>

@endsection
