<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mas3oodAlter2022812Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('orders', 'reject_id')) {

            DB::statement("
                ALTER TABLE `orders` DROP FOREIGN KEY `orders_ibfk_4`;
            ");

            DB::statement("
                ALTER TABLE `orders` DROP `reject_id`;
            ");

        }

        if (!Schema::hasColumn('orders', 'rejection_reason_msg')) {

            DB::statement("
                ALTER TABLE `orders` ADD `rejection_reason_msg` TEXT NULL AFTER `order_long`;
            ");

        }

        if (!Schema::hasColumn('orders', 'suggested_date_by_vendor')) {

            DB::statement("
                ALTER TABLE `orders` ADD `suggested_date_by_vendor` TEXT NULL AFTER `rejection_reason_msg`;            ");

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
