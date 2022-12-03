@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.langs')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.langs')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px; color: #605ca8;">@lang('site.langs')</h3>

                    <form action="{{ route('page.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <a href="{{ route('lang.get_lang') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.add')</a>
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if (count($langs) > 0)

                        <table class="table display table-responsive table_with_buttons_without_paging table-hover">

                            <thead class="bg-black">
                            <tr>
                                <th>#</th>
                                <th style="font-size: 18px; font-weight: bold">@lang('site_lang.lang_name')</th>
                                <th style="font-size: 18px; font-weight: bold">@lang('site_lang.lang_direction')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_lang.lang_is_active')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                $activeBtn = __("site.activeBtn");
                                $deactivateBtn = __("site.deactivateBtn");
                            ?>
                            @foreach ($langs as $index => $lang)
                                <tr>
                                    <td style="font-size: 18px; font-weight: bold">{{ $index + 1 }}</td>
                                    <td style="font-size: 18px; font-weight: bold">{{ $lang['lang_name']}}</td>
                                    <td style="font-size: 18px; font-weight: bold"><?php echo __('site_lang.'.$lang['lang_direction'])?></td>

                                    <td id="lang_status_{{$lang['lang_id']}}" style="text-align: center; font-size: 18px; font-weight: bold">
                                        <?php
                                            echo $lang['lang_is_active']== 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>

                                    <td>
                                        <form  class="formData_activation" style="display: inline-block">
                                            {{ csrf_field() }}
                                            <input style="font-size: 18px; font-weight: bold" type="hidden" name="lang_id" value="{{$lang['lang_id']}}">
                                            <?php
                                                $lang_id = $lang['lang_id'];
                                                echo $lang['lang_is_active']== 1 ?
                                                    "<button style='font-size: 18px;' type='submit' class='activation_btn btn btn-block'><i class='fa fa-times'> $deactivateBtn</i></button>
                                                     <input type='hidden' id= 'hidden_btn_$lang_id' name='active_status' value='false'>
                                                    "
                                                    :
                                                    "<button type='submit' class='activation_btn btn btn-info btn-sm'><i class='fa fa-check'></i> $activeBtn</button>
                                                     <input style='font-size: 18px;' type='hidden' id= 'hidden_btn_$lang_id' name='active_status' value='true'>
                                                    ";
                                            ?>
                                        </form>

                                        <a style='font-size: 18px;' href="{{ route('lang.get_lang', $lang['lang_id']) }}" class="btn btn-success"><i class="fa fa-edit"></i> @lang('site.edit')</a>


                                        <form action="{{ route('lang.destroy', $lang['lang_id']) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button style='font-size: 18px;' type="submit" class="btn bg-purple delete"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        </form>

                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table>

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

                            $.ajax({
                                type: 'post',
                                enctype: 'multipart/form-data',
                                url: "{{route('lang.update_activation')}}",
                                data : formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {

                                    if (data['status'] == 'activate'){
                                        let lang_status = 'lang_status_' +data['lang_id'];
                                        $('#'+lang_status+'> i').remove();
                                        $('#'+lang_status).append('<i class="fa fa-check" style="font-size:18px;color:green"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['lang_id']).remove();
                                        form.append("<button style='font-size: 18px; font-weight: bold' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> <?php echo $deactivateBtn?></button>");
                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['lang_id'] +"' name='active_status' value='false'>");

                                    }
                                    if (data['status'] == 'deactivate'){
                                        let lang_status = 'lang_status_' +data['lang_id'];
                                        $('#'+lang_status+'> i').remove();
                                        $('#'+lang_status).append('<i class="fa fa-times" style="font-size:18px;color:red"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['lang_id']).remove();
                                        form.append("<button style='font-size: 18px; font-weight: bold' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> <?php echo $activeBtn?></button>");

                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['lang_id'] +"' name='active_status' value='true'>");
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

