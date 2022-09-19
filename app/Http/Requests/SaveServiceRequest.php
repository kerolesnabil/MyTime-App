<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SaveServiceRequest extends FormRequest
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
            "cat_id"      => "required|numeric|exists:categories,cat_id",
        ];


        $langs = Lang::getAllLangs();

        foreach ($langs as $lang){
            $rules['service_name.' . $lang['lang_symbol']] = 'required|string';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'cat_id.required'         => __('site_service.rule_cat_id_required'),
            'cat_id.numeric'          => __('site_service.rule_cat_id_numeric'),
            'cat_id.exists'           => __('site_service.rule_cat_id_exists'),
            'service_name.*.required' => __('site_service.service_name_required'),
            'service_name.*.string'   => __('site_service.service_name_string'),
        ];
    }

}
