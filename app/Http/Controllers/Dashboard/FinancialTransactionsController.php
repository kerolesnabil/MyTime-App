<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TransactionLog;

class FinancialTransactionsController extends Controller
{

    public function index()
    {
        $transactionsLogs = TransactionLog::getAllTransactionsLogs(20);


        return view('dashboard.financial_transactions.transaction_log')->with(['logs' => $transactionsLogs]);
    }


}
