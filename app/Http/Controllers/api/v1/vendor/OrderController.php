<?php

namespace App\Http\Controllers\api\v1\vendor;

use App\Helpers\ImgHelper;
use App\Helpers\OrderActionNamesHelper;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\CouponUsed;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class OrderController extends Controller
{


    public function getListOrdersOfVendor()
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }


        $listOfOrders = Order::listOfOrdersOfVendor($vendor['vendor']->user_id);

        if(empty($listOfOrders)){
            return ResponsesHelper::returnData($listOfOrders, '200', __('api.no_orders_yet'));
        }

        return ResponsesHelper::returnData($listOfOrders, '200', '');

    }

    public function getOrderDetailsOfVendor(Request $request, $orderId)
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
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!Order::checkIfVendorHaveOrder($orderId, $vendor['vendor']->user_id)){
            return ResponsesHelper::returnError('400',__('api.vendor_not_has_this_order'));
        }

        $detailsOfOrder = Order::getOrderDetailsOfVendor($vendor['vendor']->user_id, $orderId);


        $detailsOfOrder = collect($detailsOfOrder)->toArray();

        if(empty($detailsOfOrder)){

            return ResponsesHelper::returnError($detailsOfOrder['code'], $detailsOfOrder['msg']);
        }

        $userId = $detailsOfOrder['main_order_details']['user_id'];

        $detailsOfOrder['main_order_details']['user_img'] = ImgHelper::returnImageLink($detailsOfOrder['main_order_details']['user_img']);

        $detailsOfOrder['order_items'] = OrderItem::getItemsOfOrderByOrderId($orderId);

        $detailsOfOrder['used_coupon'] = CouponUsed::getUsedCoupon($userId, $orderId)->getData();



        return ResponsesHelper::returnData($detailsOfOrder, '200','');

    }


    public function getOrderByKeyword(Request $request)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }
        $rules = [
            "keyword" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        if (is_numeric($request->keyword)){
            $orderId = intval($request->keyword);
            $data = Order::getOrdersOfVendorByKeyWord($vendor['vendor']->user_id, $orderId);
        }
        else{

            $username = strval($request->keyword);
            $data = Order::getOrdersOfVendorByKeyWord($vendor['vendor']->user_id, null, $username);
        }


        $data = collect($data)->toArray();
        if (empty($data)){
            return ResponsesHelper::returnError( '400', __('api.not_found_data_for_this_keyword'));
        }

        return ResponsesHelper::returnData($data, '200', '');


    }


}
