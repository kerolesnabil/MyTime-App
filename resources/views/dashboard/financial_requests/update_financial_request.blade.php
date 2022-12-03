@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang("site_financial_transactions.".$request_type."_requests")</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.homepage') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('vendor.index') }}"> @lang('site_financial_transactions.financial_transactions')</a></li>
                <li class="active">@lang('site.show')</li>
            </ol>
        </section>

        <section class="content">


            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="box box-primary">

                        <div class="box-header">
                            <h3 class="box-title margin-bottom" style="font-size: 18px; font-weight: bold; color: red">@lang("site_financial_transactions.".$request_type."_request") # {{$request->f_t_id}}</h3>
                        </div>

                        <hr>
                        <div class="box-body">

                            @include('partials._errors')

                            <form class="form-group">


                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px; font-weight: bold">@lang('site_user.user_name')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px; font-weight:bold">{{$request->user_name}}</label>
                                    </div>

                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px; font-weight: bold">@lang('site_user.user_phone')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px; font-weight:bold">{{$request->user_phone}}</label>
                                    </div>

                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px; font-weight: bold">@lang('site_user.user_email')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px; font-weight:bold">{{$request->user_email}}</label>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px; font-weight: bold">@lang('site_financial_transactions.amount')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px; font-weight:bold">{{$request->amount}}</label>
                                    </div>

                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px; font-weight: bold">@lang('site_payment_method.payment_method_type')</label>
                                        <br>
                                        <label style="color: #72afd2; font-size: 16px; font-weight:bold">{{$request->payment_method_name}}</label>
                                    </div>

                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px; font-weight: bold">@lang('site_financial_transactions.status')</label>
                                        <br>


                                        <?php
                                            if ($request->status == null){
                                                $color = '#ff8f00';
                                                $label = __('site_financial_transactions.request_waiting');

                                            }
                                            elseif($request->status == 1){
                                                $color = '#0bba22';
                                                $label = __('site_financial_transactions.request_approved');
                                            }
                                            else{
                                                $color = '#ea0000';
                                                $label = __('site_financial_transactions.request_not_approved');
                                            }
                                        ?>


                                        <label style="color: {{$color}}; font-size: 16px; font-weight:bold">{{$label}}</label>
                                    </div>
                                </div>

                                <hr>
                                <?php
                                    if ($request_type == 'deposit' && !is_null($request->deposit_receipt_img)){

                                        $deposit_receipt_img_label = __('site_financial_transactions.deposit_receipt_img');
                                        echo "

                                            <div class='row mb-3 margin-bottom'>
                                                <div class='col-md-4 pr-md-1'>
                                                     <label style='font-size: 18px; font-weight: bold'>$deposit_receipt_img_label</label>
                                                     <br>
                                                     <images class='img_preview'  src='$request->deposit_receipt_img' style='width: 500px; height: 500px'>
                                                </div>
                                            </div>
                                        ";
                                    }

                                    if ($request_type != 'deposit'){
                                        $iban_number = __('site_financial_transactions.iban_number');
                                        $bank_name   = __('site_financial_transactions.bank_name');

                                        $request->iban_number = is_null($request->iban_number)? "-------" : $request->iban_number;
                                        $request->bank_name   = is_null($request->bank_name)? "-------" : $request->bank_name;

                                        echo "

                                            <div class='row mb-3 margin-bottom'>
                                                <div class='col-md-4 pr-md-1'>
                                                     <label style='font-size: 18px; font-weight: bold'>$iban_number</label>
                                                     <br>
                                                     <label style='color: #72afd2; font-size: 16px; font-weight:bold'>$request->iban_number</label>
                                                </div>
                                                <div class='col-md-4 pr-md-1'>
                                                     <label style='font-size: 18px; font-weight: bold'>$bank_name</label>
                                                     <br>
                                                     <label style='color: #72afd2; font-size: 16px; font-weight:bold'>$request->bank_name</label>
                                                </div>
                                            </div>
                                        ";

                                    }
                                ?>
                            </form>


                        </div>

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="box box-solid">

                        <div class="box-header">
                            <h3 class="box-title margin-bottom" style="font-size: 18px; font-weight: bold; color: red">@lang("site_financial_transactions.take_action_towards_request") # {{$request->f_t_id}}</h3>
                        </div>

                        <hr>
                        <div class="box-body">
                            @include('partials._errors')

                            <form class="form-group" action="{{ route('financial_request.update_financial_request', $request->f_t_id) }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}


                                <input hidden name="request_type" value="{{$request_type}}">

                                <div class="row mb-3 margin-bottom">
                                    <div class="col-md-4 pr-md-1">
                                        <label style="font-size: 18px; font-weight: bold">@lang('site_financial_transactions.status')</label>
                                        <br>
                                        <select class="form-control" name="status">
                                            <option style="font-size: 16px; font-weight: bold" value="0">@lang('site_financial_transactions.request_not_approved')</option>
                                            <option style="font-size: 16px; font-weight: bold" value="1">@lang('site_financial_transactions.request_approved')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-8 pr-md-1">
                                        <label style="font-size: 18px; font-weight: bold">@lang('site_financial_transactions.notes')</label>
                                        <br>
                                        <textarea  name="notes" class="form-control" rows="3" style="font-size: 18px"></textarea>
                                    </div>
                                </div>

                                    <?php

                                        if ($request_type == 'withdrawal'){

                                            $withdrawal_receipt_img_label = __('site_financial_transactions.withdrawal_receipt_img');
                                            if (is_null($request->withdrawal_confirmation_receipt_img)){

                                                $img_url = asset('/images/default_ad_img.jpg');
                                            }
                                            else{
                                                $img_url = $request->withdrawal_confirmation_receipt_img;
                                            }

                                            echo "

                                                <div class='row mb-3 margin-bottom'>
                                                    <div class='col-md-6 pr-md-1'>
                                                        <label>$withdrawal_receipt_img_label</label>
                                                        <div class='custom-file'>
                                                            <input type='file' name='withdrawal_confirmation_receipt_img' class='custom-file-input' id='withdrawal_img'>
                                                        </div>
                                                    </div>

                                                    <div class='col-md-6 pr-md-1'>
                                                        <div id='img_holder' style=''>
                                                            <images class='img_preview'  id='avatar_img' src='$img_url'>
                                                        </div>
                                                    </div>
                                                </div>
                                            ";
                                        }
                                    ?>

                                <div class="row margin-bottom">
                                    <div class="col-md-6 pl-md-1">
                                        <button type="submit" class="btn btn-success"></i> @lang('site.save')</button>                            </div>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>

            <script>
                avatar_img_preview($('#withdrawal_img'), $('#img_holder'), 'img_preview', 'avatar_img')
            </script>

        </section>

    </div>
@endsection

