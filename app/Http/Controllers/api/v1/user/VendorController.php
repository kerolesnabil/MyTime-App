<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\VendorServices;

use App\Models\VendorDetail;
use App\Models\OrderReview;
use App\Models\VendorView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class VendorController extends Controller
{


    public function getVendorDetail(Request $request, $vendorId)
    {

        $request->request->add(['vendor_id' => $vendorId]);
        $rules = [
            "vendor_id" => "required|numeric|exists:vendor_details,user_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        VendorView::incrementMonthlyVendorViews($vendorId);
        VendorDetail::incrementVendorViewsCount($vendorId);

        $vendorData['vendor_information']     = VendorDetail::VendorDetails($vendorId);
        $vendorData['vendor_main_categories'] = VendorServices::mainCategoriesOfVendor($vendorId);


        if (empty($vendorData['vendor_information'])) {
            return ResponsesHelper::returnError('400', 'Not found data for this vendor');
        }
        
        return ResponsesHelper::returnData($vendorData, '200', '');
    }

    public function getVendorReviews(Request $request, $vendorId)
    {
        $request->request->add(['vendor_id' => $vendorId]);
        $rules = [
            "vendor_id" => "required|numeric|exists:vendor_details,user_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $vendorReviews = OrderReview::getVendorReviews($request->vendor_id);

        if (empty($vendorReviews)) {
            return ResponsesHelper::returnError('400', 'not found reviews for this vendor right now !');
        }

        return ResponsesHelper::returnData($vendorReviews, '200', '');

    }

    public function getVendorPackages(Request $request, $vendorId)
    {
        $request->request->add(['vendor_id' => $vendorId]);
        $rules = [
            "vendor_id" => "required|numeric|exists:vendor_details,user_id",
            "service_type" => "required|string"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $vendorPackages = VendorServices::packagesOfVendor($vendorId, $request->service_type);

        if (empty($vendorPackages)) {
            return ResponsesHelper::returnError('400', 'not found package for this vendor right now !');
        }

        $data = [];


        foreach ($vendorPackages as $key => $package)
        {
            if (strval($package["package_price"]) == 0 || is_null(strval($package["package_price"]))){
                unset($vendorPackages[$key]);
            }
            else {
                $package['package_services']  = array_merge($package['package_services']);

                if ($package['package_services_ids'][0] === ','){
                    $package['package_services_ids'] = ltrim($package['package_services_ids'], $package['package_services_ids'][0]);
                }

                if($package['package_services_ids'][-1] === ','){
                    $package['package_services_ids'] = rtrim($package['package_services_ids'], $package['package_services_ids'][-1]);
                }

                $data[] = $package;
            }
        }

        return ResponsesHelper::returnData($data, '200', '');
    }

    public function getVendorSubCategoriesOfCategory(Request $request, $vendorId, $cat_id)
    {
        $request->request->add(['vendor_id' => $vendorId]);
        $request->request->add(['cat_id' => $cat_id]);
        $rules = [
            "cat_id"       => "required|numeric|exists:categories,cat_id",
            "vendor_id"    => "required|numeric|exists:vendor_details,user_id",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $vendorCategoryServices['main_category'] = Category::mainCategoryByCatId($cat_id);

        if (!count($vendorCategoryServices['main_category'])){
            return ResponsesHelper::returnError(400, 'This main category does not belong to this vendor');
        }

        $vendorCategoryServices['sub_categories'] = vendorServices::subCategoriesOfVendorByCatId($cat_id, $vendorId);

        if (!count($vendorCategoryServices['sub_categories'])){

            return ResponsesHelper::returnError(400,'This category does not contain sub categories');
        }


        return ResponsesHelper::returnData($vendorCategoryServices, '200', '');
    }

    public function getVendorCategoryServices(Request $request, $vendorId, $cat_id)
    {
        $request->request->add(['vendor_id' => $vendorId]);
        $request->request->add(['cat_id' => $cat_id]);
        $rules = [
            "cat_id"        => "required|numeric|exists:categories,cat_id",
            "vendor_id"     => "required|numeric|exists:vendor_details,user_id",
            "service_type" => "required|string"
        ];

        if (!$request->service_type == 'home' || !$request->service_type == 'salon'){
            return ResponsesHelper::returnError(400,'service_type must be salon or home');
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $vendorServicesOfSubCategory  = VendorServices::servicesOfVendorByCatId($cat_id, $vendorId, $request->service_type);



        if (!count($vendorServicesOfSubCategory)) {
            return ResponsesHelper::returnError('400', 'not found services for this category!');
        }


        $data = [];
        foreach ($vendorServicesOfSubCategory as $key => $service)
        {
            if (strval($service["service_price"]) == 0 || is_null(strval($service["service_price"]))){
                unset($vendorServicesOfSubCategory[$key]);
            }
            else {
                $data[] = $service;
            }

        }


        return ResponsesHelper::returnData($data, '200', '');
    }

}
