<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;


class OrderCart extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "order_carts";
    protected $primaryKey = "order_cart_id";
    protected $guarded = ['order_cart_id'];
    protected $fillable = ['user_id', 'vendor_id','vendor_service_id', 'service_location', 'service_quantity'];


    public static function showOrderCart($userId)
    {

        $orderCartItems =
            self::query()
                ->select
                (
                    'order_carts.order_cart_id as order_cart_item_id',
                    'order_carts.vendor_service_id',
                    self::getValueWithSpecificLang('services.service_name', app()->getLocale(), 'service_name'),
                    'services.service_type',
                    'order_carts.service_quantity',
                    'order_carts.service_location',
                    'order_carts.vendor_id',
                    'users.user_name as vendor_name',
                    'users.user_img as vendor_logo'
                )
                ->join('vendor_services','vendor_services.vendor_service_id','=','order_carts.vendor_service_id')
                ->join('services', 'services.service_id', '=', 'vendor_services.service_id')
                ->join('users','users.user_id','=','order_carts.vendor_id')
                ->where('order_carts.user_id','=',$userId)
                ->get()->toArray();

        if (!empty($orderCartItems)){
            foreach ($orderCartItems as $key => $item){
                $orderCartItems[$key]['vendor_logo'] = ImgHelper::returnImageLink($item['vendor_logo']);
            }
        }

        return $orderCartItems;

    }

    public static function addItemsToOrderCart($userId, $vendorId, $serviceId, $serviceLocation, $serviceQty)
    {

        $cartItem= new OrderCart([
            'user_id'           => $userId,
            'vendor_id'         => $vendorId,
            'vendor_service_id' => $serviceId,
            'service_location'  => $serviceLocation,
            'service_quantity'  => $serviceQty
        ]);

        $cartItem->save();
        return ResponsesHelper::returnData([],200,'Service added successfully to cart');
    }

    public static function deleteItemFromCart($orderCartId)
    {

        self::destroy($orderCartId);
    }

    public static function checkIfItemAddedToCartPreviously($userId, $vendorId, $servicesId, $serviceLocation)
    {

        return self::query()
            ->select('vendor_service_id', 'order_cart_id', 'service_quantity')
            ->where('user_id', '=', $userId)
            ->where('vendor_id','=', $vendorId)
            ->where('service_location', '=', $serviceLocation)
            ->where('vendor_service_id', $servicesId)
            ->first();
    }


    public static function updateItemQuantityInCart($order_cart_id, $itemQuantity)
    {
        self::where('order_cart_id', '=', $order_cart_id)
            ->update(array(
                'service_quantity'    => $itemQuantity,
            ));
    }

    public static function getVendorAndServicesLocationInCart($userId)
    {
        return self::query()
            ->select('service_location', 'vendor_id')
            ->where('user_id', '=', $userId)
            ->first();
    }

    public static function deleteOrderCart($userId)
    {

        return self::query()->where('user_id', $userId)->delete();
    }

    public static function checkIfUserHasItemInCart($userId, $cartItemIdOrderIds)
    {

        $cartItems =
            self::query()
                ->select('order_cart_id')
                ->where('user_id', '=', $userId);

        if (is_array($cartItemIdOrderIds)){
            $cartItems->whereIn('order_cart_id', $cartItemIdOrderIds);
        }
        else{

            $cartItems->where('order_cart_id', '=',$cartItemIdOrderIds);
        }

        return $cartItems->get()->toArray();
    }
}

