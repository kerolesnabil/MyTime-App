<?php

namespace App\Http\Controllers\api\v1\user;

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
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class OrderController extends Controller
{
    use ItemsPrice;

    public static $tax = "10";

    public function getListOrdersOfUser()
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400','you are not a user');
        }

        $listOfOrders = Order::getOrdersListOrDetailsOfUser($user['user']->user_id);
        if(empty($listOfOrders)){
            return ResponsesHelper::returnData($listOfOrders, '200', 'This user has not made an order yet');
        }

        return ResponsesHelper::returnData($listOfOrders, '200', '');

    }

    public function getOrderDetailsOfUser(Request $request, $orderId)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400','you are not a user');
        }

        $request->request->add(['order_id' => $orderId]);
        $rules = [
            "order_id" => "required|numeric|exists:orders,order_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $detailsOfOrder = Order::getOrdersListOrDetailsOfUser($user['user']->user_id, $orderId);

        if(empty($detailsOfOrder)){

            return ResponsesHelper::returnError('400', "You don't have permission to get details of this order");
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
            return ResponsesHelper::returnError('400','you are not a user');
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
            return ResponsesHelper::returnError('400','There are no items in the cart to make order');
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



        $data['paymet_methods'] = PaymentMethod::getPaymentMethods();

        return ResponsesHelper::returnData($data,'200','');
    }

    public function makeOrder(Request $request)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400','you are not a user');
        }

        $request->request->add(['user_id' => $user['user']->user_id]);
        $rules = [
            "payment_method_id"           => "required|numeric|exists:payment_methods,payment_method_id",
            "order_address"               => "string",
            "order_phone"                 => "required|string|digits:9",
            "order_notes"                 => "required|string",
            "order_custom_date"           => "required|date",
            "order_custom_time"           => "required",
            "order_lat"                   => "numeric",
            "order_long"                  => "numeric",
            "coupon_code"                 => "string|exists:coupons,coupon_code",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (is_object($this->orderDataHandler($request))){
            return $this->orderDataHandler($request);
        }

        $orderTotalData = $this->orderDataHandler($request);

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



        //calc admin profit
        //based on setting, get margin percent and apply it on total price
        //then if the payment method was cash,
        //you should decrease the calculated amount from the vendor's wallet

        //online payment
        //you should increase (total order amount - the calculated amount) from the vendor's wallet
        return ResponsesHelper::returnData(['order_id' => $storedOrderData->order_id], '200', 'Order created successfully');

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
            return ResponsesHelper::returnError('400','There are no items in the cart to make order');
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


}
