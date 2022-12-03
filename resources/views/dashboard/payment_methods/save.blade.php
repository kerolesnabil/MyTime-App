@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.payment_methods')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('payment_method.index') }}"> @lang('site.payment_methods')</a></li>
                <li class="active"><?php echo isset($payment_method->payment_method_id)? __('site.edit') : __('site.add') ?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> <?php echo isset($payment_method->payment_method_id)? __('site.edit') : __('site.add') ?> </h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <?php isset($payment_method->payment_method_id)?$id = $payment_method->payment_method_id : $id = null?>
                    <form  style="font-size: 16px" class="form-group" action="{{ route('payment_method.save_payment_method', $id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <?php
                            if(isset($payment_method->payment_method_id)){
                                echo '<input hidden type="text" name="payment_method_id" value="'.$payment_method->payment_method_id.'">';
                            }
                        ?>

                        <label>@lang('site_payment_method.payment_method_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input type="text" name="payment_method_name[{{$lang['lang_symbol']}}]" class="form-control" value="<?php echo isset($payment_method->payment_method_id) && isset($payment_method->payment_method_name[$lang['lang_symbol']])?  $payment_method->payment_method_name[$lang['lang_symbol']] : ''?>">
                                </div>
                            @endforeach
                        </div>


                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_payment_method.payment_method_type')</label>
                                <select style="font-size: 16px" class="form-select form-control" name="payment_method_type" data-placeholder="Select a State">
                                    <option value="online" {{isset($payment_method->payment_method_type) &&  $payment_method->payment_method_type == 'online' ? 'selected = "selected"': ''}} >@lang("site_payment_method.payment_method_type_online")</option>
                                    <option value="cash"   {{isset($payment_method->payment_method_type) &&  $payment_method->payment_method_type == 'cash' ? 'selected = "selected"': ''}} >@lang("site_payment_method.payment_method_type_cash")</option>
                                    <option value="wallet" {{isset($payment_method->payment_method_type) &&  $payment_method->payment_method_type == 'wallet' ? 'selected = "selected"': ''}} >@lang("site_payment_method.payment_method_type_wallet")</option>
                                </select>
                            </div>
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_payment_method.is_active')</label>
                                <select class="form-select form-control" name="is_active" data-placeholder="Select a State">
                                    <option value="1" {{isset($payment_method->is_active) &&  $payment_method->is_active == 1 ? 'selected = "selected"': ''}} >@lang('site.activeBtn')</option>
                                    <option value="0" {{isset($payment_method->is_active) &&  $payment_method->is_active == 0 ? 'selected = "selected"': ''}} >@lang('site.deactivateBtn')</option>
                                </select>
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
