<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



class OrderItemController extends Controller
{
    use ItemsPrice;


    public static function createOrderItems($userId, $orderId)
    {
        $orderItemsData = self::getItemsPriceInCart($userId);

        $data = [];
        foreach ($orderItemsData as $key => $item)
        {
            $data[$key]['order_id']                         = $orderId;
            $data[$key]['item_id']                          = $item['vendor_service_id'];
            $data[$key]['item_type']                        = $item['service_type'];
            $data[$key]['item_price_before_discount']       = $item['service_price_before_discount'];
            $data[$key]['item_price_after_discount']        = $item['service_price_after_discount'];
            $data[$key]['item_count']                       = $item['service_quantity'];
            $data[$key]['item_total_price_before_discount'] = $item['service_total_price_before_discount'];
            $data[$key]['item_total_price_after_discount']  = $item['service_total_price_after_discount'];
            $data[$key]['created_at']  = now();
            $data[$key]['updated_at']  = now();

        }

        return OrderItem::createOrderItems($data);
    }



}
