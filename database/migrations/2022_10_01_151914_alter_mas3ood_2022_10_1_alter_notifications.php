<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022101AlterNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('notifications', 'order_id')) {


            DB::statement("
               ALTER TABLE my_time.notifications DROP FOREIGN KEY notifications_ibfk_3;
            ");

            DB::statement("
               ALTER TABLE `notifications` DROP `order_id`;
            ");
        }

        if (Schema::hasColumn('notifications', 'not_available_actions')) {

            DB::statement("
               ALTER TABLE `notifications` CHANGE `not_available_actions` `action` TEXT 
               CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
            ");
        }

        if (Schema::hasColumn('notifications', 'is_seen')) {

            DB::statement("
               ALTER TABLE `notifications` CHANGE `is_seen` `is_seen` TINYINT(1)
                NOT NULL DEFAULT '0' COMMENT '0 => no seen, 1=> seen';
            ");
        }

        if (!Schema::hasColumn('notifications', 'not_type')) {

            DB::statement("
               ALTER TABLE `notifications` ADD `not_type` VARCHAR(100) NOT NULL AFTER `not_title`;
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
