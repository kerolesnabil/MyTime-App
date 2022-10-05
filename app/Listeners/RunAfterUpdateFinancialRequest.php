<?php

namespace App\Listeners;

use App\Events\CreateAd;
use App\Events\UpdateFinancialRequest;
use App\Models\FinancialRequests;
use App\Models\TransactionLog;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterUpdateFinancialRequest
{


    public function handle(UpdateFinancialRequest $event)
    {

        // deposit 1 => increase wallet by user id
        // deposit 2 => create log

        // deposit 1 => decrease wallet by user id
        // deposit 2 => create log

        $request =  FinancialRequests::getFinancialRequestsByRequestId($event->requestId);

        if (!is_null($request)){

            $userWallet = User::getUserWallet($request->user_id);

            if ($event->requestType == 'deposit'){
                $newWalletAmount       = $userWallet->user_wallet + $request->amount;
                $transactionOperation = 'increase';
                $arMsg                 = " تم ايداع مبلغ $request->amount ريال سعودي ";
                $enMsg                 = "amount has been deposited $request->amount SAR";

            }
            else{
                $newWalletAmount       = $userWallet->user_wallet - $request->amount;
                $transactionOperation = 'decrease';
                $arMsg                 = " تم سحب مبلغ $request->amount ريال سعودي ";
                $enMsg                 = "amount has been withdrawn $request->amount SAR";
            }

            User::updateWalletOfUser($request->user_id, $newWalletAmount);

            $data['user_id']               = $request->user_id;
            $data['amount']                = $request->amount;
            $data['transaction_operation'] = $transactionOperation;
            $data['transaction_notes']     = '{"ar":"'.$arMsg.'", "en":"'.$enMsg.'"}';
            TransactionLog::createTransactionsLog($data);

        }





    }
}
