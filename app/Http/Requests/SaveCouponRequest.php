<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SaveCouponRequest extends FormRequest
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

            "coupon_value"       => "required|numeric",
            "coupon_type"        => "required|string",
            "coupon_limited_num" => "required|numeric",
            "coupon_start_at"    => "required|date_format:Y-m-d",
            "coupon_end_at"      => "required|date_format:Y-m-d|after:coupon_start_at",
            "is_active"          => "required|numeric|min:0|max:1",
        ];


        if (is_null($this->request->get('coupon_id'))){
            $rules["coupon_code"] =  "required|string|unique:coupons,coupon_code";
        }
        else{
            $rules["coupon_code"] =  "required|string|unique:coupons,coupon_code, " . $this->request->get('coupon_id').',coupon_id';
        }


        return $rules;
    }

    public function messages()
    {
        return [

            'coupon_code.required'        => __('site_coupon.rule_coupon_code.required'),
            'coupon_code.string'          => __('site_coupon.rule_coupon_code.string'),
            'coupon_code.unique'          => __('site_coupon.rule_coupon_code.unique'),
            'coupon_value.required'       => __('site_coupon.rule_coupon_value.required'),
            'coupon_value.numeric'        => __('site_coupon.rule_coupon_value.numeric'),
            'coupon_type.required'        => __('site_coupon.rule_coupon_type.required'),
            'coupon_type.string'          => __('site_coupon.rule_coupon_type.string'),
            'coupon_limited_num.required' => __('site_coupon.rule_coupon_limited_num.required'),
            'coupon_limited_num.numeric'  => __('site_coupon.rule_coupon_limited_num.numeric'),
            'coupon_start_at.required'    => __('site_coupon.rule_coupon_start_at.required'),
            'coupon_start_at.date_format' => __('site_coupon.rule_coupon_start_at.date_format'),
            'coupon_end_at.required'      => __('site_coupon.rule_coupon_end_at.required'),
            'coupon_end_at.date_format'   => __('site_coupon.rule_coupon_end_at.date_format'),
            'coupon_end_at.after'         => __('site_coupon.rule_coupon_end_at.after'),
            'is_active.required'          => __('site_coupon.rule_is_active.required'),
            'is_active.numeric'           => __('site_coupon.rule_is_active.numeric'),
            'is_active.min'               => __('site_coupon.rule_is_active.min'),
            'is_active.max'               => __('site_coupon.rule_is_active.max'),

        ];
    }

}
