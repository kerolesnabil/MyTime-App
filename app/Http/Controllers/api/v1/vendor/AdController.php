<?php

namespace App\Http\Controllers\api\v1\vendor;

use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{

    public function getCreateAds()
    {
        $cost=Setting::getCostOfAds();

       return  ResponsesHelper::returnData($cost,'200');

    }

    public function saveAd(Request $request,$id=null)
    {


        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }
        if(isset($id)) {
            $ad = Ad::getAd($id);
            if (empty($ad)){
                return ResponsesHelper::returnError('400',__('vendor.not_found'));
            }

            $request->request->add(['ad_id' => $id]);
            $rules= [
                "ad_at_location"        =>"required",
                "ad_days"               => "required|integer",
                "ad_title"              => "required|string",
                "ad_start_at"           => "required|date",
                'ad_img'                => 'nullable|image|mimes:jpg,jpeg,png|max:1000',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return ResponsesHelper::returnValidationError('400', $validator);
            }
        }else{
            $rules= [
                "ad_at_location"        =>"required",
                "ad_days"               => "required|integer",
                "ad_title"              => "required|string",
                "ad_start_at"           => "required|date",
                'ad_img'                => 'required|image|mimes:jpg,jpeg,png|max:1000',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return ResponsesHelper::returnValidationError('400', $validator);
            }
        }



        $request->request->add(['vendor_id'=>Auth::user()->user_id]);

        if($request->ad_at_location=='ad_in_homepage'){
            $type='ad_in_homepage';
            $request->request->add(['ad_at_homepage' => true]);
            $request->request->add(['ad_at_discover_page' => false]);
        }
        if ($request->ad_at_location=='add_in_discover_page'){
            $type='add_in_discover_page';
            $request->request->add(['ad_at_homepage' => false]);
            $request->request->add(['ad_at_discover_page' => true]);
        }
        $request->request->remove('ad_at_location');

        $cost=Setting::calculateCost($type,$request->ad_days);
        $date = Carbon::parse($request->ad_start_at)->addDay($request->ad_days);

        $request->request->add(['ad_end_at' => $date->toDateString()]);
        $request->request->add(['ad_cost' => $cost]);

        $dataArr = $request->all();
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

        return ResponsesHelper::returnData((isset($id)? (int)$id: $ad->ad_id),'400',__('vendor.save_data'));

    }

    public function getAd(Request $request,$id)
    {
        $request->request->add(['ad_id' => $id]);

        $rules=[
            'ad_id' =>"required|exists:ads,ad_id"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $ad=Ad::getAd($id);

        return ResponsesHelper::returnData($ad,'200');


    }

    public function deleteAd(Request $request,$id)
    {
        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }
        $request->request->add(['ad_id' => $id]);

        $rules = [
            "ad_id"     => "required|exists:ads,ad_id",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        Ad::deleteAd($id);

        return ResponsesHelper::returnSuccessMessage(__('vendor.delete_data'),'200');

    }
}
