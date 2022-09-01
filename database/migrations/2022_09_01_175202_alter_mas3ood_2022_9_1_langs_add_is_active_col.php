<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood202291LangsAddIsActiveCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('langs', 'lang_is_active')) {

            DB::statement("
               ALTER TABLE `langs` ADD `lang_is_active` TINYINT(1) 
               NOT NULL DEFAULT '0' COMMENT '0 => not active, 1=> active' AFTER `lang_img`;       
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
