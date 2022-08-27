<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022827Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'is_rated')) {

            DB::statement("
               ALTER TABLE `orders` ADD `is_rated` TINYINT(1) 
               NOT NULL DEFAULT '0' COMMENT '0 => no , 1=> yes' AFTER `suggested_date_by_vendor`; 
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
