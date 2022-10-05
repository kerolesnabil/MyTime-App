<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SaveAdsPricesRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            'ad_price_in_homepage.setting_value'       => 'required|numeric',
            'ad_price_in_discover_page.setting_value'  => 'required|numeric',
        ];

        $langs = Lang::getAllLangs();
        foreach ($langs as $lang){
            $rules['ad_price_in_homepage.setting_name.' . $lang['lang_symbol']]      = 'required|string';
            $rules['ad_price_in_discover_page.setting_name.' . $lang['lang_symbol']] = 'required|string';
        }
        return $rules;
    }


    public function messages()
    {
        return [
            'ad_price_in_homepage.setting_name.*.required'       => __('site_setting.rule_ad_price_in_homepage_setting_name.required'),
            'ad_price_in_homepage.setting_name.*.string'         => __('site_setting.rule_ad_price_in_homepage_setting_name.string'),
            'ad_price_in_discover_page.setting_name.*.required'  => __('site_setting.rule_ad_price_in_discover_page_setting_name.required'),
            'ad_price_in_discover_page.setting_name.*.string'    => __('site_setting.rule_ad_price_in_discover_page_setting_name.string'),
            'ad_price_in_homepage.setting_value.required'      => __('site_setting.rule_ad_price_in_homepage_setting_value.required'),
            'ad_price_in_homepage.setting_value.numeric'       => __('site_setting.rule_ad_price_in_homepage_setting_value.numeric'),
            'ad_price_in_discover_page.setting_value.required' => __('site_setting.rule_ad_price_in_discover_page_setting_value.required'),
            'ad_price_in_discover_page.setting_value.numeric'  => __('site_setting.rule_ad_price_in_discover_page_setting_value.numeric'),



        ];
    }

}
