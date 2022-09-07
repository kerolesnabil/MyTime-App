@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.coupons')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('coupon.index') }}"> @lang('site.coupons')</a></li>
                <li class="active"><?php echo isset($coupon->coupon_id)? __('site.edit') : __('site.add') ?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> <?php echo isset($coupon->coupon_id)? __('site.edit') : __('site.add') ?> </h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <?php isset($coupon->coupon_id)?$id = $coupon->coupon_id : $id = null?>
                    <form style="font-size: 17px" class="form-group" action="{{ route('coupon.save_coupon', $id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <?php
                            if(isset($coupon->coupon_id)){
                                echo '<input hidden type="text" name="coupon_id" value="'.$coupon->coupon_id.'">';
                            }
                        ?>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_coupon.coupon_code')</label>
                                <input style="font-size: 17px" type="text" name="coupon_code" class="form-control" value="<?php echo isset($coupon->coupon_id) && isset($coupon->coupon_code)?  $coupon->coupon_code : old('coupon_code') ?> ">
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_coupon.coupon_value')</label>
                                <input style="font-size: 17px" type="text" name="coupon_value" class="form-control" value="<?php echo isset($coupon->coupon_id) && isset($coupon->coupon_value)?  $coupon->coupon_value: old('coupon_value')?>">

                            </div>
                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_coupon.coupon_type')</label>
                                <select class="form-select form-control" name="coupon_type" data-placeholder="Select a State">
                                    <option style="font-size: 17px" value="value" {{isset($coupon->coupon_type) &&  $coupon->coupon_type == 'value' ? 'selected = "selected"': ''}} >@lang('site_coupon.coupon_type_value')</option>
                                    <option style="font-size: 17px" value="percent" {{isset($coupon->coupon_type) &&  $coupon->coupon_type == 'percent' ? 'selected = "selected"': ''}} >@lang('site_coupon.coupon_type_percent')</option>
                                </select>
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_coupon.coupon_limited_num')</label>
                                <input style="font-size: 17px" type="text" name="coupon_limited_num" class="form-control" value="<?php echo isset($coupon->coupon_id) && isset($coupon->coupon_limited_num)?  $coupon->coupon_limited_num: old('coupon_limited_num')?>">
                            </div>
                        </div>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_coupon.coupon_start_at')</label>
                                <input style="font-size: 17px" type="date" name="coupon_start_at" class="form-control pull-right" value="<?php echo isset($coupon->coupon_id) && isset($coupon->coupon_start_at)?  $coupon->coupon_start_at: old('coupon_start_at')?>">
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_coupon.coupon_end_at')</label>
                                <input style="font-size: 17px" type="date" name="coupon_end_at" class="form-control pull-right" value="<?php echo isset($coupon->coupon_id) && isset($coupon->coupon_end_at)?  $coupon->coupon_end_at: old('coupon_end_at')?>">
                            </div>
                        </div>


                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_coupon.is_active')</label>
                                <select class="form-select form-control" name="is_active" data-placeholder="Select a State">
                                    <option style="font-size: 17px" value="1" {{isset($coupon->is_active) &&  $coupon->is_active == 1 ? 'selected = "selected"': ''}} >@lang('site.activeBtn')</option>
                                    <option style="font-size: 17px" value="0" {{isset($coupon->is_active) &&  $coupon->is_active == 0 ? 'selected = "selected"': ''}} >@lang('site.deactivateBtn')</option>
                                </select>
                            </div>
                        </div>

                        <div class="row margin-bottom">
                            <div class="col-md-6 pl-md-1">
                                <button style="font-size: 17px" type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> <?php echo !isset($coupon->coupon_id)?  __('site.add') : __('site.edit')?></button>
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
