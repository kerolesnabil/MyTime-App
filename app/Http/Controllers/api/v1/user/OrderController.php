<?php

namespace App\Http\Controllers\api\v1\user;

use App\Adpaters\Implementation\MoyasarPaymemt;
use App\Adpaters\IPayment;
use App\Events\ChargeWallet;
use App\Events\DecreaseWallet;
use App\Helpers\ImgHelper;
use App\Helpers\OrderActionNamesHelper;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsed;
use App\Models\Order;
use App\Models\OrderCart;
use App\Models\OrderItem;
use App\Models\OrderRejection;
use App\Models\PaymentMethod;
use App\Models\RequestPaymentTransaction;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class OrderController extends Controller
{
    use ItemsPrice;


    public function getListOrdersOfUser()
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $listOfOrders = Order::getOrdersListOrDetailsOfUser($user['user']->user_id);
        if(empty($listOfOrders)){
            return ResponsesHelper::returnData($listOfOrders, '200', __('api.user_not_made_an_order_yet'));
        }

        return ResponsesHelper::returnData($listOfOrders, '200', '');

    }

    public function getOrderDetailsOfUser(Request $request, $orderId)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $request->request->add(['order_id' => $orderId]);
        $rules = [
            "order_id" => "required|numeric|exists:orders,order_id",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "order_id.required"     => __("api.order_id_required"),
                "order_id.numeric"      => __("api.order_id_numeric"),
                "order_id.exists"       => __("api.order_id_exists"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $detailsOfOrder = Order::getOrdersListOrDetailsOfUser($user['user']->user_id, $orderId);

        if(empty($detailsOfOrder)){

            return ResponsesHelper::returnError('400', __('api.not_have_permission_to_do_process'));
        }


        $detailsOfOrder                = $detailsOfOrder[0];
        $detailsOfOrder['vendor_logo'] = ImgHelper::returnImageLink($detailsOfOrder['vendor_logo']);
        $detailsOfOrder['order_items'] = OrderItem::getItemsOfOrderByOrderId($orderId);
        $detailsOfOrder['used_coupon'] = CouponUsed::getUsedCoupon($user['user']->user_id, $orderId)->getData();

        $detailsOfOrder['rejected_reason']   = '';
        $detailsOfOrder['reschedule_reason'] = '';



        if ($detailsOfOrder['order_status'] == 'reschedule'){

            $rescheduleReason = Order::getSuggestedDatesOfOrderOfUser($orderId);
            if (!is_null($rescheduleReason) && !empty($rescheduleReason->reschedule_reason_msg)){
                $detailsOfOrder['reschedule_reason'] = $rescheduleReason->reschedule_reason_msg;
            }

        }
        elseif ($detailsOfOrder['order_status'] == 'rejected'){
            $rejectedReason = OrderRejection::getRejectionReasonByOrderId($orderId);

            if (!is_null($rejectedReason) && !empty($rejectedReason->rejection_reason)){
                $detailsOfOrder['rejected_reason'] = $rejectedReason->rejection_reason;
            }
        }
        return ResponsesHelper::returnData($detailsOfOrder, '200', '');

    }

    public function getTotalPrice(Request $request)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $data  = [];
        if (isset($request->coupon_code))
        {
            $coupon = Coupon::couponVerification($user['user']->user_id, $request->coupon_code);
            if ($coupon['status'] == false){
                $data['coupon'] = $coupon;
            }
            $itemsTotalPrice = self::getTotalPriceOfItems($user['user']->user_id, $coupon)->getData();
        }
        else{
            $itemsTotalPrice = self::getTotalPriceOfItems($user['user']->user_id)->getData();
        }
        if (!isset($itemsTotalPrice->data)){
            return ResponsesHelper::returnError('400',__('api.no_items_in_cart_to_make_order'));
        }

        $data['total_price']  = $itemsTotalPrice->data;
        unset($data['total_price']->items_price_details);

        if (isset($data['total_price']->coupon_id)){
            $coupon= Coupon::getCouponValueByCouponId($data['total_price']->coupon_id);

            $data['total_price']->coupon_value = $coupon->coupon_value;
            $data['total_price']->coupon_type  = $coupon->coupon_type;
            unset($data['coupon']->coupon_id);
            unset($data['total_price']->coupon_id);
        }

        if (isset($data['coupon'])){
            $data['total_price']->coupon_value = $data['coupon']['coupon_value'];
            $data['total_price']->coupon_type  = $data['coupon']['coupon_type'];
            unset($data['coupon']);
        }

        $data = collect($data['total_price'])->toArray();

        $data['order_total_items_price_before_discount'] = strval($data['order_total_items_price_before_discount']);
        $data['order_total_items_price_after_discount']  = strval($data['order_total_items_price_after_discount']);
        $data['order_total_discount']                    = strval($data['order_total_discount']);
        $data['order_taxes_cost']                        = strval($data['order_taxes_cost']);
        $data['order_total_price']                       = strval($data['order_total_price']);



        $data['paymet_methods'] = collect(PaymentMethod::getPaymentMethods('api'));

        return ResponsesHelper::returnData($data,'200','');
    }

    public function makeOrder(Request $request)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $request->request->add(['user_id' => $user['user']->user_id]);
        $rules = [
            "payment_method_id"           => "required|numeric|exists:payment_methods,payment_method_id",
            "order_address"               => "string",
            "order_phone"                 => "digits:9",
            "order_notes"                 => "string",
            "order_custom_date"           => "required|date",
            "order_custom_time"           => "required",
            "order_lat"                   => "string",
            "order_long"                  => "string",
            "coupon_code"                 => "string|exists:coupons,coupon_code",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "payment_method_id.required" => __("api.payment_method_id_required"),
                "payment_method_id.numeric"  => __("api.payment_method_id_numeric"),
                "payment_method_id.exists"   => __("api.payment_method_id_exists"),
                "order_phone.digits"         => __("api.phone_is_9_digits"),
                "order_address.string"       => __("api.order_address_string"),
                "order_notes.string"         => __("api.order_notes_string"),
                "order_custom_date.required" => __("api.order_custom_date_required"),
                "order_custom_date.date"     => __("api.order_custom_date_date"),
                "order_custom_time.required" => __("api.order_custom_time_required"),
                "order_lat.string"          => __("api.order_lat_string"),
                "order_long.string"         => __("api.order_long_string"),
                "coupon_code.string"         => __("api.coupon_code_string"),
                "coupon_code.exists"         => __("api.coupon_code_exists")
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (is_object($this->orderDataHandler($request))){
            return $this->orderDataHandler($request);
        }

        $orderTotalData = $this->orderDataHandler($request);
        $paymentMethod = PaymentMethod::getPaymentMethodById($orderTotalData['payment_method_id']);

        // check if user wallet is enough to pay order
        if ($paymentMethod->payment_method_type == 'wallet')
        {
            $check = $this->checkIfUserWalletIsEnoughToPayOrder($user['user']->user_id, $orderTotalData['order_total_price']);
            if (!empty($check)){
                return $check;
            }
        }

        $storedOrderData = Order::createOrder($orderTotalData);

        OrderItemController::createOrderItems($request->user_id, $storedOrderData->order_id);

        if (isset($request->coupon_code)){
            $coupon = Coupon::couponVerification($user['user']->user_id, $request->coupon_code);
            if ($coupon['status'] != false){

                CouponUsed::createUsedCoupon($request->user_id, $storedOrderData->order_id, $coupon['coupon_id']);
                Coupon::increaseCouponUsedTimes($coupon['coupon_id']);
            }
        }

        OrderCart::deleteOrderCart($request->user_id);


        $paymentFunctionName = "payOrderBy" . ucwords($paymentMethod->payment_method_type);
        $paymentFunction = $this->$paymentFunctionName($storedOrderData);

        if (!empty($paymentFunction)){
            return $paymentFunction;
        }


        return ResponsesHelper::returnData([
            'order_id' => $storedOrderData->order_id,
            'online_payment_url' => ''
        ],
            '200',
            'Order created successfully'
        );

    }

    public function orderDataHandler($orderSubData)
    {

        if (isset($orderSubData->coupon_code))
        {
            $coupon = Coupon::couponVerification($orderSubData->user_id, $orderSubData->coupon_code);

            if ($coupon['status'] == false){
                $data['coupon'] = $coupon;
            }

            $itemsTotalPrice = self::getTotalPriceOfItems($orderSubData->user_id, $coupon)->getData();
        }
        else{
            $itemsTotalPrice = self::getTotalPriceOfItems($orderSubData->user_id)->getData();
        }


        if (!isset($itemsTotalPrice->data)){
            return ResponsesHelper::returnError('400',__('api.no_items_in_cart_to_make_order'));
        }

        $itemsTotalPrice = collect($itemsTotalPrice->data)->toArray();

        $vendorId = $itemsTotalPrice['items_price_details'][0]->vendor_id;
        $serviceLocation = $itemsTotalPrice['items_price_details'][0]->service_location;

        $orderTotalData['user_id']           = $orderSubData->user_id;
        $orderTotalData['vendor_id']         = $vendorId;
        $orderTotalData['payment_method_id'] = $orderSubData->payment_method_id;
        $orderTotalData['order_address']     = $orderSubData->order_address;
        $orderTotalData['order_phone']       = $orderSubData->order_phone;
        $orderTotalData['order_type']        = $serviceLocation;
        $orderTotalData['order_notes']       = $orderSubData->order_notes;
        $orderTotalData['order_custom_date'] = $orderSubData->order_custom_date;
        $orderTotalData['order_custom_time'] = $orderSubData->order_custom_time;
        $orderTotalData['order_lat']         = $orderSubData->order_lat;
        $orderTotalData['order_long']        = $orderSubData->order_long;

        $orderTotalData['order_taxes_rate']                        = $itemsTotalPrice['order_taxes_rate'];
        $orderTotalData['order_total_items_price_before_discount'] = $itemsTotalPrice['order_total_items_price_before_discount'];
        $orderTotalData['order_total_items_price_after_discount']  = $itemsTotalPrice['order_total_items_price_after_discount'];
        $orderTotalData['order_total_discount']                    = $itemsTotalPrice['order_total_discount'];
        $orderTotalData['order_taxes_cost']                        = $itemsTotalPrice['order_taxes_cost'];
        $orderTotalData['order_total_price']                       = $itemsTotalPrice['order_total_price'];

        return $orderTotalData;
    }

    public function checkIfUserWalletIsEnoughToPayOrder($userId, $orderCost)
    {
        $userObj  = User::getUserById($userId);

        if (floatval($orderCost) > floatval($userObj->user_wallet)){
            return ResponsesHelper::returnError('400', __('api.wallet_not_enough_to_pay'));
        }
    }

    private function payOrderByCash($orderData)
    {
        // decrease app profit from vendor wallet
        $arNotes = "تم سحب $orderData->order_app_profit ريال سعودي قيمة ارباح التطبيق في الطلب رقم ($orderData->order_id )";
        $enNotes = "$orderData->order_app_profit app profit SAR, the value of the application profit in order No. ($orderData->order_id ) has been withdrawn";
        $notes = '{"ar":"'.$arNotes.'", "en":"'.$enNotes.'"}';

        event(new DecreaseWallet(
            $orderData->vendor_id,
            $orderData->order_app_profit,
            $notes
        ));

    }

    private function payOrderByWallet($orderData)
    {

        // decrease app profit from user wallet
        $arNotes = "تم سحب $orderData->order_total_price ريال سعودي قيمة تكلفة الطلب رقم ($orderData->order_id )";
        $enNotes = "$orderData->order_total_price app profit SAR, the value of cost of order No. ($orderData->order_id ) has been withdrawn";
        $notes = '{"ar":"'.$arNotes.'", "en":"'.$enNotes.'"}';

        event(new DecreaseWallet(
            $orderData->user_id,
            $orderData->order_total_price,
            $notes
        ));

        // increase vendor wallet
        $arChargeNotes = "تم ايداع مبلغ $orderData->order_total_price قيمة تكلفة الطلب رقم ($orderData->order_id )";
        $enChargeNotes = "$orderData->order_total_price has been deposited, the value of the cost of the order No  ($orderData->order_id)";
        $notes   = '{"ar":"'.$arChargeNotes.'", "en":"'.$enChargeNotes.'"}';

        event(new ChargeWallet(
            $orderData->vendor_id,
            $orderData->order_total_price,
            $notes
        ));

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
        Order::changeOrderPaidCol($orderData->order_id, 1);
    }

    private function payOrderByOnline($orderData)
    {
        $data['amount']               = intval(floatval($orderData->order_total_price) * 100);
        $data['currency']             = 'SAR';
        $data['description']          = 'Order payment invoice';
        $data['callback_url']         = url('moyasar-callback');

        $paymentObj = app(IPayment::class);
        $paymentUrl = $paymentObj->createPayment($data);
        $urlValues  = parse_url($paymentUrl);
        $path       = explode('/', $urlValues['path']);


        $requestPaymentData['invoice_id']   = $path[2];
        $requestPaymentData['user_id']      = $orderData->user_id;
        $requestPaymentData['order_id']     = $orderData->order_id;
        $requestPaymentData['request_type'] = 'order';
        $requestPaymentData['amount']       = floatval($orderData->order_total_price);

        RequestPaymentTransaction::createRequestPaymentTransaction($requestPaymentData);

        return ResponsesHelper::returnData([
            'order_id'           => $orderData->order_id,
            'online_payment_url' => $paymentUrl
        ],
            '200',
            'Order created successfully'
        );

    }

}
