<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SaveLangRequest extends FormRequest
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

            "lang_symbol"     => "required|string",
            "lang_name"       => "required|string",
            "lang_direction"  => "required|string",
            "lang_is_active" => "required|numeric|min:0|max:1",
        ];


        if (is_null($this->request->get('lang_id'))){
            $rules['lang_img'] =  "required|image|mimes:jpg,jpeg,png|max:10240";
        }
        else{
            $rules['lang_img'] =  "image|mimes:jpg,jpeg,png|max:10240";
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'lang_symbol.required'    => __('site_lang.rule_lang_symbol.required'),
            'lang_symbol.string'      => __('site_lang.rule_lang_symbol.string'),
            'lang_name.required'      => __('site_lang.rule_lang_name.required'),
            'lang_name.string'        => __('site_lang.rule_lang_name.string'),
            'lang_direction.required' => __('site_lang.rule_lang_direction.required'),
            'lang_direction.string'   => __('site_lang.rule_lang_direction.string'),


            'lang_is_active.required' => __('site_lang.rule_lang_is_active.required'),
            'lang_is_active.numeric'  => __('site_lang.rule_lang_is_active.numeric'),
            'lang_is_active.min'      => __('site_lang.rule_lang_is_active.min'),
            'lang_is_active.max'      => __('site_lang.rule_lang_is_active.max'),



            'lang_img.required'       => __('site_lang.rule_lang_img.required'),
            'lang_img.image'          => __('site_lang.rule_lang_img.image'),
            'lang_img.mimes'          => __('site_lang.rule_lang_img.mimes'),
            'lang_img.max'            => __('site_lang.rule_lang_img.max'),

        ];
    }

}
