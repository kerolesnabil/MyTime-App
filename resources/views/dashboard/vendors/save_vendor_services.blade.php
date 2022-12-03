@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.vendor_services')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('vendor.index') }}"> @lang('site.vendor_services')</a></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site_vendor.add_vendor_services') : <span style="color: #605ca8">{{$vendor->vendor_name}}</span></h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')



                    <form class="form-group" action="{{ route('vendor.save_vendor_services', $vendor->user_id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <table class="table table-responsive table-hover">

                            <thead class="bg-black">
                            <tr style="font-size: 15px">
                                <th>#</th>
                                <th>@lang('site_service.main_cat_suggested')</th>
                                <th>@lang('site_service.sub_cat_suggested')</th>
                                <th>@lang('site_service.service_name')</th>
                                <th>@lang('site_service.service_title')</th>
                                <th>@lang('site_service.service_price_at_salon')</th>
                                <th>@lang('site_service.service_discount_price_at_salon')</th>
                                <th>@lang('site_service.service_price_at_home')</th>
                                <th>@lang('site_service.service_discount_price_at_home')</th>
                            </tr>
                            </thead>

                            <tbody >
                            <?php
                                $vendor_services_ids = collect($vendor_services)->pluck('main_service_id')->toArray();
                                $vendor_services = collect($vendor_services);
                            ?>

                            @foreach ($all_services as $service)
                                    <?php
                                        if (in_array($service->service_id, $vendor_services_ids)){
                                            $vendor_service = $vendor_services->where('main_service_id','=',$service->service_id)->first();
                                        }
                                        else{
                                            $vendor_service = null;
                                        }
                                    ?>


                                <tr>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="service_id[{{$service->service_id}}]"
                                            value="{{$service->service_id}}"
                                            <?php echo in_array($service->service_id, $vendor_services_ids)? 'checked':''?>
                                        >
                                    </td>

                                    @if(is_null($service->main_cat_name))
                                        <td>
                                            <input type="text" class="form-control" value="{{$service->sub_cat_name}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" readonly >
                                        </td>

                                    @else
                                        <td>
                                            <input type="text" class="form-control" value="{{$service->main_cat_name}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="{{$service->sub_cat_name}}">
                                        </td>
                                    @endif


                                    <td>
                                        <input type="text" class="form-control" value="{{$service->service_name}}">
                                    </td>

                                    <td>
                                        <input type="text"
                                               name="service_title[{{$service->service_id}}]"
                                               class="form-control"
                                               value="<?php echo $vendor_service != null ? $vendor_service->service_title : ''?>"
                                        >
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            name="service_price_at_salon[{{$service->service_id}}]"
                                            class="form-control"
                                            value="<?php echo $vendor_service != null ? $vendor_service->service_price_at_salon : ''?>"
                                        >
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            name="service_discount_price_at_salon[{{$service->service_id}}]"
                                            class="form-control"
                                            value="<?php echo $vendor_service != null ? $vendor_service->service_discount_price_at_salon : ''?>"
                                        >
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            name="service_price_at_home[{{$service->service_id}}]"
                                            class="form-control"
                                            value="<?php echo $vendor_service != null ? $vendor_service->service_price_at_home : ''?>"
                                        >
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            name="service_discount_price_at_home[{{$service->service_id}}]"
                                            class="form-control"
                                            value="<?php echo $vendor_service != null ? $vendor_service->service_discount_price_at_home : ''?>"
                                        >
                                    </td>


                                </tr>

                            @endforeach
                            </tbody>


                        </table>

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


@endsection
