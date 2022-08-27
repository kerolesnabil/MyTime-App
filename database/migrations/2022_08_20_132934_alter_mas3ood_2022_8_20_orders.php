<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022820Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('orders', 'order_address')) {

            DB::statement("
                ALTER TABLE `orders` CHANGE `order_address` `order_address` 
                VARCHAR(300) 
                CHARACTER SET utf8mb4 
                COLLATE utf8mb4_unicode_ci NULL;            
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
