<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022924AlterWithdrawalRequestsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('withdrawal_requests', 'transaction_code')) {

            DB::statement("
               ALTER TABLE `withdrawal_requests` DROP `transaction_code`;
            ");
        }

        if (Schema::hasColumn('withdrawal_requests', 'withdrawal_amount_before_charged')) {

            DB::statement("
               ALTER TABLE `withdrawal_requests` DROP `withdrawal_amount_before_charged`;
            ");
        }

        if (Schema::hasColumn('withdrawal_requests', 'withdrawal_amount_after_charged')) {

            DB::statement("
               ALTER TABLE `withdrawal_requests` DROP `withdrawal_amount_after_charged`;
            ");
        }


        if (Schema::hasColumn('withdrawal_requests', 'withdrawal_charged')) {

            DB::statement("
               ALTER TABLE `withdrawal_requests` CHANGE `withdrawal_charged` 
               `withdrawal_amount` DECIMAL(8,2) NOT NULL;     
            ");
        }

        if (Schema::hasColumn('withdrawal_requests', 'withdrawal_status')) {

            DB::statement("
               ALTER TABLE `withdrawal_requests` CHANGE `withdrawal_status` `withdrawal_status` TINYINT(1) 
               NULL COMMENT 'null => waiting, 0 => not approved, 1 => approved'; 
            ");
        }


        if (Schema::hasColumn('withdrawal_requests', 'withdrawal_notes')) {

            DB::statement("
              ALTER TABLE `withdrawal_requests` CHANGE `withdrawal_notes` `notes` TEXT CHARACTER SET 
              utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
            ");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
