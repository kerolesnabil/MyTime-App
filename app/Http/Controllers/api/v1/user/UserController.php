<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ad;
use App\Models\VendorDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Adpaters\ISMSGateway;
use App\Http\Services\VerificationServices;
use App\Models\User;


class UserController extends Controller
{


    public $verificationServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VerificationServices $verificationServices)
    {
        $this->verificationServices = $verificationServices;
    }


    public function getUserHomepageData(Request $request)
    {

        $rules = [
            "user_lat"        => "required|string",
            "user_long"       => "required|string",
            "service_type"    => "required|string",
            "filter_cat_id"   => "string"
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "user_lat.required"     => __("api.order_lat_required"),
                "user_lat.string"       => __("api.order_lat_string"),
                "user_long.required"    => __("api.order_long_required"),
                "user_long.string"      => __("api.order_long_string"),
                "service_type.required" => __("api.service_type_required"),
                "service_type.string"   => __("api.service_type_string"),
                "filter_cat_id.string"   => __("api.filter_cat_id_string"),
            ]

        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $userHomepageData['ads_at_homepage']        = Ad::availableAdsOnHomepage();
        $userHomepageData['categories_first_level'] = Category::mainCategories();


        $userHomepageData['salons']      = VendorDetail::vendorsByType('salon', $request->user_lat, $request->user_long, $request->filter_cat_id);
        $userHomepageData['specialists'] = [];

        if ($request->service_type == 'home') {
            $userHomepageData['specialists'] = VendorDetail::vendorsByType('specialist', $request->user_lat, $request->user_long, $request->filter_cat_id);
        }

        $userHomepageData['salons']      = array_values($userHomepageData['salons']);
        $userHomepageData['specialists'] = array_values($userHomepageData['specialists']);

        return ResponsesHelper::returnData($userHomepageData);
    }

    public function register(Request $request)
    {
        $rules = [
            "user_name"          => "required|string",
            "user_address"       => "string",
            "user_phone"         => "required|unique:users,user_phone|digits:9",
            "user_email"         => "string|email",
            "user_date_of_birth" => "required|date",
            "user_lat"           => "string",
            "user_long"          => "string",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "user_name.required"          => __("api.user_name_required"),
                "user_name.string"            => __("api.user_name_string"),
                "user_address.string"         => __("api.user_address_string"),
                'user_phone.required'         => __('api.phone_is_required'),
                'user_phone.unique'           => __('api.phone_unique'),
                'user_phone.digits'           => __('api.phone_is_9_digits'),
                "user_email.string"           => __("api.user_email_string"),
                "user_email.email"            => __("api.user_email_email"),
                "user_date_of_birth.required" => __("api.user_date_of_birth_required"),
                "user_date_of_birth.date"     => __("api.user_date_of_birth_date"),
                "user_lat.string"             => __("api.order_lat_string"),
                "user_long.string"            => __("api.order_long_string"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $user = User::createUser($request, 'user', null, $request->user_date_of_birth, $request->user_address);

        try {
            $verification['user_id'] = $user->user_id;
            $verification_data       = $this->verificationServices->setVerificationCode($verification);
            $message                 = $this->verificationServices->getSMSVerifyMessageByAppName($verification_data->code);

            app(ISMSGateway::class)->sendSms($user->user_phone, $message);

        } catch (\Exception $ex) {
            return ResponsesHelper::returnError('', __('api.error_in_send_sms'));
        }

        return ResponsesHelper::returnData(['user' => $user], '200', __('api.register_successfully'));
    }


    public function getUserProfile()
    {

        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', __('api.you_are_not_user'));
        }

        $userProfile['user_name']    = $user['user']->user_name;
        $userProfile['user_email']   = $user['user']->user_email;
        $userProfile['user_phone']   = $user['user']->user_phone;
        $userProfile['user_address'] = $user['user']->user_address;
        $userProfile['user_img']     = ImgHelper::returnImageLink($user['user']->user_img);

        return ResponsesHelper::returnData($userProfile, '200', '');
    }

    public function updateUserProfile(Request $request)
    {
        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', __('api.you_are_not_user'));
        }

        $request->request->add(['user_id' => $user['user']->user_id]);
        $rules = [
            "user_name"    => "required|string",
            "user_address" => "required|string",
            "user_email"   => "required|email",
            "user_img"     => "mimes:jpg,jpeg,png|max:10240",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "user_name.required"    => __("api.user_name_required"),
                "user_name.string"      => __("api.user_name_string"),
                'user_address.required' => __('api.user_address_required'),
                "user_address.string"   => __("api.user_address_string"),
                "user_email.string"     => __("api.user_email_string"),
                "user_email.email"      => __("api.user_email_email"),
                "user_img.mimes"        => __("api.user_img_mimes"),
                "user_img.max"          => __("api.user_img_max"),
            ],
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if ($request->hasFile('user_img')) {
            $image = ImgHelper::uploadImage('images', $request->user_img);
            User::updateUserProfile($request, $image);
        }
        else {
            User::updateUserProfile($request);
        }


        return ResponsesHelper::returnData([], '200', __('api.updated_successfully'));

    }

}
