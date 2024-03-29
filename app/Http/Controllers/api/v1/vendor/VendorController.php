<?php

namespace App\Http\Controllers\api\v1\vendor;

use App\Adpaters\ISMSGateway;
use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\VerificationServices;
use App\Models\Order;
use App\Models\OrderReview;
use App\Models\Setting;
use App\Models\User;
use App\Models\VendorDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{

    public $verificationServices;

    public function __construct(VerificationServices $verificationServices)
    {
        $this->verificationServices = $verificationServices;
    }

    public function getVendorHomepage(Request $request)
    {

        $vendor = Auth::user();

        if($vendor->user_type!='vendor'){
           return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $data['vendor_information']                      = User::getUserTypeVendor($vendor->user_id);
        $data['vendor_statistics']                       = VendorDetail::getVendorById($vendor->user_id);
        $data['vendor_statistics']->vendor_reviews_count = strval($data['vendor_statistics']->vendor_reviews_count);
        $data['vendor_statistics']->vendor_views_count   = strval($data['vendor_statistics']->vendor_views_count);
        $data['vendor_statistics']->vendor_rate          = strval($data['vendor_statistics']->vendor_rate);
        $data['vendor_statistics']->count_all_orders     = strval($data['vendor_statistics']->count_all_orders);
        $countAllOrderOfVendor                           = Order::countAllOrdersOfVendor($vendor->user_id);

        if (!is_null($countAllOrderOfVendor)){
            $countAllOrderOfVendor = strval($countAllOrderOfVendor->all_count_orders);
        }

        $data['vendor_statistics']->count_all_orders = $countAllOrderOfVendor;
        $data['last_orders']                         = Order::getLastOrdersOfVendor($vendor->user_id);


        return ResponsesHelper::returnData($data,'200');

    }

    public function getVendorReviews(Request $request)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }
        $vendorReviews = OrderReview::getVendorReviews($vendor['vendor']->user_id);

        if (empty($vendorReviews)) {
            return ResponsesHelper::returnError('400', __('api.not_found_reviews_for_vendor'));
        }
        return ResponsesHelper::returnData($vendorReviews, '200', '');
    }

    public function register(Request $request)
    {
        $rules = [
            "user_name"                          => "required|string",
            "user_phone"                         => "required|string|unique:users,user_phone|digits:9",
            "user_email"                         => "required|string|email",
            "user_lat"                           => "required|string",
            "user_long"                          => "required|string",
            'vendor_type'                        => 'required|string',
            'vendor_available_days'              => 'required|string',
            'vendor_start_time'                  => 'required|date_format:H:i:s',
            'vendor_end_time'                    => 'required|date_format:H:i:s|after:time_start',
            'vendor_commercial_registration_num' => 'required|string',
            'vendor_tax_num'                     => 'required|string',
            'vendor_logo'                        => "required|mimes:jpg,jpeg,png|max:10240"
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "user_name.required"                          => __("api.user_name_required"),
                "user_name.string"                            => __("api.user_name_string"),
                "user_address.string"                         => __("api.user_address_string"),
                'user_phone.required'                         => __('api.phone_is_required'),
                'user_phone.unique'                           => __('api.phone_unique'),
                'user_phone.digits'                           => __('api.phone_is_9_digits'),
                "user_email.string"                           => __("api.user_email_string"),
                "user_email.email"                            => __("api.user_email_email"),
                "user_date_of_birth.required"                 => __("api.user_date_of_birth_required"),
                "user_date_of_birth.date"                     => __("api.user_date_of_birth_date"),
                "user_lat.string"                             => __("api.order_lat_string"),
                "user_long.string"                            => __("api.order_long_string"),
                "vendor_type.required"                        => __("api.vendor_type_required"),
                "vendor_type.string"                          => __("api.vendor_type_string"),
                "vendor_available_days.required"              => __("api.vendor_available_days_required"),
                "vendor_available_days.string"                => __("api.vendor_available_days_string"),
                "vendor_start_time.required"                  => __("api.vendor_start_time_required"),
                "vendor_start_time.date_format"               => __("api.vendor_start_time_date_format"),
                "vendor_end_time.required"                    => __("api.vendor_end_time_required"),
                "vendor_end_time.date_format"                 => __("api.vendor_end_time_date_format"),
                "vendor_end_time.after"                       => __("api.vendor_end_time_after"),
                "vendor_commercial_registration_num.required" => __("api.vendor_commercial_registration_num_required"),
                "vendor_commercial_registration_num.string"   => __("api.vendor_commercial_registration_num_string"),
                "vendor_tax_num.required"                     => __("api.vendor_tax_num_required"),
                "vendor_tax_num.string"                       => __("api.vendor_tax_num_string"),
                "vendor_logo.required"                        => __("api.vendor_logo_required"),
                "vendor_logo.mimes"                           => __("api.vendor_logo_mimes"),
                "vendor_logo.max"                             => __("api.vendor_logo_max"),
            ]
        );


        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }




        $image = ImgHelper::uploadImage('images', $request->vendor_logo);


        DB::beginTransaction();
        $user        = User::createUser($request, 'vendor', $image);
        $user_detail = VendorDetail::createUserDetails($user->user_id, $request);
        DB::commit();

        try {
            $verification['user_id'] = $user->user_id;
            $verification_data       = $this->verificationServices->setVerificationCode($verification);
            $message                 = $this->verificationServices->getSMSVerifyMessageByAppName($verification_data->code);

            app(ISMSGateway::class)->sendSms($user->user_phone, $message,$image);

        } catch (\Exception $ex) {

            return ResponsesHelper::returnError('400', __('api.error_in_send_sms'));
        }

        return ResponsesHelper::returnData(['user' => $user, 'user_detail' => $user_detail], '200', '');
    }

    public function getVendorProfile(Request $request)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $vendorDetailsData = collect(VendorDetail::getVendorDetailProfile($vendor['vendor']->user_id))->toArray();

        $workDays = $vendorDetailsData['vendor_available_days'];

        $workDays = explode( ',' , strtolower(trim($workDays, "[]")));

        foreach ($workDays as $day)
        {
            $data[] = trim(trim($day, "'"), " '");
        }

        $userProfile['vendor_name']                        = $vendor['vendor']->user_name;
        $userProfile['vendor_email']                       = $vendor['vendor']->user_email;
        $userProfile['vendor_phone']                       = $vendor['vendor']->user_phone;
        $userProfile['vendor_address']                     = $vendor['vendor']->user_address;
        $userProfile['vendor_logo']                        = ImgHelper::returnImageLink($vendor['vendor']->user_img);
        $userProfile['vendor_work_days']                   = $data;
        $userProfile['vendor_start_time']                  = date('H:i', strtotime($vendorDetailsData['vendor_start_time']));
        $userProfile['vendor_end_time']                    = date('H:i', strtotime($vendorDetailsData['vendor_end_time']));
        $userProfile['vendor_slider']                      = $vendorDetailsData['vendor_slider'];
        $userProfile['vendor_description']                 = $vendorDetailsData['vendor_description'];
        $userProfile['vendor_commercial_registration_num'] = $vendorDetailsData['vendor_commercial_registration_num'];
        $userProfile['vendor_tax_num']                     = $vendorDetailsData['vendor_tax_num'];


        return ResponsesHelper::returnData($userProfile, '200', '');
    }

    public function updateVendorProfile(Request $request)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }
        $rules = [
            'vendor_name'                        => 'required|string',
            'vendor_email'                       => 'required|string|email',
            'vendor_available_days'              => 'required|string',
            'vendor_start_time'                  => 'required|date_format:H:i',
            'vendor_end_time'                    => 'required|date_format:H:i|after:time_start',
            'vendor_logo'                        => 'mimes:jpg,jpeg,png|max:10240',
            'vendor_address'                     => 'required',
            'vendor_description'                 => 'required|string',
            'slider_not_removed_images.*'        => 'string',
            'slider_new_images.*'                => 'mimes:jpg,jpeg,png|max:3072',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "user_name.required"                          => __("api.user_name_required"),
                "user_name.string"                            => __("api.user_name_string"),
                "user_address.string"                         => __("api.user_address_string"),
                'user_phone.required'                         => __('api.phone_is_required'),
                'user_phone.unique'                           => __('api.phone_unique'),
                'user_phone.digits'                           => __('api.phone_is_9_digits'),
                "user_email.string"                           => __("api.user_email_string"),
                "user_email.email"                            => __("api.user_email_email"),
                "user_date_of_birth.required"                 => __("api.user_date_of_birth_required"),
                "user_date_of_birth.date"                     => __("api.user_date_of_birth_date"),
                "user_lat.string"                             => __("api.order_lat_string"),
                "user_long.string"                            => __("api.order_long_string"),
                "vendor_type.required"                        => __("api.vendor_type_required"),
                "vendor_type.string"                          => __("api.vendor_type_string"),
                "vendor_available_days.required"              => __("api.vendor_available_days_required"),
                "vendor_available_days.string"                => __("api.vendor_available_days_string"),
                "vendor_start_time.required"                  => __("api.vendor_start_time_required"),
                "vendor_start_time.date_format"               => __("api.vendor_start_time_date_format"),
                "vendor_end_time.required"                    => __("api.vendor_end_time_required"),
                "vendor_end_time.date_format"                 => __("api.vendor_end_time_date_format"),
                "vendor_end_time.after"                       => __("api.vendor_end_time_after"),
                "vendor_logo.required"                        => __("api.vendor_logo_required"),
                "vendor_logo.mimes"                           => __("api.vendor_logo_mimes"),
                "vendor_logo.max"                             => __("api.vendor_logo_max"),
                "vendor_address.required"                     => __("api.vendor_address_required"),
                "vendor_description.required"                 => __("api.vendor_description_required"),
                "vendor_description.string"                   => __("api.vendor_description_string"),
                "slider_not_removed_images.*.string"          => __("api.slider_not_removed_images_string"),
                "slider_new_images.*.mimes"                   => __("api.slider_new_images_mimes"),
                "slider_new_images.*.max"                     => __("api.slider_new_images_max"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        $sliderInDB = VendorDetail::getVendorSlider($vendor['vendor']->user_id);
        $sliderInDB = $sliderInDB['vendor_slider'];


        if ($sliderInDB != null) {
            if (isset($request->slider_not_removed_images)){

                $notRemovedImages = array_intersect($sliderInDB, $request->slider_not_removed_images);
                $removedImages = array_diff($sliderInDB, $request->slider_not_removed_images);

                if (count($removedImages)){
                    foreach ($removedImages as $img){
                        $imgPath = ImgHelper::getImgPathFromUrl($img);
                        $imgPath = ltrim($imgPath,'uploads/');
                        ImgHelper::deleteImage('images', $imgPath);
                    }
                }
            }
            else{
                $notRemovedImages = null;
            }
        }
        else {
            $notRemovedImages = null;
        }


        if ($notRemovedImages  != null){
            $notRemovedImages = ImgHelper::getSliderPathFromUrl($notRemovedImages);
        }


        $allImages = $notRemovedImages;
        $newImages = [];
        if (isset($request->slider_new_images)){

            foreach ($request->slider_new_images as $image){
                $newImages[] = ImgHelper::uploadImage('images', $image);
            }

            if ($allImages !=null){
                $allImages = array_merge($allImages, $newImages);
            }
            else{
                $allImages  = $newImages;
            }
        }

        !empty($allImages)  ? $allImages = json_encode($allImages) : $allImages = null;

        $data['user_id']                            = $vendor['vendor']->user_id;
        $data['user_name']                          = $request->vendor_name;
        $data['user_email']                         = $request->vendor_email;
        $data['vendor_available_days']              = $request->vendor_available_days;
        $data['vendor_start_time']                  = $request->vendor_start_time;
        $data['vendor_end_time']                    = $request->vendor_end_time;
        $data['user_address']                       = $request->vendor_address;
        $data['vendor_description']                 = $request->vendor_description;
        $data['vendor_slider']                      = $allImages;

        $data = (object) $data;


        VendorDetail::updateVendorDetails($data);

        if ($request->hasFile('vendor_logo')) {
            $image = ImgHelper::uploadImage('images', $request->vendor_logo);
            User::updateUserProfile($data, $image);
        }
        else {
            User::updateUserProfile($data);
        }


        return ResponsesHelper::returnData([], '200', __('api.updated_successfully'));

    }

    public function getVendorReport(Request $request)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400',__('api.not_vendor'));
        }

        $rules = [
            'report_time_type' => 'required|string',
            'report_name_type' => 'required|string',

        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "report_time_type.required" => __("api.report_time_type_required"),
                "report_time_type.string"   => __("api.report_time_type_string"),
                "report_name_type.required" => __("api.report_name_type_required"),
                "report_name_type.string"   => __("api.report_name_type_string"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $reportTimeTypes = ['weekly', 'monthly', 'yearly'];
        if (!in_array($request['report_time_type'], $reportTimeTypes)){

            return ResponsesHelper::returnError('400',__('api.not_found_report_type'));
        }

        $reportNameTypes = ['views', 'reviews'];
        if (!in_array($request['report_name_type'], $reportNameTypes)){
            return ResponsesHelper::returnError('400',__('api.not_found_report_type'));
        }

        $report = VendorDetail::getVendorReport($vendor['vendor']->user_id, $request['report_time_type'], $request['report_name_type']);

        if (is_null($report)){

            $report['report_value'] = 0;
        }


        return ResponsesHelper::returnData($report, '200', '');
    }

}
