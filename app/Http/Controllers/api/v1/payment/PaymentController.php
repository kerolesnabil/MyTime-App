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
            $requestPaymentDataWillUpdate['payment_id']      = $request->get('payments')[0]['id'];
            $requestPaymentDataWillUpdate['request_headers'] = $request->header();
            $requestPaymentDataWillUpdate['request_body']    = $request->all();
            RequestPaymentTransaction::updateRequestPaymentTransaction($requestPaymentObj->id, $requestPaymentDataWillUpdate);

            if ($request->get('status') == 'paid' && $request->get('currency') == 'SAR'){
                if ($requestPaymentObj->order_id != null){
                    //  case order
                    $orderData = Order::getOrderById($requestPaymentObj->order_id);

                    // decrease app profit from vendor wallet
                    $arNotes = "تم سحب $orderData->order_app_profit ريال سعودي قيمة ارباح التطبيق في الطلب رقم ($orderData->order_id )";
                    $enNotes = "$orderData->order_app_profit app profit SAR, the value of the application profit in order No. ($orderData->order_id ) has been withdrawn";
                    $notes = '{"ar":"'.$arNotes.'", "en":"'.$enNotes.'"}';

                    event(new DecreaseWallet(
                        $orderData->vendor_id,
                        $orderData->order_app_profit,
                        $notes
                    ));

                    // update order paid (is paid) col
                    Order::changeOrderPaidCol($requestPaymentObj->order_id, 1);

                    // msgs for deposit money in vendor wallet
                    $arChargeNotes = "تم ايداع مبلغ $requestPaymentObj->amount قيمة تكلفة الطلب رقم ($orderData->order_id )";
                    $enChargeNotes = "$requestPaymentObj->amount has been deposited, the value of the cost of the order No  ($orderData->order_id)";
                    $notes   = '{"ar":"'.$arChargeNotes.'", "en":"'.$enChargeNotes.'"}';

                    $userId = $orderData->vendor_id;
                }
                else{
                    // case charge wallet
                    $amountWillCharge = $requestPaymentObj->amount;
                    $arNotes = " تم ايداع مبلغ $amountWillCharge ريال سعودي ";
                    $enNotes = "amount has been deposited $amountWillCharge SAR";
                    $notes   = '{"ar":"'.$arNotes.'", "en":"'.$enNotes.'"}';
                    $userId = $requestPaymentObj->user_id;
                }

                // charge vendor wallet (deposit)
                event(new ChargeWallet(
                    $userId,
                    $requestPaymentObj->amount,
                    $notes
                ));
            }

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

        $data['amount']                     = intval(floatval($request->get('amount')) * 100);
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

        return ResponsesHelper::returnData([
            'online_payment_url' => $paymentUrl
        ],
            '200',
            ''
        );
    }

}
