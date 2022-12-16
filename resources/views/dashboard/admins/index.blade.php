@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.admins')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.admins')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.admins')</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('admin.get_admin') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.add')</a>
                        </div>
                    </div>

                </div><!-- end of box header -->

                <?php
                    $activeBtn = __("site.activeBtn");
                    $deactivateBtn = __("site.deactivateBtn");
                ?>


                <div class="table-responsive">

                    @if (count($admins) > 0)

                        <table class="table table_with_buttons_without_paging table-hover">

                            <thead class="bg-black">
                            <tr style="font-weight: bold; font-size: 18px">
                                <th>#</th>
                                <th>@lang('site_admin.admin_name')</th>
                                <th>@lang('site_user.user_phone')</th>
                                <th>@lang('site_user.user_email')</th>
                                <th>@lang('site_user.user_address')</th>
                                <th style="text-align: center">@lang('site_user.user_is_active')</th>
                                <th style="text-align: center">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach ($admins as $index=>$user)
                                <tr style="font-weight: bold; font-size: 16px">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->user_name }}</td>
                                    <td>{{ $user->user_phone }}</td>
                                    <td>{{ $user->user_email }}</td>
                                    <td>{{ $user->user_address }}</td>
                                    <td id="user_status_{{$user->user_id}}" style="text-align: center">
                                        <?php
                                            echo $user->user_is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?></td>
                                    <td>
                                        <a style="font-weight: bold; font-size: 16px" href="{{ route('admin.get_admin', $user->user_id) }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>


                                        <form action="{{ route('admin.destroy', $user->user_id) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button style="font-weight: bold; font-size: 16px" type="submit" class="btn bg-purple delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        </form>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->



            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection

