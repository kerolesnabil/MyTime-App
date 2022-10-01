<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022101AlterFinancialRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('financial_requests', 'transaction_type')) {

            DB::statement("
              ALTER TABLE `financial_requests` CHANGE `transaction_type` `request_type` ENUM('deposit','withdrawal') 
              CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
            ");
        }


        if (!Schema::hasColumn('financial_requests', 'user_bank_account')) {

            DB::statement("
              ALTER TABLE `financial_requests` ADD `user_bank_account` VARCHAR(150) NULL AFTER `status`;
            ");
        }

        if (!Schema::hasColumn('financial_requests', 'bank_name')) {

            DB::statement("
              ALTER TABLE `financial_requests` ADD `bank_name` VARCHAR(300) NULL AFTER `status`;
            ");
        }

        if (!Schema::hasColumn('financial_requests', 'iban_number')) {

            DB::statement("
              ALTER TABLE `financial_requests` ADD `iban_number` VARCHAR(300) NULL AFTER `status`;
            ");
        }

        if (!Schema::hasColumn('financial_requests', 'deposit_receipt_img')) {

            DB::statement("
              ALTER TABLE `financial_requests` ADD `deposit_receipt_img` VARCHAR(300) NULL AFTER `notes`;
            ");
        }

        if (!Schema::hasColumn('financial_requests', 'withdrawal_confirmation_receipt_img')) {

            DB::statement("
              ALTER TABLE `financial_requests` ADD `withdrawal_confirmation_receipt_img` VARCHAR(300) NULL AFTER `notes`;
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
