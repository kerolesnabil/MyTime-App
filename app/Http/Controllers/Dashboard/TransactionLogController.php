<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TransactionLog;
use Illuminate\Http\Request;


class TransactionLogController extends Controller
{

    public function index(Request $request)
    {
        $logs =  TransactionLog::getTransactionsLogs(20, $request->all());
        return view('dashboard.transactions_logs.index')->with(['logs' => $logs]);
    }

}
