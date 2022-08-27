<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022822Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('orders', 'rejection_reason_msg')) {

            DB::statement("
                ALTER TABLE `orders` CHANGE `rejection_reason_msg` `reschedule_reason_msg` TEXT 
                CHARACTER SET utf8mb4 
                COLLATE utf8mb4_unicode_ci 
                NULL DEFAULT NULL;      
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
