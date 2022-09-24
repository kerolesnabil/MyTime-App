<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TransactionLog;

class TransactionLogController extends Controller
{

    public function index()
    {
        $transactionsLogs = TransactionLog::getAllTransactionsLogs(20);
        return view('dashboard.payment_methods.index')->with(['logs' => $transactionsLogs]);
    }


}
