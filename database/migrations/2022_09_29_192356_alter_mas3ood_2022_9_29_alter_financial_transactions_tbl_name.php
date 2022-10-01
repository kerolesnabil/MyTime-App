<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022929AlterFinancialTransactionsTblName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('financial_transactions')) {

            DB::statement("
               RENAME TABLE `app_my_time_test2`.`financial_transactions` 
               TO `app_my_time_test2`.`financial_requests`;
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
