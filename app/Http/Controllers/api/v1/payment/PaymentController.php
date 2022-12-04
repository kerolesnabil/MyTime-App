<?php

namespace App\Http\Controllers\api\v1\payment;

use App\Adpaters\Implementation\MoyasarPaymemt;
use App\Adpaters\ISMSGateway;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\RequestPaymentTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        $orderObj = Order::getOrderById($request->get('order_id'));

        // check if order not paid
        if ($orderObj->is_paid == 1){
            return ResponsesHelper::returnError('400', __('api.order_already_paid'));
        }


        // check if order is online
        if ($orderObj->payment_method_type != 'online'){
            return ResponsesHelper::returnError('400', __('api.payment_method_not_online_can_not_paid'));
        }


        $data['amount']               = intval($orderObj->order_total_price) * 100;
        $data['currency']             = 'SAR';
        $data['description']          = 'Order payment invoice';
        $data['callback_url']         = url('moyasar-callback');
        $data["metadata"]['user_id']  = $user['user']->user_id;
        $data["metadata"]['order_id'] = $user['user']->order_id;


        $paymentUrl = app(MoyasarPaymemt::class)->createPayment($data);

        return ResponsesHelper::returnData($paymentUrl, '200');
    }

    public function getPaymentStatus(Request $request)
    {

        Log::info($request->all());


        return 'wowo';
        $paymentInfo = app(MoyasarPaymemt::class)->getPaymentInfo("");


        $data['user_id']  = '';
        $data['order_id'] = '';
        $data['request_type'] = '';
        $data['amount'] = '';
        $data['request_headers'] = $request->header();
        $data['request_body'] = $request->all();

        RequestPaymentTransaction::createRequestPaymentTransaction($data);




    }


}
