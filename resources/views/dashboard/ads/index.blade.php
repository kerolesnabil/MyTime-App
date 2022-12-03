@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.ads')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.ads')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 15px; color: #605ca8;">@lang('site.ads')</h3>
                </div><!-- end of box header -->

                <div id="filtered-data-holder">

                </div>

                <div class="box-body">

                    @if ($ads->total() > 0)

                        <table class="table table-responsive table_with_buttons_without_paging table-hover">

                            <thead class="bg-black">

                            <tr style="font-size: 17px">
                                <th>#</th>
                                <th>@lang('site_ad.ad_title')</th>
                                <th>@lang('site_ad.vendor_name')</th>
                                <th style="text-align: center">@lang('site_ad.ad_days')</th>
                                <th style="text-align: center">@lang('site_ad.ad_start_at')</th>
                                <th style="text-align: center">@lang('site_ad.ad_end_at')</th>
                                <th style="text-align: center">@lang('site_ad.ad_cost')</th>
                                <th style="text-align: center">@lang('site_ad.ad_at_homepage')</th>
                                <th style="text-align: center">@lang('site_ad.ad_at_discover_page')</th>
                                <th style="text-align: center">@lang('site_ad.created_at')</th>
                                <th style="text-align: center">@lang('site.action')</th>

                            </tr>
                            </thead>

                            <tbody>




                            @foreach ($ads as $index => $ad)
                                <?php
                                    if ($ad->status == 0 && !is_null($ad->status)){
                                        $style = 'background-color: #ff8894';
                                    }
                                    elseif ($ad->status == 1){
                                        $style = 'background-color: #99f8af';
                                    }
                                    else{
                                        $style = 'background-color: #fce59e';
                                    }
                                ?>


                                <tr style="{{$style}}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $ad->ad_title }}</td>
                                    <td>{{ $ad->vendor_name }}</td>
                                    <td style="text-align: center">{{ $ad->ad_days}}</td>
                                    <td style="text-align: center">{{ $ad->ad_start_at}}</td>
                                    <td style="text-align: center">{{ $ad->ad_end_at}}</td>
                                    <td style="text-align: center">{{ $ad->ad_cost}}</td>

                                    <td style="text-align: center">
                                        <?php
                                            echo $ad->ad_at_homepage == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>
                                    <td style="text-align: center">
                                        <?php
                                        echo $ad->ad_at_discover_page == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>


                                    <td style="text-align: center">{{ $ad->created_at }}</td>

                                    <td>
                                        <a style="font-size: 17px" href="{{ route('ad.show_ad', $ad->ad_id) }}" class="btn btn-success btn-sm"><i class="fa  fa-eye"></i> @lang('site.show')</a>
                                        <?php if (is_null($ad->status)): ?>
                                            <a style='font-size: 17px' href="{{ route('ad.reject_ads', $ad->ad_id) }}" class="btn  bg-purple btn-sm"> @lang('site.reject')</a>
                                            <a style='font-size: 17px' href="{{ route('ad.accept_ads', $ad->ad_id) }}" class="btn btn-success btn-sm"> @lang('site.accept')</a>
                                        <?php endif; ?>
                                    </td>

                                </tr>

                            @endforeach
                            </tbody>

                        </table>
                        {!! $ads->links() !!}


                        <p>
                            {{$ads->count()}} {{__('site.of')}} {{$ads->total()}}
                        </p>
                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->




@endsection

