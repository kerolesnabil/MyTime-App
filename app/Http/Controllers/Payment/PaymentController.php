<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\FinancialRequests;
use App\Models\PaymentMethod;
use App\Models\TransactionLog;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\VarDumper;

class PaymentController extends Controller
{

    public function vendorDeposit(Request $request)
    {
        if(Auth::user()->user_type!='vendor'){

            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }
        $rules= [
            "amount"=>"required|integer",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

       $payment_obj = PaymentMethod::getPaymentByMethodType('cash');

        $data = [
            'user_id'          => Auth::user()->user_id,
            'payment_id'       => $payment_obj['payment_method_id'],
            'amount'           => $request->get('amount'),
            'transaction_type' => 'deposit',
        ];

        $request= FinancialRequests::createFinancialRequest($data);

        return ResponsesHelper::returnData($request->f_t_id,'200',trans('payment.send_order'));
    }

    public function vendorWithdrawal(Request $request)
    {

        if(Auth::user()->user_type!='vendor'){

            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }
        $rules= [
            "amount"=>"required|integer",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $payment_obj = PaymentMethod::getPaymentByMethodType('cash');

        $data = [
            'user_id'          => Auth::user()->user_id,
            'payment_id'       => $payment_obj['payment_method_id'],
            'amount'           => $request->get('amount'),
            'transaction_type' => 'withdrawal',
        ];

        $request = FinancialRequests::createFinancialRequest($data);

        return ResponsesHelper::returnData($request->f_t_id,'200',trans('payment.send_order'));
    }


    public function showTransactionLogOfVendor(Request $request)
    {
        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }

        $logs = TransactionLog::getTransactionsLogsByUserId(Auth::user()->user_id);
        return ResponsesHelper::returnData($logs,'200','');

    }

    public function showFinancialRequests(Request $request)
    {
        if(Auth::user()->user_type!='vendor'){
            return ResponsesHelper::returnError('400',trans('vendor.not_vendor'));
        }

        $requests = FinancialRequests::getFinancialRequestsByUserId(Auth::user()->user_id);
        return ResponsesHelper::returnData($requests,'200','');
    }

}
