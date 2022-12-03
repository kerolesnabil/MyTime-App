<?php

namespace App\Http\Requests;

use App\Models\Lang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFinancialReqRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {

        $rules =  [
            "notes"                               => "string",
            "status"                              => "required",
            'withdrawal_confirmation_receipt_img' => "images|mimes:jpg,jpeg,png|max:3072"

        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'notes.string'                              => __('site_financial_transactions.rule_notes_string'),
            'status.required'                           => __('site_financial_transactions.rule_status_required'),
            'withdrawal_confirmation_receipt_img.images' => __('site_financial_transactions.rule_withdrawal_img_image'),
            'withdrawal_confirmation_receipt_img.mimes' => __('site_financial_transactions.rule_withdrawal_img_mimes'),
            'withdrawal_confirmation_receipt_img.max'   => __('site_financial_transactions.rule_withdrawal_img_max'),
        ];
    }

}
