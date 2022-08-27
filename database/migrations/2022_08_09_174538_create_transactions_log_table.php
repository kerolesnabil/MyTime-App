<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('transactions_log')) {
            return;
        }

		Schema::create('transactions_log', function(Blueprint $table)
		{
			$table->integer('log_id', true);
			$table->integer('user_id');
			$table->enum('transaction_type', array('deposit','withdrawal'));
			$table->decimal('amount')->comment(' amount deposited or withdrawn');
			$table->string('status', 300);
			$table->text('transaction_notes')->nullable();
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
