<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\VarDumper;

class PaymentController extends Controller
{

    public function vendorDeposit(Request $request)
    {
        if(Auth::user()->user_type!='vendor'){

            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }
        $rules= [
            "amount"=>"required|integer",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

       $payment_id=PaymentMethod::getPaymentByMethodType('cash');

        $data=[
            'user_id'=>Auth::user()->user_id,
            'payment_id'=>$payment_id['payment_method_id'],
            'amount'=>$request->get('amount')
        ];

        $deposit=Deposit::createDeposit($data);

        return ResponsesHelper::returnData($deposit->deposit_id,'200',trans('payment.send_order'));
    }

    public function vendorWithdrawal(Request $request)
    {

        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }
        $rules= [
            "amount"=>"required|integer",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }
        if(Auth::user()->user_wallet<$request->get('amount'))
        {
            return  ResponsesHelper::returnSuccessMessage(trans('vendor.lower_amount'),'205');
        }

        $payment_id=PaymentMethod::getPaymentByMethodType('cash');
        $data=[
            'user_id'=>Auth::user()->user_id,
            'payment_id'=>$payment_id['payment_method_id'],
            'amount'=>$request->get('amount')
        ];
        $deposit=Withdrawal::createWithdrawal($data);

        return ResponsesHelper::returnData($deposit->deposit_id,'200',trans('payment.send_order'));
    }

}
