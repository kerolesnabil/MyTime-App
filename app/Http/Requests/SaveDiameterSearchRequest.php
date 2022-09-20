<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SaveDiameterSearchRequest extends FormRequest
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
            'diameter_search.setting_value'       => 'required|numeric',
        ];

        $langs = Lang::getAllLangs();
        foreach ($langs as $lang){
            $rules['diameter_search.setting_name.' . $lang['lang_symbol']]      = 'required|string';
        }
        return $rules;
    }


    public function messages()
    {
        return [
            'diameter_search.setting_name.*.required' => __('site_setting.rule_diameter_search_setting_name.required'),
            'diameter_search.setting_name.*.string'   => __('site_setting.rule_diameter_search_setting_name.string'),
            'diameter_search.setting_value.required'  => __('site_setting.rule_diameter_search_setting_value.required'),
            'diameter_search.setting_value.numeric'   => __('site_setting.rule_diameter_search_setting_value.numeric'),

        ];
    }

}
