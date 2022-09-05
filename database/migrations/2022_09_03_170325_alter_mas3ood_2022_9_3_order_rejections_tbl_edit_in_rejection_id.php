<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood202293OrderRejectionsTblEditInRejectionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('order_rejections', 'rejection_id')) {

            DB::statement("
                
                ALTER TABLE `order_rejections` CHANGE `rejection_reason` `rejection_reason` INT NOT NULL;
    
            ");


            DB::statement("
                ALTER TABLE `order_rejections` ADD FOREIGN KEY (`rejection_id`) 
                REFERENCES `order_rejection_reasons`(`rejection_reason_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
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
        Schema::table('rejection_id', function (Blueprint $table) {
            //
        });
    }
}
