<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SaveAppProfitPercentageRequest extends FormRequest
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
            'app_profit_percentage.setting_value'       => 'required|numeric|min:1|max:100',
        ];

        $langs = Lang::getAllLangs();
        foreach ($langs as $lang){
            $rules['app_profit_percentage.setting_name.' . $lang['lang_symbol']]      = 'required|string';
        }
        return $rules;
    }


    public function messages()
    {
        return [
            'app_profit_percentage.setting_name.*.required' => __('site_setting.rule_app_profit_percentage_setting_name.required'),
            'app_profit_percentage.setting_name.*.string'   => __('site_setting.rule_app_profit_percentage_setting_name.string'),
            'app_profit_percentage.setting_value.required'  => __('site_setting.rule_app_profit_percentage_setting_value.required'),
            'app_profit_percentage.setting_value.numeric'   => __('site_setting.rule_app_profit_percentage_setting_value.numeric'),
            'app_profit_percentage.setting_value.min'       => __('site_setting.rule_app_profit_percentage_setting_value.min'),
            'app_profit_percentage.setting_value.max'       => __('site_setting.rule_app_profit_percentage_setting_value.max'),

        ];
    }

}
