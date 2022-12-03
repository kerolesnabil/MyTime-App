<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022121Ads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('ads', 'status')) {

            DB::statement("
                ALTER TABLE `ads` ADD `status` TINYINT NULL DEFAULT NULL
                COMMENT 'null => pending, 0 =>rejected ,1 => approved' AFTER `ad_at_discover_page`;
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
