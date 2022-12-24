<?php

namespace App\Http\Controllers\api\v1\vendor;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\SuggestedServices;
use App\Models\VendorServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    // unused
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


    public function getMainCategoriesOfServices(Request $request)
    {
        $user=Auth::user();
        if( $user->user_type != 'vendor')
        {
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $data = Category::mainCategories();


        return ResponsesHelper::returnData($data,'200','');
    }

    public function getSubCategoriesOfServices(Request $request, $parenId)
    {
        $user=Auth::user();
        if( $user->user_type != 'vendor')
        {
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $request->request->add(['parent_id' => $parenId]);
        $rules = [
            "parent_id"=> "required|exists:categories,cat_id",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "parent_id.required"            => __("api.parent_id_required"),
                "parent_id.numeric"             => __("api.parent_id_numeric"),
                "parent_id.exists"              => __("api.parent_id_exists"),
            ]
        );
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $data = Category::getSubCategoriesMainCatId($parenId);


        if (!empty($data)){
            foreach ($data as $item){
                unset($item['cat_img']);
            }
        }

        return ResponsesHelper::returnData($data,'200','');
    }


    public function getServicesByCatId(Request $request, $catId)
    {

        $user=Auth::user();
        if( $user->user_type!='vendor')
        {
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $request->request->add(['cat_id' => $catId]);
        $rules = [
            "cat_id"=> "required|numeric|exists:categories,cat_id",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'cat_id.required'         => __('api.cat_id_required'),
                'cat_id.numeric'          => __('api.cat_id_numeric'),
                'cat_id.exists'           => __('api.cat_id_exists'),
            ]
        );
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $data = Service::getServicesByCatId($catId);

        return ResponsesHelper::returnData($data,'200','');

    }


    public function saveService(Request $request,$vendor_service_id=null)
    {

        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        if(isset($vendor_service_id))
        {
            $service= VendorServices::getServiceById($vendor_service_id);

            if (empty($service)){
                return ResponsesHelper::returnError('400',__('api.not_vendor'));
            }
            if($service->vendor_id!=Auth::user()->user_id)
            {
                return ResponsesHelper::returnError('400',__('api.this_service_is_not_for_vendor'));
            }
        }

        $rules = [
            "service_id"                        => "required|exists:services,service_id",
            "service_title"                     => "string",
            "service_price_at_salon"            => "nullable|numeric",
            "service_discount_price_at_salon"   => "nullable|numeric",
            "service_price_at_home"             => "numeric|required",
            "service_discount_price_at_home"    => "numeric|nullable",
        ];


        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "service_id.required"                     => __("api.service_id_required"),
                "service_id.numeric"                      => __("api.service_id_numeric"),
                "service_id.exists"                       => __("api.service_id_exists"),
                "service_title.string"                    => __("api.service_title_string"),
                "service_price_at_salon.numeric"          => __("api.service_price_at_salon_numeric"),
                "service_discount_price_at_salon.numeric" => __("api.service_discount_price_at_salon_numeric"),
                "service_price_at_home.required"          => __("api.service_price_at_home_required"),
                "service_price_at_home.numeric"           => __("api.service_price_at_home_numeric"),
                "service_discount_price_at_home.required" => __("api.service_discount_price_at_home_required"),
                "service_discount_price_at_home.numeric"  => __("api.service_discount_price_at_home_numeric"),
            ]
        );
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
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $service= VendorServices::getServiceById($service_id);

        if (empty($service)){
            return ResponsesHelper::returnError('400',__('vendor.not_found'));
        }
        if($service->vendor_id!=Auth::user()->user_id)
        {
            return ResponsesHelper::returnError('400',__('api.this_service_is_not_for_vendor'));
        }

        $service = Service::getService($service->service_id, 'api');

        $service = array_merge(['service_id'=>(int)$service_id], $service);

       return ResponsesHelper::returnData($service,'200');
    }

    public function deleteService(Request $request, $vendor_service_id)
    {

        $vendor['vendor']=Auth::user();

        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $service= VendorServices::getServiceById($vendor_service_id);

        if (empty($service)){
            return ResponsesHelper::returnError('400',__('api.not_found'));
        }
        if($service->vendor_id!=Auth::user()->user_id)
        {
            return ResponsesHelper::returnError('400',__('api.this_service_is_not_for_vendor'));
        }

        VendorServices::deleteService($vendor_service_id);

        return ResponsesHelper::returnSuccessMessage(__('api.deleted_successfully'),'200');

    }

    public function getServicesOfVendor()
    {
        $vendor['vendor']=Auth::user();

        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
}

        $allServicesOfVendor = VendorServices::getAllServicesOfVendor($vendor['vendor']->user_id);

        return ResponsesHelper::returnData($allServicesOfVendor,'200');
    }

    public function savePackage(Request $request,$packageId=null)
    {
        $vendor['vendor']=Auth::user();

        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $rules = [
            "services_ids.*"                  => "required|exists:vendor_services,vendor_service_id",
            "service_price_at_salon"          => "nullable|numeric",
            "service_discount_price_at_salon" => "nullable|numeric",
            "service_price_at_home"           => "required|numeric",
            "service_discount_price_at_home"  => "nullable|numeric",
            "name_package"                    => "required",
        ];

        if(isset($packageId))
        {
            $request->request->add(['package_id' => $packageId]);
            $rules['package_id'] = "required|numeric|exists:services,service_id";
        }


        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "services_ids.*.exists"                   => __("api.services_ids_not_exists"),
                "services_ids.*.required"                 => __("api.services_ids_required"),
                "service_price_at_salon.numeric"          => __("api.package_price_at_salon_numeric"),
                "service_discount_price_at_salon.numeric" => __("api.package_discount_price_at_salon_numeric"),
                "service_price_at_home.required"          => __("api.package_price_at_home_required"),
                "service_price_at_home.numeric"           => __("api.package_price_at_home_numeric"),
                "service_discount_price_at_home.required" => __("api.package_discount_price_at_home_required"),
                "service_discount_price_at_home.numeric"  => __("api.package_discount_price_at_home_numeric"),
                "name_package.required"                   => __("api.name_package_required"),
                "package_id.required"                     => __("api.package_id_required"),
                "package_id.numeric"                      => __("api.package_id_numeric"),
                "package_id.exists"                       => __("api.package_id_exists"),
            ]
        );
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }
        DB::beginTransaction();
        if (!is_null($packageId)){
            $service=Service::savePackage($request->all(),$packageId);
            VendorServices::saveVendorPackage($request->all(), $packageId,'edit');
        }
        else{
            $service = Service::savePackage($request->all());
            VendorServices::saveVendorPackage($request->all(), $service->service_id, 'create');
        }
        DB::commit();

        return ResponsesHelper::returnData((isset($packageId)? intval($packageId) : (int) $service->service_id),'200',__('api.data_saved'));

    }

    public function getPackage(Request $request, $packageId)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $request->request->add(['package_id' => $packageId]);
        $rules=[
            'package_id' =>"required|exists:services,service_id"
        ];
        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "package_id.required" => __("api.package_id_required"),
                "package_id.numeric"  => __("api.package_id_numeric"),
                "package_id.exists"   => __("api.package_id_exists"),
            ]
        );
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $data = Service::getPackage($packageId);

        if(empty($data))
        {
            return ResponsesHelper::returnError('400', __('api.not_found'));
        }
        if(!isset($data->package_services_ids))
        {
            return ResponsesHelper::returnError('400',__('api.this_id_not_package'));
        }
        $servicesIds = explode(',',$data->package_services_ids);



        $servicesOfPackage =  VendorServices::getAllServicesOfVendor($vendor['vendor']->user_id, $servicesIds);

        $data->package_services_name= $servicesOfPackage;
        unset($data->package_services_ids);

        return ResponsesHelper::returnData($data,'200');


    }

    public function deletePackage(Request $request ,$packageId)
    {
        $vendor['vendor']=Auth::user();

        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $request->request->add(['package_id' => $packageId]);

        $rules = [
            "package_id" => "required|exists:services,service_id|exists:vendor_services,service_id",
        ];

        $validator = Validator::make(
            $request->all(), $rules,
            [
                "package_id.required"            => __("api.package_id_required"),
                "package_id.numeric"             => __("api.package_id_numeric"),
                "package_id.exists"              => __("api.package_id_exists"),
            ]
        );
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        DB::beginTransaction();
        VendorServices::deleteServiceOfPackage($request->package_id);
        Service::deletePackage($request->package_id);
        VendorServices::deleteServiceOfPackage($request->package_id);
        DB::commit();
        return ResponsesHelper::returnSuccessMessage(__('api.deleted_successfully'),'200');
    }

    public function getAllPackageOfVendor()
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $packages = collect(Service::getAllPackageByVendor($vendor['vendor']->user_id));

        $servicesIds = [];
        foreach ($packages as $package){
            $servicesIds = array_merge($servicesIds, explode(',' , $package['package_services_ids']));
        }

        $servicesIds  = array_unique($servicesIds);
        $serviceObjs = VendorServices::getAllServicesOfVendor($vendor['vendor']->user_id, $servicesIds);
        $serviceObjs = collect($serviceObjs);


        foreach ($packages as $key => $package) {

            if($package['service_discount_price_at_salon'] == 0.00 )
            {
                $package['service_discount_price_at_salon'] = "";
            }
            if($package['service_discount_price_at_home'] == 0.00)
            {
                $package['service_discount_price_at_home'] = "";
            }
            $package['package_services_ids']         = explode(',', trim($package['package_services_ids'], ','));
            $packages[$key]['package_services_name'] = collect($serviceObjs->whereIn("service_id", $package['package_services_ids']))->toArray();
            $packages[$key]['package_services_name'] = array_values($packages[$key]['package_services_name']);
            unset($package['package_services_ids']);
        }

        return ResponsesHelper::returnData($packages,'200');
    }

    public function addSuggestedService(Request $request)
    {
        $vendor['vendor'] = Auth::user();

        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $rules = [
            "main_cat_name" => "required|string",
            "sub_cat_name"  => "string",
            "service_name"  => "required|string",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "main_cat_name.required" => __("api.main_cat_name_required"),
                "main_cat_name.string"   => __("api.main_cat_name_string"),
                "sub_cat_name.string"    => __("api.sub_cat_name_string"),
                "service_name.required"  => __("api.service_name_required"),
                "service_name.string"    => __("api.service_name_string")
            ]
        );
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        SuggestedServices::createSuggestedService($request, $vendor['vendor']->user_id);

        return ResponsesHelper::returnData([],'200', __('api.data_saved'));

    }
}
