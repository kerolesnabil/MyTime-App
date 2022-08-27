<?php

namespace App\Http\Controllers\api\v1\payment;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



class PaymentController extends Controller
{

    public function getPaymentMethods()
    {
        $paymentMethods = PaymentMethod::getPaymentMethods();

        return ResponsesHelper::returnData($paymentMethods, '200', '');


    }





}
