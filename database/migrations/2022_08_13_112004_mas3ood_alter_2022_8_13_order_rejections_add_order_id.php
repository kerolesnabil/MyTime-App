<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mas3oodAlter2022813OrderRejectionsAddOrderId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('order_rejections', 'order_id')) {

            DB::statement("
                ALTER TABLE `order_rejections` 
                ADD `order_id` 
                INT NOT NULL AFTER `rejection_reason`;
            ");

            DB::statement("
                ALTER TABLE `order_rejections` 
                ADD FOREIGN KEY (`order_id`) 
                REFERENCES `orders`(`order_id`) 
                ON DELETE RESTRICT ON UPDATE RESTRICT;
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
