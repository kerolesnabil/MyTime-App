<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SaveTaxRateRequest extends FormRequest
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
            'tax_rate.setting_value'       => 'required|numeric|min:1|max:100',
        ];

        $langs = Lang::getAllLangs();
        foreach ($langs as $lang){
            $rules['tax_rate.setting_name.' . $lang['lang_symbol']]  = 'required|string';
        }
        return $rules;
    }


    public function messages()
    {
        return [
            'tax_rate.setting_name.*.required' => __('site_setting.rule_tax_rate_setting_name.required'),
            'tax_rate.setting_name.*.string'   => __('site_setting.rule_tax_rate_setting_name.string'),
            'tax_rate.setting_value.required'  => __('site_setting.rule_tax_rate_setting_value.required'),
            'tax_rate.setting_value.numeric'   => __('site_setting.rule_tax_rate_setting_value.numeric'),
            'tax_rate.setting_value.min'       => __('site_setting.rule_tax_rate_setting_value.min'),
            'tax_rate.setting_value.max'       => __('site_setting.rule_tax_rate_setting_value.max'),

        ];
    }

}
