<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mas3ood2022124CreateRequestPaymentTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('request_payment_transactions')) {
            return;
        }


        Schema::create('request_payment_transactions', function(Blueprint $table)
        {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('order_id')->nullable();
            $table->string('request_type', 300)->comment('order, charge_wallet');
            $table->decimal('amount')->comment('amount value');
            $table->text('request_headers')->nullable();
            $table->text('request_body')->nullable();
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

    }
}
