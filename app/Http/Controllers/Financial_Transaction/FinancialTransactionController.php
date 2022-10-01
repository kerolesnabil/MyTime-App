<?php

namespace App\Http\Controllers\Financial_Transaction;

use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\FinancialRequests;
use App\Models\PaymentMethod;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FinancialTransactionController extends Controller
{

    public function vendorCreateDepositRequest(Request $request)
    {
        if(Auth::user()->user_type!='vendor'){

            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }
        $rules= [
            "amount"              => "required|integer",
            "deposit_receipt_img" => "image|mimes:jpg,jpeg,png|max:3072",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $payment_obj = PaymentMethod::getPaymentByMethodType('cash');
        $image       = ImgHelper::uploadImage('images', $request['deposit_receipt_img']);

        $data = [
            'user_id'             => Auth::user()->user_id,
            'payment_id'          => $payment_obj['payment_method_id'],
            'amount'              => $request->get('amount'),
            'request_type'        => 'deposit',
            'deposit_receipt_img' => $image
        ];

        $request= FinancialRequests::createDepositRequest($data);

        return ResponsesHelper::returnData($request->f_t_id,'200',trans('payment.send_order'));
    }

    public function vendorCreateWithdrawalRequest(Request $request)
    {

        if(Auth::user()->user_type!='vendor'){

            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }
        $rules= [
            "amount"            => "required|integer",
            "bank_name"         => "required|string",
            "user_bank_account" => "required|string",
            "iban_number"       => "required|string",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $payment_obj = PaymentMethod::getPaymentByMethodType('cash');

        $data = [
            'user_id'           => Auth::user()->user_id,
            'payment_id'        => $payment_obj['payment_method_id'],
            'amount'            => $request->get('amount'),
            'request_type'      => 'withdrawal',
            'bank_name'         => $request->get('bank_name'),
            'user_bank_account' => $request->get('user_bank_account'),
            'iban_number'       => $request->get('iban_number'),
        ];

        $request = FinancialRequests::createWithdrawalRequest($data);
        return ResponsesHelper::returnData($request->f_t_id,'200',trans('payment.send_order'));
    }

    public function showFinancialRequests()
    {
        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }

        $requests = FinancialRequests::getFinancialRequestsByUserId(Auth::user()->user_id);
        return ResponsesHelper::returnData($requests,'200','');
    }

    public function showTransactionLogOfVendor()
    {
        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }

        $logs = TransactionLog::getTransactionsLogsByUserId(Auth::user()->user_id);
        return ResponsesHelper::returnData($logs,'200','');

    }
}
