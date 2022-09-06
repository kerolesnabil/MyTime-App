@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.categories')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.categories')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.categories')</h3>

                    <form action="{{ route('category.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                    <a href="{{ route('category.get_category') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($categories->count() > 0)

                        <table class="table table-bordered table-hover">

                            <thead style="background-color: rgba(0,0,0,0.88); color: white">
                            <tr>
                                <th>#</th>
                                <th>@lang('site_category.cat_name')</th>
                                <th style="text-align: center; font-size: 16px">@lang('site_category.parent_cat_name')</th>
                                <th style="text-align: center; font-size: 16px">@lang('site_category.has_children')</th>
                                <th style="text-align: center; font-size: 16px">@lang('site_category.cat_is_active')</th>
                                <th style="text-align: center; font-size: 16px">@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                                $activeBtn = __("site.activeBtn");
                                $deactivateBtn = __("site.deactivateBtn");
                            ?>
                            @foreach ($categories as $index => $category)
                                <tr>
                                    <td style="font-size: 16px">{{ $index + 1 }}</td>
                                    <td style="font-size: 16px">{{ $category->cat_name }}</td>
                                    <td style="text-align: center; font-size: 16px"><?php echo is_null($category->parent_cat_name) ? '-----' : $category->parent_cat_name ?></td>
                                    <td style="text-align: center; font-size: 16px">
                                        <?php
                                            echo $category->has_children == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>


                                    <td id="cat_status_{{$category->cat_id}}" style="text-align: center; font-size: 16px">
                                        <?php
                                            echo $category->cat_is_active == 1 ? '<i class="fa fa-check" style="font-size:18px;color:green"></i>' : '<i class="fa fa-times" style="font-size:18px;color:red"></i>';
                                        ?>
                                    </td>
                                    <td style="font-size: 16px">

                                        <form  class="formData_activation" style="display: inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="cat_id" value="{{$category->cat_id}}">
                                            <?php
                                                echo $category->cat_is_active == 1 ?
                                                    "<button style='font-size: 16px' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> $deactivateBtn</i></button>
                                                     <input type='hidden' id= 'hidden_btn_$category->cat_id' name='active_status' value='false'>
                                                    "
                                                    :
                                                    "<button style='font-size: 16px' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> $activeBtn</button>
                                                     <input type='hidden' id= 'hidden_btn_$category->cat_id' name='active_status' value='true'>
                                                    ";
                                            ?>
                                        </form>

                                        <a style="font-size: 16px" href="{{ route('category.get_category', $category->cat_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>


                                        <form action="{{ route('category.destroy', $category->cat_id) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button style="font-size: 16px" type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        </form>

                                        <a style="font-size: 16px" href="{{ route('category.get_category_services', $category->cat_id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> @lang('site_category.show_category_services')</a>

                                        @if($category->has_children != 0)
                                            <a style="font-size: 16px" href="{{ route('category.get_sub_category', $category->cat_id) }}" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i> @lang('site_category.show_sub_cats')</a>
                                        @endif


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
                                url: "{{route('category.update_activation')}}",
                                data : formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                success: function (data) {

                                    console.log(data);
                                    if (data['status'] == 'activate'){

                                        let cat_status = 'cat_status_' +data['cat_id'];
                                        $('#'+cat_status+'> i').remove();
                                        $('#'+cat_status).append('<i class="fa fa-check" style="font-size:18px;color:green"></i>');
                                        form.find('button').remove();
                                        form.find('#hidden_btn_'+data['cat_id']).remove();
                                        form.append("<button style='font-size: 16px' type='submit' class='activation_btn btn btn-block danger btn-sm'><i class='fa fa-times'> <?php echo $deactivateBtn?></button>");
                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['cat_id'] +"' name='active_status' value='false'>");

                                    }
                                    if (data['status'] == 'deactivate'){
                                        let cat_status = 'cat_status_' +data['cat_id'];
                                        $('#'+cat_status+'> i').remove();
                                        $('#'+cat_status).append('<i class="fa fa-times" style="font-size:18px;color:red"></i>');
                                        form.find('button').remove();

                                        form.find('#hidden_btn_'+data['cat_id']).remove();
                                        form.append("<button style='font-size: 16px' type='submit' class='activation_btn btn btn-info success btn-sm'><i class='fa fa-check'></i> <?php echo $activeBtn?></button>");

                                        form.append("<input type='hidden' id= 'hidden_btn_"+ data['cat_id'] +"' name='active_status' value='true'>");
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

