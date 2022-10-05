<?php

namespace App\Listeners;

use App\Events\CreateAd;
use App\Models\TransactionLog;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAfterCreateAd
{


    public function handle(CreateAd $event)
    {
        // decrease wallet of vendor

        $newWalletValue = $event->walletValue - $event->adCost;

        User::updateWalletOfUser($event->userId, $newWalletValue);

        // create transaction log

        $arMsg = " تم خصم تكلفة الاعلان من المحفظة $event->adCost ريال سعودي ";
        $enMsg = "The cost of advertising has been deducted from the wallet $event->adCost SAR";


        $data['user_id']               = $event->userId;
        $data['amount']                = $event->adCost;
        $data['transaction_operation'] = 'decrease';
        $data['transaction_notes']     = '{"ar":"'.$arMsg.'", "en":"'.$enMsg.'"}';
        TransactionLog::createTransactionsLog($data);

    }
}
