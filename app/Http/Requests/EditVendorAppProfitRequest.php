<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EditVendorAppProfitRequest extends FormRequest
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

        return [
            'vendor_app_profit_percentage' => 'required|numeric',
        ];
    }


    public function messages()
    {
        return [
            'vendor_app_profit_percentage.required' => __('site_vendor.rule_vendor_app_profit_percentage.required'),
            'vendor_app_profit_percentage.numeric'  => __('site_vendor.rule_vendor_app_profit_percentage.numeric'),
        ];
    }

}
