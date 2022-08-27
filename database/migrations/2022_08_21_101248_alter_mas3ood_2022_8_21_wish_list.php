<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022821WishList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('wish_list')) {

            DB::statement("
                ALTER TABLE wish_list DROP FOREIGN KEY wish_list_ibfk_2;         
            ");

            DB::statement("
                  ALTER TABLE `wish_list` ADD FOREIGN KEY (`service_id`) 
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
