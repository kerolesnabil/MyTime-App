<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood202293LangsEditInIsActiveCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('langs', 'lang_is_active')) {

            DB::statement("
              ALTER TABLE `langs` CHANGE `lang_is_active` `lang_is_active` TINYINT(1) 
              NOT NULL DEFAULT '1' COMMENT '0 => not active, 1 => active';
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
        Schema::table('is_active_col', function (Blueprint $table) {
            //
        });
    }
}
