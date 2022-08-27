<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mas3ood2022814AddOrderCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('order_carts')) {
            return;
        }

        Schema::create('order_carts', function(Blueprint $table)
        {
            $table->integer('order_cart_id', true);
            $table->integer('user_id')->index('user_id')->comment('user type normal user');
            $table->integer('vendor_id')->index('vendor_id');
            $table->integer('vendor_service_id')->index('vendor_service_id');
            $table->integer('service_quantity');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('order_carts', function(Blueprint $table)
        {
            $table->foreign('user_id', 'order_carts_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('vendor_id', 'order_carts_ibfk_3')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('vendor_service_id', 'order_carts_ibfk_2')->references('service_id')->on('services')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
