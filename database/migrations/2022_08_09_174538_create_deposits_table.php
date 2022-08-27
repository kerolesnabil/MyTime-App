<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('deposits')) {
            return;
        }

		Schema::create('deposits', function(Blueprint $table)
		{
			$table->integer('deposit_id', true);
			$table->integer('user_id')->index('user_id');
			$table->integer('payment_method_id')->index('payment_method_id');
			$table->string('transaction_code', 300)->unique('transaction_code');
			$table->decimal('deposit_amount');
			$table->string('status', 300);
			$table->text('notes')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('deposits', function(Blueprint $table)
        {
            $table->foreign('user_id', 'deposits_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('payment_method_id', 'deposits_ibfk_2')->references('payment_method_id')->on('payment_methods')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
