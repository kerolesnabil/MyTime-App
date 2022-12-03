<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Foundation\Http\FormRequest;

class SaveBankAccountDetailsRequest extends FormRequest
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
            'bank_account_details.setting_value'  => 'required',
        ];


        $langs = Lang::getAllLangs();
        foreach ($langs as $lang){
            $rules['bank_account_details.setting_name.' . $lang['lang_symbol']]      = 'required|string';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'bank_account_details.setting_name.*.required' => __('site_setting.rule_bank_account_details_setting_name.required'),
            'bank_account_details.setting_name.*.string'   => __('site_setting.rule_bank_account_details_setting_name.string'),
            'bank_account_details.setting_value.*.required' => __('site_setting.rule_bank_account_details_setting_value.required'),
        ];
    }
}
