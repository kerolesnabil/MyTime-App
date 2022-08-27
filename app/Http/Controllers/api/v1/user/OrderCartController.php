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
            return ResponsesHelper::returnError('400', 'you are not a user');
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
            return ResponsesHelper::returnError('400', 'you are not a user');
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
            return ResponsesHelper::returnError('400', 'Service location must be salon or home');
        }


        if (!VendorServices::checkIfVendorHasService($request->vendor_id, $request->vendor_service_id)) {
            return ResponsesHelper::returnError('400', 'It is not possible to order from more than one vendor');
        }

        if (!VendorServices::checkIfServiceProvidedInSpecificLocation($request->vendor_id, $request->vendor_service_id, $request->service_location)){
            return ResponsesHelper::returnError('400', "This service is not provided in $request->service_location, it cannot be added to the cart");
        }

        $orderCartItem = OrderCart::checkIfItemAddedToCartPreviously($user['user']->user_id, $request->vendor_id, $request->vendor_service_id, $request->service_location);
        if (is_object($orderCartItem)) {
            // update count
            $itemNewQuantity = $orderCartItem->service_quantity + $request->service_quantity;
            OrderCart::updateItemQuantityInCart($orderCartItem->order_cart_id, $itemNewQuantity);
            return ResponsesHelper::returnData([],200,'Service added successfully to cart');

        }

        $serviceLocationOfCart = OrderCart::getVendorAndServicesLocationInCart($user['user']->user_id);

        if (is_object($serviceLocationOfCart)){
            if($request->service_location !== $serviceLocationOfCart->service_location){
                return ResponsesHelper::returnError('400', 'Services cannot be order in more than one location');
            }

            if($request->vendor_id !== $serviceLocationOfCart->vendor_id){
                return ResponsesHelper::returnError('400', 'Services cannot be ordered from more than one vendor');
            }
        }

        return OrderCart::addItemsToOrderCart($user['user']->user_id, $request->vendor_id, $request->vendor_service_id, $request->service_location, $request->service_quantity);
    }

    public function deleteItemFromCart(Request $request, $orderCartItemId)
    {
        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', 'you are not a user');
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
            return ResponsesHelper::returnError('400','This user does not have permission to remove this service from the cart');
        }

        OrderCart::deleteItemFromCart($orderCartItemId);
        return ResponsesHelper::returnData([],'200', 'Service deleted form cart successfully');
    }

    public function deleteOrderCart()
    {
        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', 'you are not a user');
        }



        if (OrderCart::deleteOrderCart($user['user']->user_id) == 0){
            return ResponsesHelper::returnError('400', 'The cart is empty, there is nothing to delete');
        }
        return ResponsesHelper::returnData([],'200', 'Order cart successfully');
    }

    public function updateItemQtyInCart(Request $request)
    {
        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', 'you are not a user');
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
            return ResponsesHelper::returnError('400','This user does not have permission to remove this services from the cart');
        }

        OrderCart::updateItemQuantityInCart($request->order_cart_item_id, $request->quantity);
        return ResponsesHelper::returnData([],'200', 'Item quantity Updated');

    }

}
