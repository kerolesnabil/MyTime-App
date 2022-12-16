@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.vendors')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('vendor.index') }}"> @lang('site.vendors')</a></li>
                <li class="active">@lang('site_vendor.edit_vendor_app_profit_percentage')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> @lang('site_vendor.edit_vendor_app_profit_percentage')</h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')

                    <form style="font-size: 17px" class="form-group" action="{{ route('vendor.edit_vendor_app_profit',  $vendor->user_id ) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}


                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-6 pr-md-1">
                                <label>@lang('site_vendor.vendor_app_profit_percentage') %</label>
                                <input style="font-size: 17px" type="text" name="vendor_app_profit_percentage" class="form-control" value="<?php echo $vendor->vendor_app_profit_percentage ?> ">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 pl-md-1">
                                <button type="submit" class="btn btn-success"></i> @lang('site.save')</button>
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
