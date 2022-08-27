<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mas3oodAlter2022813OrderRejections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('order_rejections', 'rejection_reason_msg')) {

            DB::statement("
                ALTER TABLE `order_rejections` DROP `rejection_reason_msg`;
            ");
        }

        if (Schema::hasColumn('order_rejections', 'suggested_date')) {

            DB::statement("
                ALTER TABLE `order_rejections` DROP `suggested_date`;
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
