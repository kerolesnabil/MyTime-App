<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\VendorDetail;
use App\Models\VendorServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class RecentDataController extends Controller
{

    public function getRecentData(Request $request)
    {


        $data['recent_vendors']          = VendorDetail::recentVendor(3);
        $data['most_requested_services'] = VendorServices::getMostServiceOrdered(10, 'service');
        $data['most_requested_packages'] = VendorServices::getMostServiceOrdered(10,'package');
        $data['ads']                     = Ad::availableAdsOnDiscoverPage();

        return ResponsesHelper::returnData($data, '200', '');
    }

}
