<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        Deposit::createDeposit($data);


    }

}
