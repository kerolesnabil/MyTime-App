<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\VendorServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class PriceServiceController extends Controller
{

    public function getPriceOfOrderAndServices(Request $request)
    {

        return self::calculatePriceOfOrderAndServices($request);

    }

    public static function calculatePriceOfOrderAndServices(Request $request)
    {
        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', 'you are not a user');
        }

        $rules = [
            "service_type"                => "required",
            "vendor_id"                   => "required|numeric|exists:users,user_id",
            "order_items.*.service_id"    => "required|numeric|exists:vendor_services,service_id|distinct",
            "order_items.*.service_count" => "required|numeric",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $couponObj                   = CouponController::couponVerification($request->coupon_code, $user['user']->user_id);
        $couponData['coupon_status'] = $couponObj['msg'];

        $servicesPrice = self::getPriceOfServicesAfterCount($request);

        if ($servicesPrice->getStatusCode() != 200) {
            return $servicesPrice;
        }

        $servicesPrice = json_decode($servicesPrice->content(), true);

        dd($servicesPrice);

        $servicesPrice = $servicesPrice["data"];
        $servicesPrice = collect($servicesPrice);

        $orderPrice['order_total_items_price_before_discount'] = $servicesPrice->sum(['item_total_price_before_discount']);
        $orderPrice['order_total_items_price_after_discount']  = $servicesPrice->sum(['item_total_price_after_discount']);
        $orderPrice['order_total_discount']                    = $orderPrice['order_total_items_price_before_discount'] - $orderPrice['order_total_items_price_after_discount'];


        if (!empty($couponObj['data'])) {
            if ($couponObj['data']['coupon_type'] == 'value') {

                $orderPrice['order_total_discount']                   += $couponObj['data']['coupon_value'];
                $orderPrice['order_total_items_price_after_discount'] -= $couponObj['data']['coupon_value'];
            }
            else {

                $orderPrice['order_total_discount']                   += ($orderPrice['order_total_items_price_after_discount'] * $couponObj['data']['coupon_value']);
                $orderPrice['order_total_items_price_after_discount'] -= ($orderPrice['order_total_items_price_after_discount'] * $couponObj['data']['coupon_value']);
            }

        }

        $orderPrice['order_taxes_rate']  = "10%";
        $orderPrice['order_taxes_cost']  = (intval($orderPrice['order_taxes_rate']) / 100) * $orderPrice['order_total_items_price_after_discount'];
        $orderPrice['order_total_price'] = $orderPrice['order_total_items_price_after_discount'] + $orderPrice['order_taxes_cost'];


        $orderPrice["order_total_items_price_before_discount"] = number_format($orderPrice["order_total_items_price_before_discount"], 2);
        $orderPrice["order_total_items_price_after_discount"]  = number_format($orderPrice["order_total_items_price_after_discount"], 2);
        $orderPrice["order_total_discount"]                    = number_format($orderPrice["order_total_discount"], 2);
        $orderPrice["order_taxes_cost"]                        = number_format($orderPrice["order_taxes_cost"], 2);
        $orderPrice["order_total_price"]                       = number_format($orderPrice["order_total_price"], 2);

        $data['coupon']              = $couponData;
        $data['order_price_details'] = $orderPrice;

        $servicesPrice = $servicesPrice->all();
        foreach ($servicesPrice as $key => $item) {
            $servicesPrice[$key]['item_total_price_before_discount'] = number_format($servicesPrice[$key]['item_total_price_before_discount'], 2);
            $servicesPrice[$key]['item_total_price_after_discount']  = number_format($servicesPrice[$key]['item_total_price_after_discount'], 2);
        }
        $data['items_price_details'] = $servicesPrice;

        return ResponsesHelper::returnData($data);

    }

    private static function getPriceOfServicesAfterCount(Request $request)
    {

        $serviceIds = [];
        foreach ($request->order_items as $item) {
            $serviceIds = array_merge($serviceIds, explode(',', $item['service_id']));
        }


        $serviceObjs = VendorServices::servicesPricingDataByIds($serviceIds, $request->vendor_id);

        $serviceObjsIds = [];
        foreach ($serviceObjs as $serviceObj) {
            $serviceObjsIds = array_merge($serviceObjsIds, explode(',', $serviceObj['service_id']));
        }


        if (array_diff($serviceIds, $serviceObjsIds)) {
            return ResponsesHelper::returnError('400', 'You can not make an order from more than one vendor');
        }

        $servicePriceData = $serviceObjs;
        foreach ($serviceObjs as $key => $serviceObj) {
            foreach ($request->order_items as $item) {

                if ($serviceObj['service_id'] == $item['service_id']) {
                    $servicePriceData[$key]['service_count'] = $item['service_count'];

                    if ($request->service_type == 'salon') {

                        $servicePriceData[$key]['service_location']           = 'salon';
                        $servicePriceData[$key]['item_price_before_discount'] = $servicePriceData[$key]['service_price_at_salon'];

                        if (strval($servicePriceData[$key]['item_price_before_discount']) == 0 || is_null(strval($servicePriceData[$key]['item_price_before_discount']))) {
                            return ['data' => [], 'code' => '400', 'msg' => "Service id " . $item['service_id'] . " not served at salon"];
                        }
                        else {
                            $servicePriceData[$key]['item_price_after_discount']        = $servicePriceData[$key]['service_discount_price_at_salon'];
                            $servicePriceData[$key]['item_total_price_before_discount'] = $servicePriceData[$key]['service_price_at_salon'] * $servicePriceData[$key]['service_count'];
                            $servicePriceData[$key]['item_total_price_after_discount']  = $servicePriceData[$key]['service_discount_price_at_salon'] * $servicePriceData[$key]['service_count'];
                        }
                    }
                    elseif ($request->service_type == 'home') {

                        $servicePriceData[$key]['service_location']           = 'home';
                        $servicePriceData[$key]['item_price_before_discount'] = $servicePriceData[$key]['service_price_at_home'];

                        if (strval($servicePriceData[$key]['item_price_before_discount']) == 0 || is_null(strval($servicePriceData[$key]['item_price_before_discount']))) {
                            return ['data' => [], 'code' => '400', 'msg' => "Service id " . $item['service_id'] . " not served at home"];
                        }
                        else {
                            $servicePriceData[$key]['item_price_after_discount']        = $servicePriceData[$key]['service_discount_price_at_home'];
                            $servicePriceData[$key]['item_total_price_before_discount'] = $servicePriceData[$key]['service_price_at_home'] * $servicePriceData[$key]['service_count'];
                            $servicePriceData[$key]['item_total_price_after_discount']  = $servicePriceData[$key]['service_discount_price_at_home'] * $servicePriceData[$key]['service_count'];
                        }
                    }
                    unset($servicePriceData[$key]['service_price_at_salon']);
                    unset($servicePriceData[$key]['service_discount_price_at_salon']);
                    unset($servicePriceData[$key]['service_price_at_home']);
                    unset($servicePriceData[$key]['service_discount_price_at_home']);
                }
            }
        }

        return ResponsesHelper::returnData($servicePriceData);

    }


}
