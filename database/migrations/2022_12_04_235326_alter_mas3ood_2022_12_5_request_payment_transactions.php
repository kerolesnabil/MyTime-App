<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022125RequestPaymentTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('request_payment_transactions', 'invoice_id')) {
            DB::statement("
                ALTER TABLE `request_payment_transactions` ADD `invoice_id` VARCHAR(300) NOT NULL AFTER `id`;
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
