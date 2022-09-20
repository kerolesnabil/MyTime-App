@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.admins')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.index') }}"> @lang('site.admins')</a></li>
                <li class="active"><?php echo isset($admin->user_id)? __('site.edit') : __('site.add') ?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> <?php echo isset($admin->user_id)? __('site.edit') : __('site.add') ?> </h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <?php isset($admin->user_id)?$id = $admin->user_id : $id = null?>
                    <form class="form-group" action="{{ route('admin.save_admin', $id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <?php
                            if(isset($admin->user_id)){
                                echo '<input hidden type="text" name="user_id" value="'.$admin->user_id.'">';
                            }
                        ?>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label style="font-weight:bold; font-size: 18px">@lang('site_admin.admin_name')</label>
                                <input style="font-weight:bold; font-size: 16px" type="text" name="user_name" class="form-control" value="<?php echo isset($admin->user_id) && isset($admin->user_name)?  $admin->user_name : Request::old('user_name')?>">
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label style="font-weight:bold; font-size: 18px">@lang('site_user.user_address')</label>
                                <input style="font-weight:bold; font-size: 16px" type="text" name="user_address" class="form-control" value="<?php echo isset($admin->user_id) && isset($admin->user_address)?  $admin->user_address : Request::old('user_address')?>">
                            </div>
                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label style="font-weight:bold; font-size: 18px">@lang('site_user.user_phone')</label>
                                <input style="font-weight:bold; font-size: 16px" type="text" name="user_phone" class="form-control" value="<?php echo isset($admin->user_id) && isset($admin->user_phone)?  $admin->user_phone : Request::old('user_phone')?>">
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label style="font-weight:bold; font-size: 18px">@lang('site_user.user_email')</label>
                                <input style="font-weight:bold; font-size: 16px" type="text" name="user_email" class="form-control" value="<?php echo isset($admin->user_id) && isset($admin->user_email)?  $admin->user_email : Request::old('user_email')?>">
                            </div>
                        </div>


                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label style="font-weight:bold; font-size: 18px">@lang('site_admin.date_of_birth')</label>
                                <input style="font-weight:bold; font-size: 16px" type="date" name="user_date_of_birth" class="form-control" value="<?php echo isset($admin->user_id) && isset($admin->user_date_of_birth)?  $admin->user_date_of_birth : Request::old('user_date_of_birth')?>">
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label style="font-weight:bold; font-size: 18px">@lang('site_user.user_is_active')</label>
                                <select class="form-select form-control" name="user_is_active" data-placeholder="Select a State">
                                    <option style="font-weight:bold; font-size: 16px" value="1" {{isset($admin->user_is_active) &&  $admin->user_is_active == 1 ? 'selected = "selected"': ''}} >@lang('site.activeBtn')</option>
                                    <option style="font-weight:bold; font-size: 16px" value="0" {{isset($admin->user_is_active) &&  $admin->user_is_active == 0 ? 'selected = "selected"': ''}} >@lang('site.deactivateBtn')</option>
                                </select>
                            </div>


                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label style="font-weight:bold; font-size: 18px">@lang('site_admin.admin_password')</label>
                                <input style="font-weight:bold; font-size: 16px" type="password" name="password" class="form-control" value="">
                            </div>

                            <div class="col-md-6 pr-md-1">
                                <label style="font-weight:bold; font-size: 18px">@lang('site_admin.admin_confirm_password')</label>
                                <input style="font-weight:bold; font-size: 16px" type="password" name="confirm_password" class="form-control" value="">
                            </div>

                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_admin.img')</label>
                                <div class="custom-file">
                                    <input type="file" name="user_img" class="custom-file-input" id="img">
                                </div>
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <div id="img_holder" style="">
                                    <?php
                                    if(isset($admin->user_id)){
                                        //edit
                                        $img_url = asset("$admin->user_img");
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
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> <?php echo !isset($admin->user_id)?  __('site.add') : __('site.edit')?></button>
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
