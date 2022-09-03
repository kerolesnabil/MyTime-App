<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SaveCategoryRequest extends FormRequest
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
            "cat_is_active"      => "required|numeric|min:0|max:1",
        ];

        if ($this->request->get('parent_id') != 0){
            $rules['parent_id'] =  "required|numeric|exists:categories,cat_id";
        }
        else{
            $rules['parent_id'] =  "required|numeric";
        }


        if (is_null($this->request->get('cat_id'))){
            $rules['cat_img'] =  "required|image|mimes:jpg,jpeg,png|max:10240";
        }
        else{
            $rules['cat_img'] =  "image|mimes:jpg,jpeg,png|max:10240";
        }



        $langs = Lang::getAllLangs();

        foreach ($langs as $lang){
            $rules['cat_name.' . $lang['lang_symbol']]        = 'required|string';
            $rules['cat_description.' . $lang['lang_symbol']] = 'required|string';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'parent_id.required'         => __('site_category.rule_parent_id_required'),
            'parent_id.numeric'          => __('site_category.rule_parent_id_numeric'),
            'parent_id.exists'           => __('site_category.rule_parent_id_exists'),
            'cat_name.required'          => __('site_category.rule_cat_name_required'),
            'cat_name.string'            => __('site_category.rule_cat_name_string'),
            'cat_description.string'     => __('site_category.rule_cat_description_string'),
            'cat_img.required'           => __('site_category.rule_cat_img_required'),
            'cat_img.image'              => __('site_category.rule_cat_img_image'),
            'cat_img.mimes'              => __('site_category.rule_cat_img_mimes'),
            'cat_img.max'                => __('site_category.rule_cat_img_max'),
            'cat_is_active.required'     => __('site_category.rule_cat_is_active_required'),
            'cat_is_active.numeric'      => __('site_category.rule_cat_is_active_numeric'),
            'cat_is_active.min'          => __('site_category.rule_cat_is_active_min'),
            'cat_is_active.max'          => __('site_category.rule_cat_is_active_max'),
            'cat_name.*.required'        => __('site_category.rule_cat_name_required'),
            'cat_name.*.string'          => __('site_category.rule_cat_name_required'),
            'cat_description.*.required' => __('site_category.rule_cat_description_required'),
            'cat_description.*.string'   => __('site_category.rule_cat_description_required'),
        ];
    }

}
