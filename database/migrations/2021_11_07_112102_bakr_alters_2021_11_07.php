<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BakrAlters20211107 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        if (
//            !Schema::hasColumn('agency_margin_history_log', 'user_id')
//        ) {
//
//            DB::statement("
//                ALTER TABLE `agency_margin_history_log` ADD `user_id` INT NOT NULL COMMENT 'the admin user id who made this change' AFTER `id`;
//            ");
//
//        }
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
