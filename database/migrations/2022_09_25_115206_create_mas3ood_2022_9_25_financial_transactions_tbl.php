<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMas3ood2022925FinancialTransactionsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('financial_transactions')) {
            Schema::create('financial_transactions', function(Blueprint $table)
            {
                $table->integer('f_t_id', true);
                $table->integer('user_id')->index('user_id');
                $table->integer('payment_method_id')->index('payment_method_id');
                $table->decimal('amount')->comment('amount value');
                $table->boolean('status')->nullable()->comment('null => waiting, 0 => not approved, 1 => approved');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            Schema::table('withdrawal_requests', function(Blueprint $table)
            {
                $table->foreign('user_id', 'financial_transactions_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
                $table->foreign('payment_method_id', 'financial_transactions_ibfk_2')->references('payment_method_id')->on('payment_methods')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            });


        }






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
