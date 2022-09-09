<?php

namespace App\Http\Controllers\api\v1\vendor;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\VendorServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    public function getAllCategoriesServices()
    {
        $rootCategories = Category::getAllCategoriesTree();
        $allServices    = Service::getAllCategoriesServicesTree();
        $newCatsArr = [];

        foreach ($rootCategories["0"] as $parentId=>$parentCat){
            $childCats = isset($rootCategories[$parentCat["cat_id"]]) ? $rootCategories[$parentCat["cat_id"]] : [];
            $parentCat["child_cats"] = [];
            $parentCat["services"]   = isset($allServices[$parentCat["cat_id"]]) ? $allServices[$parentCat["cat_id"]] : [];;
            foreach ($childCats as $childId=>$child){
                $child["services"]         = isset($allServices[$child["cat_id"]]) ? $allServices[$child["cat_id"]] : [];;
                $parentCat["child_cats"][] = $child;
            }
            $newCatsArr[] = $parentCat;
        }

        return ResponsesHelper::returnData($newCatsArr,'200');
    }

    public function saveService(Request $request,$vendor_service_id=null)
    {

        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }

        if(isset($vendor_service_id))
        {
            $service= VendorServices::getServiceById($vendor_service_id);

            if (empty($service)){
                return ResponsesHelper::returnError('400',__('vendor.not_found'));
            }
            if($service->vendor_id!=Auth::user()->user_id)
            {
                return ResponsesHelper::returnError('400',__('vendor.This_service_is_not_for_you'));
            }
        }

        $rules = [
            "service_id"                        => "required|exists:services,service_id",
            "service_title"                     => "string",
            "service_price_at_salon"            => "nullable|numeric",
            "service_discount_price_at_salon"   => "nullable|numeric",
            "service_price_at_home"             => "numeric|required",
            "service_discount_price_at_home"    => "numeric|required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

       $data= VendorServices::saveVendorService($request->all(), Auth::user()->user_id);


        return ResponsesHelper::returnData((isset($vendor_service_id)? intval($vendor_service_id) : (int) $data->vendor_service_id),'200',__('vendor.save_data'));
    }

    public function showService(Request $request, $service_id)
    {
        $vendor['vendor']=Auth::user();

        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }

        $service= VendorServices::getServiceById($service_id);

        if (empty($service)){
            return ResponsesHelper::returnError('400',__('vendor.not_found'));
        }
        if($service->vendor_id!=Auth::user()->user_id)
        {
            return ResponsesHelper::returnError('400',__('vendor.This_service_is_not_for_you'));
        }

        $service= Service::getService($service->service_id);

        $service= array_merge(['service_id'=>(int)$service_id],$service);

       return ResponsesHelper::returnData($service,'200');
    }

    public function deleteService(Request $request ,$vendor_service_id)
    {

        $vendor['vendor']=Auth::user();

        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }

        $service= VendorServices::getServiceById($vendor_service_id);

        if (empty($service)){
            return ResponsesHelper::returnError('400',__('vendor.not_found'));
        }
        if($service->vendor_id!=Auth::user()->user_id)
        {
            return ResponsesHelper::returnError('400',__('vendor.This_service_is_not_for_you'));
        }

        VendorServices::deleteService($vendor_service_id);

        return ResponsesHelper::returnSuccessMessage(__('vendor.delete_data'),'200');

    }

    public function getServicesOfVendor()
    {
        $vendor['vendor']=Auth::user();

        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }

        $allServicesOfVendor=VendorServices::getAllServicesOfVendor($vendor['vendor']->user_id);
        $services=Service::getServicesOfVendor($allServicesOfVendor);

        return ResponsesHelper::returnData($services,'200');
    }


    public function savePackage(Request $request,$package_id=null)
    {

        if(isset($package_id))
        {
            $request->request->add(['package_id' => $package_id]);

            $rules=[
                'package_id' =>"required|exists:services,service_id"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return ResponsesHelper::returnValidationError('400', $validator);
            }
        }

        $rules = [
            "services_ids"=> "required|array",
            "services_ids.*"=>"required|exists:services,service_id",
            "service_price_at_salon"            => "nullable|numeric",
            "service_discount_price_at_salon"   => "nullable|numeric",
            "service_price_at_home"             => "numeric|required",
            "service_discount_price_at_home"    => "numeric|required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }
        DB::beginTransaction();
        $service=Service::savePackage($request->all(),$package_id);
        VendorServices::createVendorPackage($request->all(),isset($service->service_id)?$service->service_id:$package_id);
        DB::commit();

        return ResponsesHelper::returnData((isset($package_id)? intval($package_id) : (int) $service->service_id),'200',__('vendor.save_data'));

    }

    public function getPackage(Request $request, $package_id)
    {

        $request->request->add(['package_id' => $package_id]);

        $rules=[
            'package_id' =>"required|exists:services,service_id"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $data=Service::getPackage($package_id);

        $ids=explode(',',$data->package_services_ids);

        $getServicesName=Service::servicesNamesByIds($ids);
        $data->package_services_name= $getServicesName;

        return ResponsesHelper::returnData($data,'200');


    }

    public function deletePackage(Request $request ,$package_id)
    {
        $request->request->add(['package_id' => $package_id]);

        $rules = [
            "package_id"     => "required|exists:services,service_id",
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        DB::beginTransaction();
        Service::deletePackage($request->package_id);
        VendorServices::deleteServiceOfPackage($request->package_id);
        DB::commit();
        return ResponsesHelper::returnSuccessMessage(__('vendor.delete_data'),'200');
    }

    public function getAllPackageOfVendor()
    {
        $user=Auth::user();
        if( $user->user_type!='vendor')
        {
            return ResponsesHelper::returnError('400','yor are not a vendor');
        }


        $packges=Service::getAllPackageByVendor($user->user_id);

        $package_services_ids = array_map(function($item){
            return explode(",",$item);
        },$packges->pluck('package_services_ids')->toArray());

        $package_services_ids = collect($package_services_ids)->flatten(1)->all();
        $package_services_ids = array_unique($package_services_ids);
        $ids = array_diff($package_services_ids,[""]);

        $services=Service::getServicesOfVendor($ids);

        foreach ($packges as $packge) {
            $packge->package_services_ids  = explode(',', trim($packge->package_services_ids, ','));
            $packge->package_services_name = $services->whereIn("service_id", $packge->package_services_ids)->all();
        }

        return ResponsesHelper::returnData($packges,'200');
    }


}
