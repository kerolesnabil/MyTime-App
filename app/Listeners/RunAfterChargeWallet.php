<?php

namespace App\Listeners;

use App\Events\ChargeWallet;
use App\Events\CreateAd;
use App\Models\TransactionLog;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterChargeWallet
{


    public function handle(ChargeWallet $event)
    {
        // increase wallet of vendor
        $userObj        = User::getUserById($event->userId);
        $newWalletValue = $userObj->user_wallet + $event->amountWillCharge;

        User::updateWalletOfUser($event->userId, $newWalletValue);

        // create transaction log
        $arMsg = " تم ايداع مبلغ $event->amountWillCharge ريال سعودي ";
        $enMsg = "amount has been deposited $event->amountWillCharge SAR";

        $data['user_id']               = $event->userId;
        $data['amount']                = $event->amountWillCharge;
        $data['transaction_operation'] = 'increase';
        $data['transaction_notes']     = $event->notes;
        TransactionLog::createTransactionsLog($data);
    }
}
