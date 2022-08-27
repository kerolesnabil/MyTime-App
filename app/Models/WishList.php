<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;


class WishList extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "wish_list";
    protected $primaryKey = "wish_list_id";
    protected $guarded = ['wish_list_id'];
    protected $fillable = ['user_id', 'vendor_id','service_id','service_location'];


    public static function showWishListOfUser($userId)
    {
        $wishList=
            self::query()
                ->select
                (
                    'wish_list.wish_list_id',
                    'wish_list.vendor_id',
                    'wish_list.service_id',
                    self::getValueWithSpecificLang('services.service_name', app()->getLocale(), 'service_name'),
                    'wish_list.service_location',
                    'service_price_at_salon',
                    'service_discount_price_at_salon',
                    'service_price_at_home',
                    'service_discount_price_at_home'
                )
                ->join('vendor_services','vendor_services.vendor_service_id','wish_list.service_id')
                ->join('services', 'services.service_id', '=', 'vendor_services.service_id')
                ->where('wish_list.user_id','=',$userId)
                ->get()->toArray();
        return $wishList;
    }

    public static function addItemToWishList($userId, $vendorId, $serviceId, $serviceLocation)
    {
        $wishList = self::checkIfWishItemsPreviously($userId, $vendorId,$serviceId);
        if (!$wishList['status']){
            return ['msg' => 'This service has already been added to your wish list', 'code' => '400'];
        }

        $wishListItem = new WishList(['user_id'=> $userId, 'vendor_id' => $vendorId,'service_id'=>$serviceId, 'service_location' => $serviceLocation]);
        $wishListItem->save();
        return ['msg' => 'Service added successfully to the wish list', 'code' => '200'];
    }

    public static function deleteItemFromWishList($wishListItemId)
    {
        self::query()->where('wish_list_id', $wishListItemId)->delete();
    }

    private static function checkIfWishItemsPreviously($userId, $vendorId, $serviceId)
    {
        $wishListId =
            self::query()
                ->select('wish_list_id')
                ->where('user_id', '=', $userId)
                ->where('vendor_id','=', $vendorId)
                ->where('service_id','=', $serviceId)
                ->get();

        if(count($wishListId) == 0){

            return ['status' => true, 'wish_list_id' => []];
        }
        return ['status' => false, 'wish_list_id' => $wishListId];

    }

    public static function checkIfUserHasSpecificWishListItem($userId, $wishListItemId)
    {
        $wishListId =
            self::query()
                ->select('wish_list_id')
                ->where('user_id', '=', $userId)
                ->where('wish_list_id','=', $wishListItemId)
                ->first();

        if(is_object($wishListId)){
            return true;
        }
        return false;


    }
}
