<?php

namespace App\Http\Controllers\api\v1\common;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class SettingsController extends Controller
{

    public static function getSocialMedia()
    {
        $socialMedia = Setting::getSettingByKey('social_media','api');

        if (is_null($socialMedia)){
            return $data =[];
        }
        $data = json_decode($socialMedia->setting_value, true);

        return $data;
    }

    public static function getWhatsAppNumber()
    {
        $whatsAppNumber = Setting::getSettingByKey('whatsapp', 'api');

        if (is_null($whatsAppNumber)){
            return $data =[];
        }

        $data = $whatsAppNumber;

        return $data;
    }


}
