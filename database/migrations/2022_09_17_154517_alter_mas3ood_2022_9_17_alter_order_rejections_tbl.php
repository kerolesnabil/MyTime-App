<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022917AlterOrderRejectionsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('order_rejections')) {

            DB::statement("
              ALTER TABLE order_rejections DROP FOREIGN KEY order_rejections_ibfk_2;
            ");


            DB::statement("
             ALTER TABLE `order_rejections` ADD FOREIGN KEY (`rejection_reason`) 
             REFERENCES `order_rejections_reasons`(`rejection_reason_id`) 
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
