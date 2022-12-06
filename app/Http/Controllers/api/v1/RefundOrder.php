<?php

namespace App\Http\Controllers\api\v1;

use App\Adpaters\IPayment;
use App\Events\ChargeWallet;
use App\Events\DecreaseWallet;
use App\Helpers\ResponsesHelper;
use App\Models\Order;
use App\Models\RequestPaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

trait RefundOrder
{

    public function refundOrderCost($orderId)
    {

        $orderObj = Order::getOrderById($orderId);

        // check if order is paid && check if order status cancel or rejected
        if (($orderObj->order_status == 'canceled' || $orderObj->order_status == 'rejected') &&
            $orderObj->is_paid == 1 &&
            $orderObj->payment_method_type != 'cash'
        ){

            $functionRefundName = "refund" . ucwords($orderObj->payment_method_type) . "Money";

            // call function to refund based on payment method type
            $this->$functionRefundName($orderId, $orderObj->user_id);


            // decrease vendor wallet
            $moneyWillDecreaseFromVendor = floatval($orderObj->order_total_price) - floatval($orderObj->order_app_profit);
            $arNotes = "تم سحب $moneyWillDecreaseFromVendor ريال سعودي قيمة تكلفة الطلب رقم ($orderObj->order_id )";
            $enNotes = "$orderObj->order_total_price SAR has been withdrawn, the value of the cost of the order No  ($orderObj->order_id)";
            $notes   = '{"ar":"'.$arNotes.'", "en":"'.$enNotes.'"}';

            event(new DecreaseWallet(
                $orderObj->vendor_id,
                $moneyWillDecreaseFromVendor,
                $notes
            ));

            // change order (is_paid) col
            Order::changeOrderPaidCol($orderObj->order_id, 0);
        }

    }

    private function refundOnlineMoney($orderId)
    {
        $orderData = Order::getOrderById($orderId);
        $requestObj = RequestPaymentTransaction::getRequestPaymentByOrderIdAndUserId($orderId, $orderData->user_id);
        if (!is_null($requestObj->invoice_id)){
            // refund money
            $paymentObj = app(IPayment::class);
            $paymentObj->refundOrderMoney($requestObj->payment_id);
        }

    }
    private function refundWalletMoney($orderId)
    {
        $orderData = Order::getOrderById($orderId);

        // increase user wallet
        $arChargeNotes = "تم ايداع مبلغ $orderData->order_total_price قيمة تكلفة الطلب رقم ($orderData->order_id )";
        $enChargeNotes = "$orderData->order_total_price has been deposited, the value of the cost of the order No  ($orderData->order_id)";
        $notes   = '{"ar":"'.$arChargeNotes.'", "en":"'.$enChargeNotes.'"}';

        event(new ChargeWallet(
            $orderData->user_id,
            $orderData->order_total_price,
            $notes
        ));

    }

}
