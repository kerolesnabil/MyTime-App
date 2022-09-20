@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.users')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.users')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-body">
                    <i class="fa fa-file-text-o" aria-hidden="true" style="font-size: 24px; display: inline"> <span style=" font-weight: bold;color: #0B90C4">@lang('site.report')</span></i>
                    <hr>

                    @if ($users->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr>
                                <th>#</th>
                                <th>@lang('site_user.user_name')</th>
                                <th>@lang('site_user.user_phone')</th>
                                <th>@lang('site_user.user_email')</th>
                                <th>@lang('site_user.user_address')</th>
                                <th style="text-align: center">@lang('site_user.user_is_active')</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach ($users as $index=>$user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->user_name }}</td>
                                    <td>{{ $user->user_phone }}</td>
                                    <td>{{ $user->user_email }}</td>
                                    <td>{{ $user->user_address }}</td>
                                    <td id="user_status_{{$user->user_id}}" style="text-align: center">
                                        <?php
                                            echo $user->user_is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>

                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->
                    {!! $users->links() !!}

                        {{ $users->appends(request()->query())->links() }}

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection

