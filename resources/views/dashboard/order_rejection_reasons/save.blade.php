@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.order_rejection_reason')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('order_rejection_reason.index') }}"> @lang('site.order_rejection_reason')</a></li>
                <li class="active"><?php echo isset($reason->rejection_reason_id)? __('site.edit') : __('site.add') ?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> <?php echo isset($reason->rejection_reason_id)? __('site.edit') : __('site.add') ?> </h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <?php isset($reason->rejection_reason_id)?$id = $reason->rejection_reason_id : $id = null?>
                    <form class="form-group" action="{{ route('order_rejection_reason.save_order_rejection_reason', $id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        
                        <label>@lang('site_order_rejection_reason.rejection_reason')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input type="text" name="rejection_reason[{{$lang['lang_symbol']}}]" class="form-control" value="<?php echo isset($reason->rejection_reason_id) && isset($reason->rejection_reason[$lang['lang_symbol']])?  $reason->rejection_reason[$lang['lang_symbol']] : ''?>">
                                </div>
                            @endforeach
                        </div>

                        <div class="row margin-bottom">
                            <div class="col-md-6 pl-md-1">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> <?php echo !isset($reason->rejection_reason_id)?  __('site.add') : __('site.edit')?></button>
                            </div>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
