<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('payment_methods')) {
            return;
        }

		Schema::create('payment_methods', function(Blueprint $table)
		{
			$table->integer('payment_method_id', true);
			$table->text('payment_method_name');
			$table->string('payment_method_type', 50)->comment('online or cash or wallet');
			$table->boolean('is_active')->default(1)->comment('0 => not active, 1=> active');
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
