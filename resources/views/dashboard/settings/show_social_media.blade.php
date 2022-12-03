@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.settings')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-cogs"></i> @lang('site.settings')</a></li>
                <li><a href="{{ route('setting.social_media') }}"> @lang('site_setting.social_media')</a></li>
                <li class="active">@lang('site.show')</li>
            </ol>
        </section>

        <section class="content">


            <div class="row">
                <div class="col-md-1">
                </div>


                <div class="col-md-10">
                    <div class="box box-primary">

                        <div class="box-header">
                            <h3 class="box-title">@lang('site_setting.social_media')</h3>
                        </div><!-- end of box header -->

                        <hr style="border-top: 1px solid #3c8dbc;">
                        <div class="box-body">

                            @include('partials._errors')

                            <label style="font-size: 18px; margin-bottom: 15px">@lang('site_setting.setting_name') : <span style="font-size: 17px; font-weight: bold; color: #605ca8;">{{$social_media['setting_name']}}</span></label>
                            <br>
                            <div style="display: inline-block" class="margin-bottom">
                                <a style="font-size: 17px" href="{{ route('setting.get_social_media')}}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                <a style="font-size: 17px" href="{{ route('setting.get_social_media', $social_media['setting_id']) }}" class="btn btn-success"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                            </div>

                            @foreach($social_media['setting_value'] as $key => $item)
                                <div  style="background-color: #f1f4fc;padding: 4px; margin-bottom: 15px">
                                    <div class="row mb-3">

                                        <div class="col-md-3 pr-md-1">
                                            <label style="font-size: 17px">@lang('site_setting.social_media_name')</label>
                                            <br>
                                            <label style="color: #605ca8; font-size: 16px">{{$item['name']}}</label>
                                        </div>
                                        <div class="col-md-3 pr-md-1">
                                            <label style="font-size: 17px">@lang('site_setting.social_media_link')</label>
                                            <br>
                                            <label style="font-size: 16px"><a style="color: #605ca8;" href="{{$item['link']}}"> {{$item['name']}}_link</a></label>
                                        </div>

                                        <div class="col-md-3 pr-md-1">
                                            <label style="font-size: 17px">@lang('site_setting.social_media_class')</label>
                                            <br>
                                            <label style="color: #605ca8; font-size: 16px"><i style="font-size: 25px" class="{{$item['class']}}"></i></label>
                                        </div>

                                        <div class="col-md-3 pr-md-1" style="margin-top: 15px">
                                            <form style="display: inline-block">
                                                {{ csrf_field() }}
                                                <input hidden name="data" value="{{json_encode($social_media['setting_value'])}}">
                                                <input hidden name="deleted_data_key" value="{{$key}}">
                                                <button style="font-size: 17px" type="submit" class="delete_btn btn bg-purple"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form>
                                        </div>


                                    </div>
                                </div>
                            @endforeach



                        </div><!-- end of box body -->

                        <script>
                            $(document).ready(function () {
                                $('body').on('click','.delete_btn', function (e) {

                                    e.preventDefault();

                                    $holderDiv = $(this).parent().parent().parent().parent();

                                    let formData = new FormData($(this).parent()[0]);
                                    $.ajax({
                                        type: 'post',
                                        enctype: 'multipart/form-data',
                                        url: "{{route('setting.destroy_social_media')}}",
                                        data : formData,
                                        processData: false,
                                        contentType: false,
                                        cache: false,
                                        success: function (data) {
                                            console.log(data);
                                            $holderDiv.remove();
                                        },
                                        error: function (data) {
                                            console.log(data);
                                        }
                                    })

                                });
                            });

                        </script>

                    </div><!-- end of box -->
                </div>

            </div>


        </section><!-- end of content -->


    </div><!-- end of content wrapper -->
@endsection
