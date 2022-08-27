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
            return ResponsesHelper::returnError('400','you are not a vendor');
        }


        $listOfOrders = Order::listOfOrdersOfVendor($vendor['vendor']->user_id);

        if(empty($listOfOrders)){
            return ResponsesHelper::returnData($listOfOrders, '200', 'This vendor has no orders yet');
        }

        return ResponsesHelper::returnData($listOfOrders, '200', '');

    }

    public function getOrderDetailsOfVendor(Request $request, $orderId)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
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
            return ResponsesHelper::returnError('400','This vendor does not have this order');
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



}
