<?php

namespace App\Http\Controllers\api\v1\payment;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



class PaymentController extends Controller
{

    public function getPaymentMethods()
    {
        $paymentMethods = PaymentMethod::getPaymentMethods('api');

        return ResponsesHelper::returnData($paymentMethods, '200', '');

    }

    public function createOrderPayment(Request $request)
    {
        $user['user'] = Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $rules = [
            "order_id" => "required|numeric|exists:orders,order_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        if (!Order::checkIfUserHaveOrder($request->get('order_id'), $user['user']->user_id)){
            return ResponsesHelper::returnError('400','');
        }

        $orderObj = Order::getOrderById($request->get('order_id'));


        $paymentObj = PaymentMethod::getPaymentMethodById($orderObj->payment_method_id);

        // check if order is online
        // check if order not paid





    }

    public function getPaymentStatus($request)
    {


    }






}
