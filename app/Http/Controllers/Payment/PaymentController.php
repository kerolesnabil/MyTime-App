<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\VarDumper\VarDumper;

class PaymentController extends Controller
{

    public function deposit(Request $request)
    {
        $rules= [
            "amount"                =>"required|integer",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


       $payment_id=PaymentMethod::getPaymentByMethodType('cash')

        $data=[
            'user_id'=>Auth::user()->id,
            'paymentId'=>$payment_id,
            'amount'=>$request->get('amount')
        ]

        $deposit=Deposit::createDeposit($data);

        return ResponsesHelper::returnData($deposit->deposit_id,'200',trans('payment.send_order'));
    }

}
