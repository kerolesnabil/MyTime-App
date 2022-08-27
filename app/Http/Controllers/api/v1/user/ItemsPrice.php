<?php
/**
 * Created by PhpStorm.
 * User: boiar
 * Date: 18/08/22
 * Time: 01:47 م
 */

namespace App\Http\Controllers\api\v1\user;


use App\Helpers\ResponsesHelper;
use App\Models\Coupon;
use App\Models\OrderCart;
use App\Models\Setting;
use App\Models\VendorServices;

trait ItemsPrice
{

    public static function getItemsPriceInCart($userId)
    {

        $orderCart = OrderCart::showOrderCart($userId);

        if (empty($orderCart)){
            return ResponsesHelper::returnError('400','There are no items in the cart');
        }

        $data = [];
        foreach ($orderCart as $key => $item){
            $data['service_type'] = $item['service_location'];
            $data['vendor_id'] = $item['vendor_id'];
            $data['vendor_services_ids'][$key] = $item['vendor_service_id'];
        }


        $servicesPrice = collect(VendorServices::servicesPricingDataByIds($data['vendor_services_ids'], $data['vendor_id']));


        $cartItems = [];

        foreach ($orderCart as $key => $item)
        {
            $servicePrice = $servicesPrice->firstWhere('vendor_service_id', $item['vendor_service_id']);


            $cartItems[$key]['order_cart_item_id']                  = $item['order_cart_item_id'];
            $cartItems[$key]['vendor_service_id']                   = $item['vendor_service_id'];
            $cartItems[$key]['service_location']                    = $data['service_type'];
            $cartItems[$key]['service_name']                        = $item['service_name'];
            $cartItems[$key]['service_type']                        = $item['service_type'];
            $cartItems[$key]['service_quantity']                    = $item['service_quantity'];
            $cartItems[$key]['service_price_before_discount']       = $servicePrice["service_price_at_" . $data['service_type']];
            $cartItems[$key]['service_price_after_discount']        = $servicePrice["service_discount_price_at_" . $data['service_type']];
            $cartItems[$key]['service_total_price_before_discount'] = $cartItems[$key]['service_price_before_discount'] * $cartItems[$key]['service_quantity'];
            $cartItems[$key]['service_total_price_after_discount']  = $cartItems[$key]['service_price_after_discount'] * $cartItems[$key]['service_quantity'];
            $cartItems[$key]['vendor_id']                           = $item['vendor_id'];
            $cartItems[$key]['vendor_name']                         = $item['vendor_name'];
            $cartItems[$key]['vendor_logo']                         = $item['vendor_logo'];
        }

        return $cartItems;
    }

    public static function getTotalPriceOfItems($userId, $coupon = null)
    {
        $cartItems = self::getItemsPriceInCart($userId);
        if (is_object($cartItems)){
            return $cartItems;
        }


        $itemsPriceDetails = [];
        $totalItemsPriceBeforeDiscount = 0;
        $totalItemsPriceAfterDiscount = 0;
        $serviceLocation ='';
        foreach ($cartItems as $key => $item)
        {

            $itemsPriceDetails[$key]['vendor_id']                           = $item['vendor_id'];
            $itemsPriceDetails[$key]['vendor_name']                         = $item['vendor_name'];
            $itemsPriceDetails[$key]['order_cart_item_id']                  = $item['order_cart_item_id'];
            $itemsPriceDetails[$key]['vendor_service_id']                   = $item['vendor_service_id'];
            $itemsPriceDetails[$key]['service_name']                        = $item['service_name'];
            $itemsPriceDetails[$key]['service_type']                        = $item['service_type'];
            $itemsPriceDetails[$key]['service_location']                    = $item['service_location'];
            $itemsPriceDetails[$key]['service_quantity']                    = $item['service_quantity'];
            $itemsPriceDetails[$key]['service_price']                       = $item['service_price_before_discount'];
            $itemsPriceDetails[$key]['service_discount']                    = $item['service_price_after_discount'];
            $itemsPriceDetails[$key]['service_total_price_before_discount'] = $item['service_total_price_before_discount'];
            $itemsPriceDetails[$key]['service_total_price_after_discount']  = $item['service_total_price_after_discount'];
            $serviceLocation                                                 = $itemsPriceDetails[$key]['service_location'];
            $totalItemsPriceBeforeDiscount                                  += $itemsPriceDetails[$key]['service_total_price_before_discount'];
            $totalItemsPriceAfterDiscount                                   += $itemsPriceDetails[$key]['service_total_price_after_discount'];
        }

        $data['service_location']                        = $serviceLocation;
        $tax                                             = Setting::getTax();
        $data['order_taxes_rate']                        = $tax['tax_rate'];
        $data['order_total_items_price_before_discount'] = $totalItemsPriceBeforeDiscount;


        if (!is_null($coupon)){
            if ($coupon['status'] != false)
            {
                $data['coupon_id'] = $coupon['coupon_id'];
                if ($coupon['coupon_type'] == 'value'){

                    $data['order_total_items_price_after_discount'] = $totalItemsPriceAfterDiscount - $coupon['coupon_value'];
                }
                else {
                    $data['order_total_items_price_after_discount'] = $totalItemsPriceAfterDiscount - ($totalItemsPriceAfterDiscount * $coupon['coupon_value']);
                }
            }
            else{
                $data['order_total_items_price_after_discount'] = $totalItemsPriceAfterDiscount;
            }

        }
        else {
            $data['order_total_items_price_after_discount'] = $totalItemsPriceAfterDiscount;
        }

        $data['order_total_discount'] = $data['order_total_items_price_before_discount'] - $data['order_total_items_price_after_discount'];
        $data['order_taxes_cost']     = $data['order_total_items_price_after_discount'] * $tax['tax_value'];
        $data['order_total_price']    = $data['order_total_items_price_after_discount'] + $data['order_taxes_cost'];
        $data['items_price_details']  = $itemsPriceDetails;

        return ResponsesHelper::returnData($data, '200', '');
    }

}