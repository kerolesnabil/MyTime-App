<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mas3ood202293CreateOrderRejectionsReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('order_rejection_reasons')) {
            return;
        }

        Schema::create('order_rejection_reasons', function(Blueprint $table)
        {
            $table->integer('rejection_reason_id', true);
            $table->text('rejection_reason');
            $table->timestamps();
            $table->softDeletes();
        });
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
