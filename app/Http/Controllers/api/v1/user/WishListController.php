<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\VendorServices;
use App\Models\WishList;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class WishListController extends Controller
{


    public function showWishListOfUser()
    {
        $user['user']=Auth::user();

        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $wishList = WishList::showWishListOfUser($user['user']->user_id);

        if (empty($wishList)){
            ResponsesHelper::returnError('400', __('api.wish_list_is_empty'));
        }

        $data = [];

        foreach ($wishList as $key => $item){

            $serviceLocation                            = $item['service_location'];
            $data[$key]['wish_list_id']                 = $item['wish_list_id'];
            $data[$key]['vendor_id']                    = $item['vendor_id'];
            $data[$key]['vendor_service_id']            = $item['service_id'];
            $data[$key]['service_name']                 = $item['service_name'];
            $data[$key]['service_location']             = $serviceLocation;
            $data[$key]["service_price"]                = $item["service_price_at_$serviceLocation"];
            $data[$key]["service_price_after_discount"] = $item["service_discount_price_at_$serviceLocation"];

        }


        return ResponsesHelper::returnData($data, '200', '');

    }

    public function addServiceToWishListOfUser(Request $request)
    {

        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $rules = [
            "vendor_service_id" => "required|numeric|exists:vendor_services,vendor_service_id",
            "vendor_id"         => "required|numeric|exists:vendor_details,user_id",
            "service_location"  => "required|string"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!$request->service_location == "home" || !$request->service_location == "salon") {
            return ResponsesHelper::returnError('400', __('api.service_location_must_be_salon_or_home'));
        }

        if (!VendorServices::checkIfVendorHasService($request->vendor_id, $request->vendor_service_id)){
            return ResponsesHelper::returnData([],'400',"");
        }

        if (!VendorServices::checkIfServiceProvidedInSpecificLocation($request->vendor_id, $request->vendor_service_id, $request->service_location)){
            return ResponsesHelper::returnError('400', __('api.this_service_not_provided_location') ." ". $request->service_location);
        }


        $data = WishList::addItemToWishList($user['user']->user_id, $request->vendor_id, $request->vendor_service_id, $request->service_location);


        return ResponsesHelper::returnData([], $data['code'], $data['msg']);


    }

    public function deleteServiceFromWishListOfUser(Request $request, $wishListItemId)
    {
        $user['user']=Auth::user();
        if($user['user']->user_type!='user'){
            return ResponsesHelper::returnError('400',__('api.you_are_not_user'));
        }

        $request->request->add(['wish_list_item_id' => $wishListItemId]);
        $rules = [
            "wish_list_item_id"  => "required|numeric|exists:wish_list,wish_list_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!WishList::checkIfUserHasSpecificWishListItem($user['user']->user_id, $request->wish_list_item_id))
        {
            return ResponsesHelper::returnError('400',__('api.not_have_permission_to_do_process'));
        }


        WishList::deleteItemFromWishList($request->wish_list_item_id);

        return ResponsesHelper::returnData([], '200', __('api.deleted_successfully'));

    }



}
