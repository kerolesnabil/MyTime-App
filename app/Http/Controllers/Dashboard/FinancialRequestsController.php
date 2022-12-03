<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\UpdateFinancialRequest;
use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFinancialReqRequest;
use App\Models\FinancialRequests;
use \Illuminate\Http\Request;

class FinancialRequestsController extends Controller
{

    public function showDepositRequests(Request $request)
    {
        $requestType = 'deposit';
        $financial_requests =  FinancialRequests::getFinancialRequestsWithType('deposit', $request->all());
        return view('dashboard.financial_requests.show_financial_requests')->with(['requests' => $financial_requests, 'request_type' => $requestType]);
    }


    public function showWithdrawalRequests(Request $request)
    {
        $requestType = 'withdrawal';
        $financial_requests =  FinancialRequests::getFinancialRequestsWithType('withdrawal', $request->all());
        return view('dashboard.financial_requests.show_financial_requests')->with(['requests' => $financial_requests, 'request_type' => $requestType]);
    }


    public function getFinancialRequest(Request $request, $requestId)
    {
        $financialRequest = FinancialRequests::getFinancialRequestsByRequestId($requestId);

        if (is_null($financialRequest)){
            session()->flash('warning', __('site_financial_transactions.request_not_found'));
            return redirect()->back();
        }

        if ($financialRequest->status != null){
            session()->flash('warning', __('site_financial_transactions.request_status_not_waiting'));
            return redirect()->back();
        }

        return view('dashboard.financial_requests.update_financial_request')->with(['request' => $financialRequest, 'request_type' => $financialRequest->request_type]);
    }


    public function updateFinancialRequest(UpdateFinancialReqRequest $request, $requestId)
    {

        $data['status'] = $request['status'];
        $data['notes'] = $request['notes'];

        if (isset($request['withdrawal_confirmation_receipt_img']) && !is_null($request['withdrawal_confirmation_receipt_img'])){
            $data["withdrawal_confirmation_receipt_img"] = ImgHelper::uploadImage('images', $request['withdrawal_confirmation_receipt_img']);
        }

        FinancialRequests::updateFinancialRequest($data, $requestId);
        if ($request['status'] == 1){
            event(new UpdateFinancialRequest(
                $requestId,
                $request['request_type'],
                $request['status']
            ));
        }
        session()->flash('success', __('site.created_successfully'));

        if ($request['request_type'] == 'deposit'){

            return redirect(route('financial_request.show_deposit_requests'));
        }
        else{
            return redirect(route('financial_request.show_withdrawal_requests'));
        }




    }
}
