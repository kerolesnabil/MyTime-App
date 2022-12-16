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

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px; color: #605ca8">@lang('site.users')</h3>

                </div><!-- end of box header -->

                <div class="table-responsive">

                    @if ($users->count() > 0)

                        <table class="table table_with_buttons_without_paging table-hover">

                            <thead class="bg-black">
                            <tr>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >#</th>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >@lang('site_user.user_name')</th>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >@lang('site_user.user_phone')</th>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >@lang('site_user.user_email')</th>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >@lang('site_user.user_address')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_user.user_is_active')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                $activeBtn = __("site.activeBtn");
                                $deactivateBtn = __("site.deactivateBtn");
                            ?>
                            @foreach ($users as $index=>$user)
                                <tr>
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $index + 1 }}</td>
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $user->user_name }}</td>
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $user->user_phone }}</td>
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $user->user_email }}</td>
                                    <td style='text-align: center; font-size: 18px; font-weight: bold' >{{ $user->user_address }}</td>
                                    <td id="user_status_{{$user->user_id}}" style="text-align: center; font-size: 18px; font-weight: bold">
                                        <?php
                                            echo $user->user_is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?></td>
                                    <td>

                                        <form  class="formData_activation" style="display: inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="user_id" value="{{$user->user_id}}">
                                            <?php
                                                echo $user->user_is_active == 1 ?
                                                    "<button style='text-align: center; font-size: 18px; font-weight: bold' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> $deactivateBtn</i></button>
                                                     <input type='hidden' id= 'hidden_btn_$user->user_id' name='active_status' value='false'>
                                                    "
                                                    :
                                                    "<button style='text-align: center; font-size: 18px; font-weight: bold' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> $activeBtn</button>
                                                     <input type='hidden' id= 'hidden_btn_$user->user_id' name='active_status' value='true'>
                                                    ";
                                            ?>
                                        </form>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->
                        {!! $users->links("pagination::bootstrap-4") !!}

                        {{ $users->appends(request()->query())->links() }}

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->

                <script>
                    $(document).ready(function () {
                        $('body').on('click','.activation_btn', function (e) {

                            e.preventDefault();
                            let formData = new FormData($(this).parent()[0]);
                            let form = $(this).parent();

                            console.log(form.find('#hidden_btn_'+7));
                            $.ajax({
                                type: 'post',
                                enctype: 'multipart/form-data',
                                url: "{{route('user.update_activation')}}",
                                data : formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {

                                    if (data['status'] == 'activate'){

                                        let user_status = 'user_status_' +data['user_id'];
                                        $('#'+user_status+'> i').remove();
                                        $('#'+user_status).append('<i class="fa fa-check" style="font-size:18px;color:green"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['user_id']).remove();
                                        form.append("<button style='text-align: center; font-size: 18px; font-weight: bold' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> <?php echo $deactivateBtn?></button>");
                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['user_id'] +"' name='active_status' value='false'>");

                                    }
                                    if (data['status'] == 'deactivate'){
                                        let user_status = 'user_status_' +data['user_id'];
                                        $('#'+user_status+'> i').remove();
                                        $('#'+user_status).append('<i class="fa fa-times" style="font-size:18px;color:red"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['user_id']).remove();
                                        form.append("<button style='text-align: center; font-size: 18px; font-weight: bold' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> <?php echo $activeBtn?></button>");

                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['user_id'] +"' name='active_status' value='true'>");
                                    }
                                },
                                error: function (data) {
                                    console.log(data);
                                }
                            })

                        });
                    });

                </script>


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection

