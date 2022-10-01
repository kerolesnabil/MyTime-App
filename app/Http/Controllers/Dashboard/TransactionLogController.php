<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FinancialRequests;
use App\Models\TransactionLog;

class TransactionLogController extends Controller
{



    public function index()
    {
        $logs =  TransactionLog::getTransactionsLogs(20);

        return view('dashboard.transactions_logs.index')->with(['logs' => $logs]);
    }

}
