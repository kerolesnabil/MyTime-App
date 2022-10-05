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
               RENAME TABLE `my_time`.`financial_transactions` 
               TO `my_time`.`financial_requests`;
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
