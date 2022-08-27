<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mas3oodAlter2022813Pages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('pages', 'show_in_user_app')) {

            DB::statement("
                ALTER TABLE `pages` 
                ADD `show_in_user_app` TINYINT(1) 
                NOT NULL DEFAULT '0' AFTER `page_position`;
            ");
        }

        if (!Schema::hasColumn('pages', 'show_in_vendor_app')) {

            DB::statement("
                ALTER TABLE `pages` 
                ADD `show_in_vendor_app` TINYINT(1) 
                NOT NULL DEFAULT '0' AFTER `show_in_user_app`;
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
