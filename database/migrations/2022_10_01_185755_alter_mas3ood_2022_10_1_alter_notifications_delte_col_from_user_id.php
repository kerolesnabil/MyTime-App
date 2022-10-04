<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022101AlterNotificationsDelteColFromUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('notifications', 'from_user_id')) {

            DB::statement("
              ALTER TABLE my_time.notifications DROP FOREIGN KEY notifications_ibfk_1;
            ");

            DB::statement("
              ALTER TABLE `notifications` DROP `from_user_id`;
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
        Schema::table('user_id', function (Blueprint $table) {
            //
        });
    }
}
