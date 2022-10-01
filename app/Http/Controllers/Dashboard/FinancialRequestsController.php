<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FinancialRequests;
use \Illuminate\Http\Request;

class FinancialRequestsController extends Controller
{



    public function showDepositRequests()
    {
        $requestType = 'deposit';
        $financial_requests =  FinancialRequests::getFinancialRequestsWithType('deposit');
        return view('dashboard.financial_requests.show_financial_requests')->with(['requests' => $financial_requests, 'request_type' => $requestType]);
    }


    public function showWithdrawalRequests()
    {
        $requestType = 'withdrawal';
        $financial_requests =  FinancialRequests::getFinancialRequestsWithType('withdrawal');
        return view('dashboard.financial_requests.show_financial_requests')->with(['requests' => $financial_requests, 'request_type' => $requestType]);
    }


    public function handleActionOnFinancialRequest(Request $request)
    {

        if ($request['status'] == 1){
            // validation
            //approve


        }
        else{
            //reject


        }



            return $request->all();

    }


    private function checkI


    private function approveRequest()
    {
    }

    private function rejectRequest()
    {
    }


}
