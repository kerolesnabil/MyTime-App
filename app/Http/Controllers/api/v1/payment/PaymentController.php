<?php

namespace App\Http\Controllers\api\v1\payment;

use App\Adpaters\Implementation\MoyasarPaymemt;
use App\Adpaters\IPayment;
use App\Adpaters\ISMSGateway;
use App\Events\ChargeWallet;
use App\Events\DecreaseWallet;
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


    public function paymentCallback(Request $request)
    {
        Log::info($request->all());

        $invoiceId = $request->get('id');

        $requestPaymentObj = RequestPaymentTransaction::getRequestPaymentTransactionByInvoiceId($invoiceId);

        if (is_object($requestPaymentObj)){
            // update request payment transaction data
            $requestPaymentDataWillUpdate['request_headers'] = $request->header();
            $requestPaymentDataWillUpdate['request_body'] = $request->all();
            RequestPaymentTransaction::updateRequestPaymentTransaction($requestPaymentObj->id, $requestPaymentDataWillUpdate);


            if ($request->get('status') == 'paid' &&
                $request->get('currency') == 'SAR'
            ){
                if ($requestPaymentObj->order_id != null){
                    // update order paid (is paid) col
                    Order::changeOrderPaidCol($requestPaymentObj->order_id, 1);
                }
                else{
                    $arNotes = " تم ايداع مبلغ $requestPaymentObj->amount ريال سعودي ";
                    $enNotes = "amount has been deposited $requestPaymentObj->amount SAR";
                    $notes   = '{"ar":"'.$arNotes.'", "en":"'.$enNotes.'"}';

                    // charge vendor wallet (deposit)
                    event(new ChargeWallet(
                        $requestPaymentObj->user_id,
                        $requestPaymentObj->amount,
                        $notes
                    ));
                }
            }

        }
    }

    public function refundOrderCost(Request $request)
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

        // check if order is not paid
        if ($orderObj->is_paid != 1){
            return ResponsesHelper::returnError('400', __('api.order_already_not_paid'));
        }


        // check if order status cancel or rejected
        if ($orderObj->order_status != 'canceled' && $orderObj->order_status != 'rejected'){
            return ResponsesHelper::returnError('400', __('api.can_not_refund_order_status_not_reject_or_cancel'));
        }


        // check if order is online
        if ($orderObj->payment_method_type != 'online'){
            return ResponsesHelper::returnError('400', __('api.payment_method_not_online_can_not_paid'));
        }

        $requestObj = RequestPaymentTransaction::getRequestPaymentByOrderIdAndUserId($orderObj->order_id, $user['user']->user_id);

        if (!is_null($requestObj->invoice_id)){

            // refund money
            $paymentObj = app(IPayment::class);
            $paymentObj->refundOrderMoney($requestObj->invoice_id);


            // change order (is_paid) col
            Order::changeOrderPaidCol($orderObj->order_id, 0);


            // decrease vendor wallet
            $moneyWillDecreaseFromVendor = floatval($orderObj->order_total_price) - floatval($orderObj->order_app_profit);
            $arNotes = "تم سحب $moneyWillDecreaseFromVendor ريال سعودي قيمة تكلفة الطلب رقم )$orderObj->order_id )";
            $enNotes = "$orderObj->order_total_price SAR has been withdrawn, the value of the cost of the order No  ($orderObj->order_id)";
            $notes   = '{"ar":"'.$arNotes.'", "en":"'.$enNotes.'"}';

            event(new DecreaseWallet(
                $orderObj->vendor_id,
                $moneyWillDecreaseFromVendor,
                $notes

            ));

        }


    }

    public function chargeWalletPayment(Request $request)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $rules = [
            "amount" => "required|numeric|min:1"
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $data['amount']                     = floatval($request->get('amount')) * 100;
        $data['currency']                   = 'SAR';
        $data['description']                = 'charge wallet';
        $data['callback_url']               = url('moyasar-callback');
        $data["metadata"]['user_id']        = null;
        $data["metadata"]['order_id']       = $user['user']->user_id;

        $paymentObj                         = app(IPayment::class);
        $paymentUrl                         = $paymentObj->createPayment($data);
        $urlValues                          = parse_url($paymentUrl);
        $path                               = explode('/', $urlValues['path']);

        $requestPaymentData['invoice_id']   = $path[2];
        $requestPaymentData['user_id']      = $user['user']->user_id;
        $requestPaymentData['order_id']     = null;
        $requestPaymentData['request_type'] = 'charge_wallet';
        $requestPaymentData['amount']       = floatval($request->get('amount'));

        RequestPaymentTransaction::createRequestPaymentTransaction($requestPaymentData);

    }

}
