<?php

namespace App\Listeners;

use App\Events\ChargeWallet;
use App\Events\CreateAd;
use App\Events\DecreaseWallet;
use App\Models\TransactionLog;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterDecreaseWallet
{


    public function handle(DecreaseWallet $event)
    {
        // decrease wallet
        $userObj        = User::getUserById($event->userId);
        $newWalletValue = $userObj->user_wallet - $event->amountWillDecrease;

        User::updateWalletOfUser($event->userId, $newWalletValue);

        // create transaction log

        $data['user_id']               = $event->userId;
        $data['amount']                = $event->amountWillDecrease;
        $data['transaction_operation'] = 'decrease';
        $data['transaction_notes']     = $event->notes;
        TransactionLog::createTransactionsLog($data);
    }
}
