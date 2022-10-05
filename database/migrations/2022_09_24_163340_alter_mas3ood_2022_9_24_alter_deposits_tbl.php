<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022924AlterDepositsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('deposits', 'transaction_code')) {

            DB::statement("
               ALTER TABLE `deposits` DROP `transaction_code`;
            ");
        }

        if (Schema::hasColumn('deposits', 'status')) {

            DB::statement("
               ALTER TABLE `deposits` CHANGE `status` `status` TINYINT(1) NULL 
               COMMENT 'null => waiting, 0 => not approved, 1 => approved';     
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
