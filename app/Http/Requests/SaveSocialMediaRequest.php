<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SaveSocialMediaRequest extends FormRequest
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

        if ($this->request->get('operation') == 'edit'){
            $langs = Lang::getAllLangs();
            foreach ($langs as $lang){
                $rules['setting_name.' . $lang['lang_symbol']]   = 'required|string';
            }
            $rules['setting_value.*.name']  = "required|string";
            $rules['setting_value.*.link']  = "required|string";
            $rules['setting_value.*.class'] = "required|string";
        }
        else {
            $rules['setting_value.name']  = "required|string";
            $rules['setting_value.link']  = "required|string";
            $rules['setting_value.class'] = "required|string";
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'setting_name.*.required'        => __('site_setting.rule_setting_name.required'),
            'setting_name.*.string'          => __('site_setting.rule_setting_name.string'),
            'setting_value.*.name.required'  => __('site_setting.rule_setting_value.name.required'),
            'setting_value.*.name.string'    => __('site_setting.rule_setting_value.name.string'),
            'setting_value.*.link.required'  => __('site_setting.rule_setting_value.link.required'),
            'setting_value.*.link.string'    => __('site_setting.rule_setting_value.link.string'),
            'setting_value.*.class.required' => __('site_setting.rule_setting_value.class.required'),
            'setting_value.*.class.string'   => __('site_setting.rule_setting_value.class.string'),
            'setting_value.name.required'  => __('site_setting.rule_setting_value.name.required'),
            'setting_value.name.string'    => __('site_setting.rule_setting_value.name.string'),
            'setting_value.link.required'  => __('site_setting.rule_setting_value.link.required'),
            'setting_value.link.string'    => __('site_setting.rule_setting_value.link.string'),
            'setting_value.class.required' => __('site_setting.rule_setting_value.class.required'),
            'setting_value.class.string'   => __('site_setting.rule_setting_value.class.string'),

        ];
    }

}
