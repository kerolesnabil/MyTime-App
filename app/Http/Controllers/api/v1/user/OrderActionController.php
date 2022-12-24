<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\api\v1\RefundOrder;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderReview;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class OrderActionController extends Controller
{
use RefundOrder;

    public function addOrderReview(Request $request)
    {

        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $rules = [
            "order_id"       => "required|numeric|exists:orders,order_id",
            "rate"           => "required|numeric|min:1|max:5",
            "review_comment" => "string",

        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "order_id.required"     => __("api.order_id_required"),
                "order_id.numeric"      => __("api.order_id_numeric"),
                "order_id.exists"       => __("api.order_id_exists"),
                "rate.required"         => __("api.rate_required"),
                "rate.numeric"          => __("api.rate_numeric"),
                "rate.min"              => __("api.rate_min"),
                "rate.max"              => __("api.rate_max"),
                "review_comment.string" => __("api.review_comment_string"),

            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'done'){
            return ResponsesHelper::returnError('400','');
        }

        if (! OrderReview::checkIfUserReviewOrder($request->order_id, $user['user']->user_id ) ){
            return ResponsesHelper::returnError('400', __('api.you_can_not_review_now'));
        }

        Order::updateRatedStatusOfOrder($request->order_id);

        $review = OrderReview::addOrderReview($request, $user['user']->user_id);
        return ResponsesHelper::returnData(['order_review_id' => $review], '200', __('api.review_added_successfully'));
    }

    public function changePaymentMethodOfOrder(Request $request)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $rules = [
            "order_id"          => "required|numeric|exists:orders,order_id",
            "payment_method_id" => "required|numeric|exists:payment_methods,payment_method_id",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "order_id.required"          => __("api.order_id_required"),
                "order_id.numeric"           => __("api.order_id_numeric"),
                "order_id.exists"            => __("api.order_id_exists"),
                "payment_method_id.required" => __("api.payment_method_id_required"),
                "payment_method_id.numeric"  => __("api.payment_method_id_numeric"),
                "payment_method_id.exists"   => __("api.payment_method_id_exists"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'accepted'){
            return ResponsesHelper::returnError('400','');
        }


        $orderPaymentMethod = Order::changePaymentMethodOfOrder($request, $user['user']->user_id);

        return ResponsesHelper::returnData($orderPaymentMethod['data'], $orderPaymentMethod['code'], $orderPaymentMethod['msg']);

    }

    public function cancelOrderOfUser(Request $request, $orderId)
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

        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400', __('api.not_have_permission_to_do_process'));
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'pending'){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        Order::changeStatusOfOrder('canceled', $orderId);

        // refund money if it paid
        $this->refundOrderCost($orderId);

        return ResponsesHelper::returnData([], '200', __('api.order_canceled_successfully'));
    }

    public function getSuggestedDatesOfOrder(Request $request, $orderId)
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

        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400', __('api.not_have_permission_to_do_process'));
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'reschedule'){
            return ResponsesHelper::returnError('400', __('api.can_not_get_suggested_dates'));
        }

        $suggestedDates = Order::getSuggestedDatesOfOrderOfUser($request->order_id);


        if (is_null($suggestedDates)){
            return ResponsesHelper::returnError('400', __('api.can_not_get_suggested_dates'));
        }

        $data['suggested_dates'] = json_decode($suggestedDates->suggested_date_by_vendor,true);

        $data['reschedule_reason_msg'] = $suggestedDates->reschedule_reason_msg;

        return ResponsesHelper::returnData($data, '200', '');
    }

    public function changeOrderDate(Request $request)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400', __('api.you_are_not_user'));
        }

        $rules = [
            "order_id" => "required|numeric|exists:orders,order_id",
            "date"     => "required|date",
            "time"     => "required|date_format:H:i:s",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "order_id.required" => __("api.order_id_required"),
                "order_id.numeric"  => __("api.order_id_numeric"),
                "order_id.exists"   => __("api.order_id_exists"),
                "date.required"     => __("api.date_required"),
                "date.date"         => __("api.date_should_date"),
                "time.required"     => __("api.time_required"),
                "time.date_format"  => __("api.time_date_format"),

            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        if (!Order::checkIfUserHaveOrder($request->order_id, $user['user']->user_id)){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status !=  'reschedule'){
            return ResponsesHelper::returnError('400',__('api.can_not_change_date'));
        }


        Order::changeOrderDate($request, $user['user']->user_id);

        Order::changeStatusOfOrder('pending', $request->order_id);

        return ResponsesHelper::returnData([], '200', __('api.order_date_updated'));
    }

}
