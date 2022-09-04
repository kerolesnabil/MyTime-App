<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SaveRejectionReasonRequest extends FormRequest
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
        $rules = [];
        $langs = Lang::getAllLangs();
        foreach ($langs as $lang){
            $rules['rejection_reason.' . $lang['lang_symbol']]   = 'required|string';
        }

        return $rules;
    }

    public function messages()
    {
        return [

            'rejection_reason.*.required' => __('site_order_rejection_reason.rule_rejection_reason.required'),
            'rejection_reason.*.string'   => __('site_order_rejection_reason.rule_page_title.string'),

        ];
    }

}
