<?php

namespace App\Http\Controllers\api\v1\vendor;

use App\Events\CreateAd;
use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{

    public function getCreateAds()
    {
        $cost = Setting::getCostOfAds();

        $data = [];
        if (!empty($cost)){

            foreach ($cost as $item){

                if ($item['setting_key'] == 'price_ad_in_homepage'){
                    $data['price_ad_in_homepage'] = $item['setting_value'];
                }

                if ($item['setting_key'] == 'price_ad_in_discover_page'){
                    $data['price_ad_in_discover_page'] = $item['setting_value'];
                }
            }

        }

       return  ResponsesHelper::returnData($data,'200');

    }

    public function saveAd(Request $request,$id=null)
    {


        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }
        $rules= [
            "ad_at_location"        =>"required",
            "ad_days"               => "required|integer",
            "ad_title"              => "required|string",
            "ad_start_at"           => "required|date",
            'ad_img'                => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        if(isset($id)) {
            $ad = Ad::getAd($id);
            if (empty($ad)){
                return ResponsesHelper::returnError('400',__('vendor.not_found'));
            }
            if($ad->vendor_id!=Auth::user()->user_id)
            {
                return ResponsesHelper::returnError('400',__('vendor.This_ad_is_not_for_you'));
            }
            $request->request->add(['ad_id' => $id]);

        }


        $request->request->add(['vendor_id'=>Auth::user()->user_id]);

        if($request->ad_at_location=='ad_in_homepage'){
            $type='ad_in_homepage';
            $settingKey = 'price_ad_in_homepage';
            $request->request->add(['ad_at_homepage' => true]);
            $request->request->add(['ad_at_discover_page' => false]);
        }
        if ($request->ad_at_location == 'add_in_discover_page'){
            $type='ad_in_discover_page';
            $settingKey = 'price_ad_in_discover_page';
            $request->request->add(['ad_at_homepage' => false]);
            $request->request->add(['ad_at_discover_page' => true]);
        }
        if(!isset($type) && !isset($settingKey)){
            return ResponsesHelper::returnError('400', __('vendor.ad_at_location_not_found'));
        }
        $request->request->remove('ad_at_location');

        $cost = Setting::calculateCost($settingKey, $request->ad_days);
        $date = Carbon::parse($request->ad_start_at)->addDay($request->ad_days);

        $request->request->add(['ad_end_at' => $date->toDateString()]);
        $request->request->add(['ad_cost' => $cost]);

        $dataArr = $request->all();

        // check if vendor has the cost of ad in his wallet
        if (is_null($id)){
            $wallet = User::getUserWallet(Auth::user()->user_id);

            if ($wallet->user_wallet < $cost){
                return ResponsesHelper::returnData([],'400',__('vendor.wallet_amount_not_enough'));
            }
            else{

                event(new CreateAd(
                    Auth::user()->user_id,
                    $wallet->user_wallet,
                    $cost
                ));


            }
        }


        if(isset($id))
        {
            if(isset($request->ad_img))
            {
                $dataArr["ad_img"]=ImgHelper::uploadImage('images',$request->ad_img);
                $img=explode('/',$ad->ad_img);
                ImgHelper::deleteImage('images',$img[4]);
            }
        }else{
            $dataArr["ad_img"]=ImgHelper::uploadImage('images',$request->ad_img);
        }



        $ad=Ad::saveAd($dataArr);

        return ResponsesHelper::returnData((isset($id)? (int)$id: $ad->ad_id),'200',__('vendor.save_data'));

    }

    public function getAd(Request $request,$id)
    {


        $ad = Ad::getAd($id);

        if (empty($ad)){
            return ResponsesHelper::returnError('400',__('vendor.not_found'));
        }
        if($ad->vendor_id!=Auth::user()->user_id)
        {
            return ResponsesHelper::returnError('400',__('vendor.This_ad_is_not_for_you'));
        }

        return ResponsesHelper::returnData($ad,'200');


    }

    public function deleteAd(Request $request,$id)
    {
        $ad = Ad::getAd($id);

        if (empty($ad)){
            return ResponsesHelper::returnError('400',__('vendor.not_found'));
        }
        if($ad->vendor_id!=Auth::user()->user_id)
        {
            return ResponsesHelper::returnError('400',__('vendor.This_ad_is_not_for_you'));
        }

        Ad::deleteAd($id);

        return ResponsesHelper::returnSuccessMessage(__('vendor.delete_data'),'200');

    }
}
