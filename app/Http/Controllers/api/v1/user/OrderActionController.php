<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderReview;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class OrderActionController extends Controller
{

    public function addOrderReview(Request $request)
    {

        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400','you are not a user');
        }

        $rules = [
            "order_id"       => "required|numeric|exists:orders,order_id",
            "rate"           => "required|numeric|min:1|max:5",
            "review_comment" => "string",

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400','You don\'t have permission to review this order');
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'done'){
            return ResponsesHelper::returnError('400','The status of order is not done yet, you can not review now');
        }

        if (! OrderReview::checkIfUserReviewOrder($request->order_id, $user['user']->user_id ) ){
            return ResponsesHelper::returnError('400', 'This order has already been reviewed by this user');
        }

        Order::updateIsRatedColIfOrderReviewed($request->order_id);

        $review = OrderReview::addOrderReview($request, $user['user']->user_id);
        return ResponsesHelper::returnData(['order_review_id' => $review], '200', 'Review added successfully');
    }

    public function changePaymentMethodOfOrder(Request $request)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400','you are not a user');
        }

        $rules = [
            "order_id"          => "required|numeric|exists:orders,order_id",
            "payment_method_id" => "required|numeric|exists:payment_methods,payment_method_id",

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400','You don\'t have permission to change payment method for this order');
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'accepted'){
            return ResponsesHelper::returnError('400','The status of order is not accepted yet, you can not change payment method now');
        }


        $orderPaymentMethod = Order::changePaymentMethodOfOrder($request, $user['user']->user_id);

        return ResponsesHelper::returnData($orderPaymentMethod['data'], $orderPaymentMethod['code'], $orderPaymentMethod['msg']);

    }

    public function cancelOrderOfUser(Request $request, $orderId)
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

        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400','You don\'t have permission to cancel this order');
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'pending'){
            return ResponsesHelper::returnError('400','Order status not pending, you can not cancel this order');
        }

        Order::changeStatusOfOrder('canceled', $orderId);
        return ResponsesHelper::returnData([], '200', 'The order has been successfully canceled');

    }

    public function getSuggestedDatesOfOrder(Request $request, $orderId)
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

        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400','You don\'t have permission to cancel this order');
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'reschedule'){
            return ResponsesHelper::returnError('400','Order status not reschedule, you can not get suggested dates for this order');
        }

        $suggestedDates = Order::getSuggestedDatesOfOrderOfUser($request->order_id);


        if (is_null($suggestedDates)){
            return ResponsesHelper::returnError('400','There are no suggested dates by the vendor for this order');
        }

        $data['suggested_dates'] = json_decode($suggestedDates->suggested_date_by_vendor,true);

        $data['reschedule_reason_msg'] = $suggestedDates->reschedule_reason_msg;

        return ResponsesHelper::returnData($data, '200', '');
    }

    public function changeOrderDate(Request $request)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400','you are not a user');
        }

        $rules = [
            "order_id"   => "required|numeric|exists:orders,order_id",
            "date" => "required|date",
            "time" => "required|date_format:H:i:s",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400','You don\'t have permission to cancel this order');
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'reschedule'){
            return ResponsesHelper::returnError('400','Order status not reschedule, you can not change date of this order');
        }


        Order::changeOrderDate($request, $user['user']->user_id);

        Order::changeStatusOfOrder('pending', $request->order_id);

        return ResponsesHelper::returnData([], '200', 'Order date updated successfully');
    }

}
