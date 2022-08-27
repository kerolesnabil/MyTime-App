<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022821WishListAddServiceLocationCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('wish_list', 'service_location')) {

            DB::statement("
                ALTER TABLE `wish_list` ADD `service_location` 
                VARCHAR(300) NOT NULL COMMENT 'home or salon' AFTER `service_id`;       
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
