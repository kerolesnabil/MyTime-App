<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SaveAppImagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [];

        $langs = Lang::getAllLangs();
        foreach ($langs as $lang){

            $rules['user_splash.setting_name.' . $lang['lang_symbol']]   = 'required|string';
            $rules['user_logo.setting_name.' . $lang['lang_symbol']]     = 'required|string';
            $rules['vendor_splash.setting_name.' . $lang['lang_symbol']] = 'required|string';
            $rules['vendor_logo.setting_name.' . $lang['lang_symbol']]   = 'required|string';
        }

        if (isset($request->user_splash['setting_value'])){
            $rules['user_splash_img'] =  "image|mimes:jpg,jpeg,png|max:10240";
        }

        if (isset($request->user_logo['setting_value'])){
            $rules['user_logo_img'] =  "image|mimes:jpg,jpeg,png|max:10240";
        }

        if (isset($request->vendor_splash['setting_value'])){
            $rules['vendor_splash_img'] =  "image|mimes:jpg,jpeg,png|max:10240";
        }

        if (isset($request->vendor_logo['setting_value'])){
            $rules['vendor_logo_img'] =  "image|mimes:jpg,jpeg,png|max:2048";
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'user_splash.setting_name.*.required'   => __('site_setting.rule_user_splash_setting_name.required'),
            'user_splash.setting_name.*.string'     => __('site_setting.rule_user_splash_setting_name.string'),
            'user_logo.setting_name.*.required'     => __('site_setting.rule_user_logo_setting_name.required'),
            'user_logo.setting_name.*.string'       => __('site_setting.rule_user_logo_setting_name.string'),
            'vendor_splash.setting_name.*.required' => __('site_setting.rule_vendor_splash_setting_name.required'),
            'vendor_splash.setting_name.*.string'   => __('site_setting.rule_vendor_splash_setting_name.string'),
            'vendor_logo.setting_name.*.required'   => __('site_setting.rule_vendor_logo_setting_name.required'),
            'vendor_logo.setting_name.*.string'     => __('site_setting.rule_vendor_logo_setting_name.string'),

            'user_splash_img.image'                  => __('site_setting.rule_user_splash_img.image'),
            'user_splash_img.mimes'                  => __('site_setting.rule_user_splash_img.mimes'),
            'user_splash_img.max'                    => __('site_setting.rule_user_splash_img.max'),

            'user_logo_img.image'                    => __('site_setting.rule_user_logo_img.image'),
            'user_logo_img.mimes'                    => __('site_setting.rule_user_logo_img.mimes'),
            'user_logo_img.max'                      => __('site_setting.rule_user_logo_img.max'),

            'vendor_splash_img.image'                => __('site_setting.rule_vendor_splash_img.image'),
            'vendor_splash_img.mimes'                => __('site_setting.rule_vendor_splash_img.mimes'),
            'vendor_splash_img.max'                  => __('site_setting.rule_vendor_splash_img.max'),

            'vendor_logo_img.image'                  => __('site_setting.rule_vendor_logo_img.image'),
            'vendor_logo_img.mimes'                  => __('site_setting.rule_vendor_logo_img.mimes'),
            'vendor_logo_img.max'                    => __('site_setting.rule_vendor_logo_img.max'),
        ];
    }

}
