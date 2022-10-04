<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022104AlterFinancialRequestsDelteColFinancialRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('financial_requests', 'user_bank_account')) {

            DB::statement("
              ALTER TABLE `financial_requests` DROP `user_bank_account`;
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
