<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\OrderCart;
use App\Models\VendorServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class OrderCartController extends Controller
{
    use ItemsPrice;

    public function showOrderCart()
    {
        $user['user'] = Auth::user();

        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', __('api.you_are_not_user'));
        }

        $cartItems = self::getItemsPriceInCart($user['user']->user_id);


        if (is_object($cartItems)){
            return $cartItems;
        }

        $data = [];
        foreach ($cartItems as $key => $item){


            $data[$key]['order_cart_item_id']                  = $item['order_cart_item_id'];
            $data[$key]['vendor_service_id']                   = $item['vendor_service_id'];
            $data[$key]['service_location']                    = $item['service_location'];
            $data[$key]['service_name']                        = $item['service_name'];
            $data[$key]['service_type']                        = $item['service_type'];
            $data[$key]['service_quantity']                    = $item['service_quantity'];
            $data[$key]['service_price_before_discount']       = $item['service_price_before_discount'];
            $data[$key]['service_price_after_discount']        = $item['service_price_after_discount'];
            $data[$key]['service_total_price_before_discount'] = strval($item['service_total_price_before_discount']);
            $data[$key]['service_total_price_after_discount']  = strval($item['service_total_price_after_discount']);
            $data[$key]['vendor_id']                           = $item['vendor_id'];
            $data[$key]['vendor_name']                         = $item['vendor_name'];
            $data[$key]['vendor_logo']                         = $item['vendor_logo'];
        }
        return ResponsesHelper::returnData($data, '200', '');
    }

    public function addItemToOrderCart(Request $request)
    {
        $user['user'] = Auth::user();

        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', __('api.you_are_not_user'));
        }

        $rules = [
            "vendor_id"         => "required|numeric|exists:vendor_details,user_id",
            "service_location"  => "required|string",
            "vendor_service_id" => "required|numeric|exists:vendor_services,vendor_service_id",
            "service_quantity"  => "required|numeric",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!$request->service_location == "home" || !$request->service_location == "salon") {
            return ResponsesHelper::returnError('400', __('api.service_location_must_be_salon_or_home'));
        }


        if (!VendorServices::checkIfVendorHasService($request->vendor_id, $request->vendor_service_id)) {
            return ResponsesHelper::returnError('400', __('api.can_not_order_from_more_vendor'));
        }

        if (!VendorServices::checkIfServiceProvidedInSpecificLocation($request->vendor_id, $request->vendor_service_id, $request->service_location)){
            return ResponsesHelper::returnError('400', __('api.this_service_not_provided_location') ." ".$request->service_location);
        }

        $orderCartItem = OrderCart::checkIfItemAddedToCartPreviously($user['user']->user_id, $request->vendor_id, $request->vendor_service_id, $request->service_location);
        if (is_object($orderCartItem)) {
            // update count
            $itemNewQuantity = $orderCartItem->service_quantity + $request->service_quantity;
            OrderCart::updateItemQuantityInCart($orderCartItem->order_cart_id, $itemNewQuantity);
            return ResponsesHelper::returnData([],200,__('api.service_added_successfully_to_cart'));

        }

        $serviceLocationOfCart = OrderCart::getVendorAndServicesLocationInCart($user['user']->user_id);

        if (is_object($serviceLocationOfCart)){
            if($request->service_location !== $serviceLocationOfCart->service_location){
                return ResponsesHelper::returnError('400', __('api.services_can_not_order_in_more_location'));
            }

            if($request->vendor_id !== $serviceLocationOfCart->vendor_id){
                return ResponsesHelper::returnError('400', __('api.can_not_order_from_more_vendor'));
            }
        }

        return OrderCart::addItemsToOrderCart($user['user']->user_id, $request->vendor_id, $request->vendor_service_id, $request->service_location, $request->service_quantity);
    }

    public function deleteItemFromCart(Request $request, $orderCartItemId)
    {
        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', __('api.you_are_not_user'));
        }

        $request->request->add(['order_cart_item_id' => $orderCartItemId]);
        $rules = [
            "order_cart_item_id"  => "required|numeric|exists:order_carts,order_cart_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (empty(OrderCart::checkIfUserHasItemInCart($user['user']->user_id, $orderCartItemId))){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        OrderCart::deleteItemFromCart($orderCartItemId);
        return ResponsesHelper::returnData([],'200', __('api.service_deleted_form_cart_successfully'));
    }

    public function deleteOrderCart()
    {
        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', __('api.you_are_not_user'));
        }



        if (OrderCart::deleteOrderCart($user['user']->user_id) == 0){
            return ResponsesHelper::returnError('400', __('api.cart_empty_nothing_to_delete'));
        }
        return ResponsesHelper::returnData([],'200', __('api.cart_successfully_emptied'));
    }

    public function updateItemQtyInCart(Request $request)
    {
        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', __('api.you_are_not_user'));
        }

        $rules = [
            "order_cart_item_id" => "required|numeric|exists:order_carts,order_cart_id",
            "quantity"           => "required|numeric",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }



        if(empty(OrderCart::checkIfUserHasItemInCart($user['user']->user_id, $request->order_cart_item_id))){
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }

        OrderCart::updateItemQuantityInCart($request->order_cart_item_id, $request->quantity);
        return ResponsesHelper::returnData([],'200', __('api.item_qty_updated'));

    }

}
