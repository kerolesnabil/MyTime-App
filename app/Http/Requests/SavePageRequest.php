<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SavePageRequest extends FormRequest
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

            "show_in_user_app"   => "required|numeric|min:0|max:1",
            "show_in_vendor_app" => "required|numeric|min:0|max:1",
            "is_active"          => "required|numeric|min:0|max:1",
            "page_position"      => "required|string",
        ];


        if (is_null($this->request->get('page_id'))){
            $rules['img'] =  "required|image|mimes:jpg,jpeg,png|max:10240";
        }
        else{
            $rules['img'] =  "image|mimes:jpg,jpeg,png|max:10240";
        }


        $langs = Lang::getAllLangs();
        foreach ($langs as $lang){
            $rules['page_title.' . $lang['lang_symbol']]   = 'required|string';
            $rules['page_content.' . $lang['lang_symbol']] = 'required|string';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'show_in_user_app.required'   => __('site_page.rule_show_in_user_app.required'),
            'show_in_user_app.numeric'    => __('site_page.rule_show_in_user_app.numeric'),
            'show_in_user_app.min'        => __('site_page.rule_show_in_user_app.min'),
            'show_in_user_app.max'        => __('site_page.rule_show_in_user_app.max'),
            'show_in_vendor_app.required' => __('site_page.rule_show_in_vendor_app.required'),
            'show_in_vendor_app.numeric'  => __('site_page.rule_show_in_vendor_app.numeric'),
            'show_in_vendor_app.min'      => __('site_page.rule_show_in_vendor_app.min'),
            'show_in_vendor_app.max'      => __('site_page.rule_show_in_vendor_app.max'),
            'is_active.required'          => __('site_page.rule_is_active.required'),
            'is_active.numeric'           => __('site_page.rule_is_active.numeric'),
            'is_active.min'               => __('site_page.rule_is_active.min'),
            'is_active.max'               => __('site_page.rule_is_active.max'),
            'page_position.required'      => __('site_page.rule_page_position.required'),
            'page_position.numeric'       => __('site_page.rule_page_position.numeric'),
            'img.required'                => __('site_page.rule_img.required'),
            'img.image'                   => __('site_page.rule_img.image'),
            'img.mimes'                   => __('site_page.rule_img.mimes'),
            'img.max'                     => __('site_page.rule_img.max'),
            'page_title.*.required'       => __('site_page.rule_page_title.required'),
            'page_title.*.string'         => __('site_page.rule_page_title.string'),
            'page_content.*.string'       => __('site_page.rule_page_content.string'),
            'page_content.*.required'     => __('site_page.rule_page_content.required'),
        ];
    }

}
