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

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "vendor_id.required"         => __("api.vendor_id_required"),
                "vendor_id.numeric"          => __("api.vendor_id_numeric"),
                "vendor_id.exists"           => __("api.vendor_id_exists"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        VendorView::incrementMonthlyVendorViews($vendorId);
        VendorDetail::incrementVendorViewsCount($vendorId);

        $vendorData['vendor_information']     = VendorDetail::VendorDetails($vendorId);
        $vendorData['vendor_main_categories'] = VendorServices::mainCategoriesOfVendor($vendorId);


        if (empty($vendorData['vendor_information'])) {
            return ResponsesHelper::returnError('400', __('api.not_found_data_for_vendor'));
        }

        return ResponsesHelper::returnData($vendorData, '200', '');
    }

    public function getVendorReviews(Request $request, $vendorId)
    {
        $request->request->add(['vendor_id' => $vendorId]);
        $rules = [
            "vendor_id" => "required|numeric|exists:vendor_details,user_id",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "vendor_id.required"         => __("api.vendor_id_required"),
                "vendor_id.numeric"          => __("api.vendor_id_numeric"),
                "vendor_id.exists"           => __("api.vendor_id_exists"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $vendorReviews = OrderReview::getVendorReviews($request->vendor_id);

        if (empty($vendorReviews)) {
            return ResponsesHelper::returnError('400', __('api.not_found_reviews_for_vendor'));
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

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "vendor_id.required"    => __("api.vendor_id_required"),
                "vendor_id.numeric"     => __("api.vendor_id_numeric"),
                "vendor_id.exists"      => __("api.vendor_id_exists"),
                "service_type.required" => __("api.service_type_required"),
                "service_type.string"   => __("api.service_type_string"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $vendorPackages = VendorServices::packagesOfVendor($vendorId, $request->service_type);


        if (empty($vendorPackages)) {
            return ResponsesHelper::returnError('400', __('api.not_found_package_for_vendor'));
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

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "cat_id.required"    => __("api.cat_id_required"),
                "cat_id.numeric"     => __("api.cat_id_numeric"),
                "cat_id.exists"      => __("api.cat_id_exists"),
                "vendor_id.required" => __("api.vendor_id_required"),
                "vendor_id.numeric"  => __("api.vendor_id_numeric"),
                "vendor_id.exists"   => __("api.vendor_id_exists"),

            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $vendorCategoryServices['main_category'] = Category::mainCategoryByCatId($cat_id);

        if (!count($vendorCategoryServices['main_category'])){
            return ResponsesHelper::returnError(400, __('api.main_category_not_belong_to_vendor'));
        }

        $vendorCategoryServices['sub_categories'] = vendorServices::subCategoriesOfVendorByCatId($cat_id, $vendorId);

        if (!count($vendorCategoryServices['sub_categories'])){

            return ResponsesHelper::returnError(400,__('api.category_not_contain_sub_categories'));
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
            "service_type"  => "required|string"
        ];

        if (!$request->service_type == 'home' || !$request->service_type == 'salon'){
            return ResponsesHelper::returnError(400,__('api.service_location_must_be_salon_or_home'));
        }
        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "cat_id.required"       => __("api.cat_id_required"),
                "cat_id.numeric"        => __("api.cat_id_numeric"),
                "cat_id.exists"         => __("api.cat_id_exists"),
                "vendor_id.required"    => __("api.vendor_id_required"),
                "vendor_id.numeric"     => __("api.vendor_id_numeric"),
                "vendor_id.exists"      => __("api.vendor_id_exists"),
                "service_type.required" => __("api.service_type_required"),
                "service_type.string"   => __("api.service_type_string"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $vendorServicesOfSubCategory  = VendorServices::servicesOfVendorByCatId($cat_id, $vendorId, $request->service_type);



        if (!count($vendorServicesOfSubCategory)) {
            return ResponsesHelper::returnError('400', __('api.not_found_services_to_category'));
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
