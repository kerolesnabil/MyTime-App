<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022820OrderCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('orders')) {


            DB::statement("
                ALTER TABLE order_carts DROP FOREIGN KEY order_carts_ibfk_2;         
            ");

            DB::statement("
               ALTER TABLE `order_carts` ADD FOREIGN KEY (`vendor_service_id`) 
               REFERENCES `vendor_services`(`vendor_service_id`) 
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
