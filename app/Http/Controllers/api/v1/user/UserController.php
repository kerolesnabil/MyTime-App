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
            "user_lat"        => "required|numeric",
            "user_long"       => "required|numeric",
            "service_type"    => "required|string",
            "filter_cat_id"   => "string"
        ];

        $validator = Validator::make($request->all(), $rules);

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
            "user_phone"         => "required|string|unique:users,user_phone|digits:9",
            "user_email"         => "string|email",
            "user_date_of_birth" => "required|date",
            "user_lat"           => "string",
            "user_long"          => "string",
        ];

        $validator = Validator::make($request->all(), $rules);

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
            return ResponsesHelper::returnError('', 'some error in send sms');
        }

        return ResponsesHelper::returnData(['user' => $user], '200', 'register successfully');
    }


    public function getUserProfile()
    {

        $user['user'] = Auth::user();
        if ($user['user']->user_type != 'user') {
            return ResponsesHelper::returnError('400', 'you are not a user');
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
            return ResponsesHelper::returnError('400', 'you are not a user');
        }

        $request->request->add(['user_id' => $user['user']->user_id]);
        $rules = [
            "user_name"    => "required|string",
            "user_email"   => "required|string|email",
            "user_address" => "required|string",
            "user_img"     => "image|mimes:jpg,jpeg,png|max:10240",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        if (!is_null($request->user_img)) {

            $image = ImgHelper::uploadImage('images', $request->user_img);
            User::updateUserProfile($request, $image);
        }
        else {
            User::updateUserProfile($request);
        }


        return ResponsesHelper::returnData([], '200', 'updated successfully');

    }

}
