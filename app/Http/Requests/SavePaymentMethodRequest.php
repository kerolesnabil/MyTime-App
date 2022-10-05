<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SavePaymentMethodRequest extends FormRequest
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
        $rules =  [
            "payment_method_type" => "required|string",
            "is_active"           => "required|numeric|min:0|max:1",
        ];

        $langs = Lang::getAllLangs();
        foreach ($langs as $lang){
            $rules['payment_method_name.' . $lang['lang_symbol']]   = 'required|string';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'payment_method_type.required' => __('site_payment_method.rule_payment_method_type.required'),
            'payment_method_type.string'   => __('site_payment_method.rule_payment_method_type.string'),
            'payment_method_name.required' => __('site_payment_method.rule_payment_method_name.required'),
            'payment_method_name.string'   => __('site_payment_method.rule_payment_method_name.string'),
            'is_active.required'           => __('site_payment_method.rule_is_active.required'),
            'is_active.numeric'            => __('site_payment_method.rule_is_active.numeric'),
            'is_active.min'                => __('site_payment_method.rule_is_active.min'),
            'is_active.max'                => __('site_payment_method.rule_is_active.max'),
        ];
    }

}
