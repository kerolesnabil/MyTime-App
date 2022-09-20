@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.pages')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.pages')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.pages')</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('page.get_page') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                        </div>
                    </div>
                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($pages->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr>
                                <th style='text-align: center; font-size: 18px; font-weight: bold' >#</th>
                                <th style='font-size: 18px; font-weight: bold'>@lang('site_page.page_title')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_page.page_position')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_page.show_in_user_app')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_page.show_in_vendor_app')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site_page.is_active')</th>
                                <th style="text-align: center; font-size: 18px; font-weight: bold">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                $activeBtn = __("site.activeBtn");
                                $deactivateBtn = __("site.deactivateBtn");
                            ?>
                            @foreach ($pages as $index => $page)
                                <tr>
                                    <td  style="font-size: 18px; font-weight: bold">{{ $index + 1 }}</td>
                                    <td  style="font-size: 18px; font-weight: bold">{{ $page->page_title }}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">{{ $page->page_position}}</td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">
                                        <?php
                                            echo $page->show_in_user_app == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>
                                    <td style="text-align: center; font-size: 18px; font-weight: bold">
                                        <?php
                                        echo $page->show_in_vendor_app == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>


                                    <td id="page_status_{{$page->page_id}}" style="text-align: center; font-size: 18px; font-weight: bold">
                                        <?php
                                            echo $page->is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>
                                    <td>

                                        <form  class="formData_activation" style="display: inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="page_id" value="{{$page->page_id}}">
                                            <?php
                                                echo $page->is_active == 1 ?
                                                    "<button style='font-size: 18px; font-weight: bold'type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> $deactivateBtn</i></button>
                                                     <input type='hidden' id= 'hidden_btn_$page->page_id' name='active_status' value='false'>
                                                    "
                                                    :
                                                    "<button style='font-size: 18px; font-weight: bold' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> $activeBtn</button>
                                                     <input type='hidden' id= 'hidden_btn_$page->page_id' name='active_status' value='true'>
                                                    ";
                                            ?>
                                        </form>

                                        <a style='font-size: 18px; font-weight: bold' href="{{ route('page.get_page', $page->page_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>


                                        <form action="{{ route('page.destroy', $page->page_id) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button style='font-size: 18px; font-weight: bold' type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
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
                                url: "{{route('page.update_activation')}}",
                                data : formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {

                                    console.log(data);
                                    if (data['status'] == 'activate'){

                                        let page_status = 'page_status_' +data['page_id'];
                                        $('#'+page_status+'> i').remove();
                                        $('#'+page_status).append('<i class="fa fa-check" style="font-size:18px;color:green"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['page_id']).remove();
                                        form.append("<button style='font-size: 18px; font-weight: bold' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> <?php echo $deactivateBtn?></button>");
                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['page_id'] +"' name='active_status' value='false'>");

                                    }
                                    if (data['status'] == 'deactivate'){
                                        let page_status = 'page_status_' +data['page_id'];
                                        $('#'+page_status+'> i').remove();
                                        $('#'+page_status).append('<i class="fa fa-times" style="font-size:18px;color:red"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['page_id']).remove();
                                        form.append("<button style='font-size: 18px; font-weight: bold' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> <?php echo $activeBtn?></button>");
                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['page_id'] +"' name='active_status' value='true'>");
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

