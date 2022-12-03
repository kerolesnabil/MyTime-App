@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.settings')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('admin.homepage') }}"> @lang('site.settings')</a></li>
                <li><a href="{{ route('setting.get_bank_account_details') }}"> @lang('site_setting.bank_account_details')</a></li>
                <li class="active"><?php echo __('site.edit')?></li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h1 class="box-title" style="color: red; font-size: 20px; font-weight: bold"> <?php echo __('site.edit')?> @lang('site_setting.bank_account_details')</h1>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')
                    <form class="form-group" action="{{ route('setting.save_bank_account_details') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}


                        <label style="font-size: 18px; color: #605ca8 ">@lang('site_setting.setting_name')</label>
                        <div class="row mb-3 margin-bottom">
                            @foreach($langs as $lang)
                                <div class="col-md-6 pr-md-1">
                                    <label style="color: #007cff; font-size: 18px">{{$lang['lang_symbol']}}</label>
                                    <input style="font-weight: bold; font-size: 14px;" type="text" name="bank_account_details[setting_name][{{$lang['lang_symbol']}}]" class="form-control" value="{{$bank_account_details['setting_name'][$lang['lang_symbol']]}}">
                                </div>
                            @endforeach
                        </div>

                        <div class="row mb-3 margin-bottom"  >
                            <div class="col-md-4">
                                <label style="font-size: 18px">@lang('site_setting.bank_name')</label>
                                <input name="bank_account_details[setting_value][bank_name]" class="form-control" value="{{$bank_account_details['setting_value']['bank_name']}}">
                            </div>

                            <div class="col-md-4">
                                <label style="font-size: 18px">@lang('site_setting.account_number')</label>
                                <input name="bank_account_details[setting_value][account_number]" class="form-control" value="{{$bank_account_details['setting_value']['account_number']}}">
                            </div>

                            <div class="col-md-4">
                                <label style="font-size: 18px">@lang('site_setting.iban_number')</label>
                                <input name="bank_account_details[setting_value][iban_number]" class="form-control" value="{{$bank_account_details['setting_value']['iban_number']}}">
                            </div>
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
