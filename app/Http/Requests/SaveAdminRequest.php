<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SaveAdminRequest extends FormRequest
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
            "user_name"          => "required|string",
            "user_address"       => "string",
            "user_email"         => "required|string|email",
            "user_date_of_birth" => "required|date",
            "user_img"           => "mimes:jpg,jpeg,png|max:10240",
            "user_is_active"     => "required|numeric|min:0|max:1",
        ];


        if (is_null($this->request->get('user_id'))){
            $rules['password']         = "required|string|min:5";
            $rules['confirm_password'] = "required|string|min:5|same:password";
            $rules["user_phone"]       = "required|string|digits:9|unique:users,user_phone";
        }
        else{
            $rules['password']         = "string|min:5";
            $rules['confirm_password'] = "string|min:5|same:password";
            $rules["user_phone"]       = "required|string|digits:9|unique:users,user_phone, " . $this->request->get('user_id') . ",user_id";

        }
        return $rules;
    }

    public function messages()
    {
        return [
            'user_name.required'          => __('site_admin.rule_user_name.required'),
            'user_name.string'            => __('site_admin.rule_user_name.string'),
            'user_address.string'         => __('site_admin.rule_user_address.string'),
            'user_phone.required'         => __('site_admin.rule_user_phone.required'),
            'user_phone.string'           => __('site_admin.rule_user_phone.string'),
            'user_phone.digits'           => __('site_admin.rule_user_phone.digits'),
            'user_phone.unique'           => __('site_admin.rule_user_phone.unique'),
            'user_email.required'         => __('site_admin.rule_user_email.required'),
            'user_email.string'           => __('site_admin.rule_user_email.string'),
            'user_email.email'            => __('site_admin.rule_user_email.email'),
            'user_date_of_birth.required' => __('site_admin.rule_user_user_date_of_birth.required'),
            'user_date_of_birth.date'     => __('site_admin.rule_user_user_date_of_birth.date'),
            'password.required'           => __('site_admin.rule_password.required'),
            'password.string'             => __('site_admin.rule_password.string'),
            'password.min'                => __('site_admin.rule_password.min'),
            'confirm_password.required'   => __('site_admin.rule_confirm_password.required'),
            'confirm_password.string'     => __('site_admin.rule_confirm_password.string'),
            'confirm_password.min'        => __('site_admin.rule_confirm_password.min'),
            'confirm_password.same'       => __('site_admin.rule_confirm_password.same'),
            'user_img.images'             => __('site_admin.rule_user_img.images'),
            'user_img.mimes'              => __('site_admin.rule_user_img.mimes'),
            'user_img.max'                => __('site_admin.rule_user_img.max'),
            'user_is_active.required'     => __('site_admin.rule_user_is_active.required'),
            'user_is_active.numeric'      => __('site_admin.rule_user_is_active.numeric'),
            'user_is_active.min'          => __('site_admin.rule_user_is_active.min'),
            'user_is_active.max'          => __('site_admin.rule_user_is_active.max'),

        ];
    }

}
