@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.services')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('service.index') }}"> @lang('site.services')</a></li>
                <li class="active"><?php echo isset($service['service_id'])? __('site.edit') : __('site.add') ?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title"> <?php echo isset($service['service_id'])? __('site.edit') : __('site.add') ?> </h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <?php isset($service['service_id'])?$id = $service['service_id'] : $id = null?>
                    <form class="form-group" action="{{ route('service.save_service', $id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <?php
                            if(isset($service['service_id'])){
                                echo '<input hidden type="text" name="service_id" value="'.$service['service_id'].'">';
                            }
                        ?>

                        <div class="row mb-3 margin-bottom">
                            <div class="col-md-12 pr-md-1">
                                <label>@lang('site_service.cat_name')</label>
                                <select class="form-select form-control" name="cat_id" data-placeholder="Select a State">
                                    @foreach($cats as $cat)
                                        <option value="{{$cat['cat_id']}}" {{isset($service['category_id']) &&  $service['category_id'] == $cat['cat_id'] ? 'selected = "selected"': ''}} >{{$cat['cat_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <label>@lang('site_service.service_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)

                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input type="text" name="service_name[{{$lang['lang_symbol']}}]" class="form-control" value="{{ isset($service['service_id']) &&  isset($service['service_name'][$lang['lang_symbol']]) ? $service['service_name'][$lang['lang_symbol']] : ''}}">
                                </div>
                            @endforeach
                        </div>


                        <div class="row margin-bottom">
                            <div class="col-md-6 pl-md-1">
                                <button type="submit" class="btn btn-success"></i> @lang('site.save')</button>
                            </div>
                        </div>


                    </form><!-- end of form -->



                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
