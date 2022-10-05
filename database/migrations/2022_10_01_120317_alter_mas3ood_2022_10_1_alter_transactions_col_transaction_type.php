<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022101AlterTransactionsColTransactionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('transactions_log', 'transaction_type')) {

            DB::statement("
               ALTER TABLE `transactions_log` CHANGE `transaction_type` `transaction_operation` ENUM('increase','decrease') 
               CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
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
