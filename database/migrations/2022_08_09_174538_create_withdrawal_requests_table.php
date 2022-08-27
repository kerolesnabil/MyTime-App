<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('withdrawal_requests')) {
            return;
        }

		Schema::create('withdrawal_requests', function(Blueprint $table)
		{
			$table->integer('withdrawal_id', true);
			$table->integer('user_id')->index('user_id');
			$table->integer('payment_method_id')->index('payment_method_id');
			$table->string('transaction_code', 300)->unique('transaction_code');
			$table->decimal('withdrawal_amount_before_charged');
			$table->decimal('withdrawal_charged')->comment('discount value');
			$table->decimal('withdrawal_amount_after_charged');
			$table->string('withdrawal_status', 300);
			$table->text('withdrawal_notes')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('withdrawal_requests', function(Blueprint $table)
        {
            $table->foreign('user_id', 'withdrawal_requests_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('payment_method_id', 'withdrawal_requests_ibfk_2')->references('payment_method_id')->on('payment_methods')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
