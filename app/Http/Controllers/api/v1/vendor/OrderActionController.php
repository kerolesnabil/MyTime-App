<?php

namespace App\Http\Controllers\api\v1\vendor;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderRejection;
use App\Models\OrderRejectionReason;
use App\Models\OrderReview;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class OrderActionController extends Controller
{

    public function rescheduleOrderDate(Request $request)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',trans('api.not_vendor'));
        }

        $rules = [
            "order_id"               => "required|numeric|exists:orders,order_id",
            "reschedule_reason_msg"  => "string",
            "suggested_dates.*.date" => "required|date",
            "suggested_dates.*.time" => "required|date_format:H:i:s"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if(!Order::checkIfVendorHaveOrder($request->order_id,$vendor['vendor']->user_id)){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status != 'pending') {
            return ResponsesHelper::returnError('400',__('api.can_not_reschedule_order'));
        }

        $data['order_id']              = $request->order_id;
        $data['reschedule_reason_msg'] = $request->reschedule_reason_msg;
        $data['suggested_dates']       = json_encode($request->suggested_dates);




        Order::changeStatusOfOrder('reschedule', $request->order_id);
        Order::createSuggestedDates($data);

        return ResponsesHelper::returnData([],'200',__('api.rescheduled_successfully'));
    }

    public function rejectOrder(Request $request)
    {

        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',trans('api.not_vendor'));
        }

        $rules = [
            "order_id"            => "required|numeric|exists:orders,order_id",
            "rejection_reason_id" => "required|numeric|exists:order_rejections_reasons,rejection_reason_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if(!Order::checkIfVendorHaveOrder($request->order_id,$vendor['vendor']->user_id)){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        $orderStatus = Order::getOrderStatus($request->order_id);

        if ($orderStatus->order_status != 'pending') {
            return ResponsesHelper::returnError('400',__('api.can_not_reject_order'));
        }


        $data['order_id']         = $request->order_id;
        $data['rejection_reason'] = $request->rejection_reason_id;
        $data['created_at']       = now();
        $data['updated_at']       = now();

        OrderRejection::createOrderRejectionReasons($data);
        Order::changeStatusOfOrder('rejected', $request->order_id);

        return ResponsesHelper::returnData([],'200',__('api.order_successfully_rejected'));
    }

    public function acceptOrder(Request $request, $orderId)
    {

        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $request->request->add(['order_id' => $orderId]);
        $rules = [
            "order_id" => "required|numeric|exists:orders,order_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnError('400', __('api.order_id_invalid'));
        }

        if(!Order::checkIfVendorHaveOrder($orderId, $vendor['vendor']->user_id)){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        $orderStatus = Order::getOrderStatus($orderId);

        if ($orderStatus->order_status != 'pending') {
            return ResponsesHelper::returnError('400',__('api.can_not_accept_order'));
        }

        Order::changeStatusOfOrder('accepted', $orderId);
        return ResponsesHelper::returnSuccessMessage(__('api.order_accepted_successfully'), '200');
    }

    public function doneOrder(Request $request, $orderId)
    {

        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400', __('api.not_vendor'));
        }

        $request->request->add(['order_id' => $orderId]);
        $rules = [
            "order_id" => "required|numeric|exists:orders,order_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if(!Order::checkIfVendorHaveOrder($orderId, $vendor['vendor']->user_id)){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        $orderStatus = Order::getOrderStatus($orderId);

        if ($orderStatus->order_status != 'accept') {
            return ResponsesHelper::returnError('400',__('api.can_not_done_order'));
        }


        // @TODO Mass3ood

       // make order done
       // process on app profit

    }

    public function getAllOrderRejectionReasons()
    {
        $allReasons = OrderRejectionReason::getAllReasons();

        if(empty($allReasons)){
            return ResponsesHelper::returnData($allReasons, '200', __('api.no_reasons_for_rejection'));
        }
        return ResponsesHelper::returnData($allReasons, '200', '');
    }

}
