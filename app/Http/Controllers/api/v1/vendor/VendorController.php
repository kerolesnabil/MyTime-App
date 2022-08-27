<?php

namespace App\Http\Controllers\api\v1\vendor;

use App\Adpaters\ISMSGateway;
use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\VerificationServices;
use App\Models\Order;
use App\Models\OrderReview;
use App\Models\User;
use App\Models\VendorDetail;
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

        $vendor['vendor']=Auth::user();

        if($vendor['vendor']->user_type!='vendor'){
           return ResponsesHelper::returnError('400','you are not a vendor');
        }

        $vendor['vendor_information']=VendorDetail::getVendorById($vendor['vendor']->user_id);

        $vendor['vendor']->user_img=ImgHelper::returnImageLink( $vendor['vendor']->user_img);

        $vendor['last_orders']=Order::getLastOrdersOfVendor($vendor['vendor']->user_id);

        $vendor['count_orders']=Order::countAllOrdersOfVendor($vendor['vendor']->user_id);

        return ResponsesHelper::returnData($vendor,'200');

    }

    public function getVendorReviews(Request $request)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }
        $vendorReviews = OrderReview::getVendorReviews($vendor['vendor']->user_id);

        if (empty($vendorReviews)) {
            return ResponsesHelper::returnError('400', 'not found reviews for this vendor right now !');
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
            'vendor_logo'                        => 'required|image|mimes:jpg,jpeg,png|max:1000'
        ];

        $validator = Validator::make($request->all(), $rules);

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

            return ResponsesHelper::returnError('300', 'some error in send sms');
        }

        return ResponsesHelper::returnData(
            [
                'user' => $user, 'user_detail' => $user_detail
            ],
            '201',
            ''
        );
    }

    public function getVendorProfile(Request $request)
    {
        $vendor['vendor']=Auth::user();
        if($vendor['vendor']->user_type!='vendor'){
            return ResponsesHelper::returnError('400','you are not a vendor');
        }

        $vendorDetailsData = collect(VendorDetail::getVendorDetailProfile($vendor['vendor']->user_id))->toArray();


        $userProfile['vendor_name']                        = $vendor['vendor']->user_name;
        $userProfile['vendor_email']                       = $vendor['vendor']->user_email;
        $userProfile['vendor_phone']                       = $vendor['vendor']->user_phone;
        $userProfile['vendor_address']                     = $vendor['vendor']->user_address;
        $userProfile['vendor_logo']                        = ImgHelper::returnImageLink($vendor['vendor']->user_img);
        $userProfile['vendor_available_days']              = $vendorDetailsData['vendor_available_days'];
        $userProfile['vendor_start_time']                  = $vendorDetailsData['vendor_start_time'];
        $userProfile['vendor_end_time']                    = $vendorDetailsData['vendor_end_time'];
        $userProfile['vendor_slider']                      = $vendorDetailsData['vendor_slider'];
        $userProfile['vendor_description']                 = $vendorDetailsData['vendor_description'];
        $userProfile['vendor_commercial_registration_num'] = $vendorDetailsData['vendor_commercial_registration_num'];
        $userProfile['vendor_tax_num']                     = $vendorDetailsData['vendor_tax_num'];

        return ResponsesHelper::returnData($userProfile, '200', '');
    }

    public function updateVendorProfile(Request $request)
    {
        $rules = [
            'user_name'                          => 'required|string',
            'user_email'                         => 'required|string|email',
            'vendor_available_days'              => 'required|string',
            'vendor_start_time'                  => 'required|date_format:H:i:s',
            'vendor_end_time'                    => 'required|date_format:H:i:s|after:time_start',
            'vendor_logo'                        => 'image|mimes:jpg,jpeg,png|max:10240',
            'vendor_address'                     => 'required',
            'vendor_description'                 => 'required|string',
            'vendor_commercial_registration_num' => 'required|string',
            'vendor_tax_num'                     => 'required|string',
            'slider_old_images.*'                => 'string|string',
            'slider_new_images.*'                => 'image|mimes:jpg,jpeg,png|max:10240',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!is_null($request->vendor_logo)) {

            $image = ImgHelper::uploadImage('images', $request->vendor_logo);
            User::updateUserProfile($request, $image);
        }
        else {
            User::updateUserProfile($request);
        }






    }



}
