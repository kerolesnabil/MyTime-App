<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022817OrderCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('order_carts', 'service_location')) {

            DB::statement("
             ALTER TABLE `order_carts` 
             ADD `service_location` 
             VARCHAR(300) NOT NULL 
             COMMENT 'salon or home' 
             AFTER `vendor_service_id`;
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
