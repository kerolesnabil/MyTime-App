<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsUsedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('coupons_used')) {
            return;
        }

		Schema::create('coupons_used', function(Blueprint $table)
		{
			$table->integer('coupon_used_id', true);
			$table->integer('user_id');
			$table->integer('coupon_id');
			$table->integer('order_id');
			$table->decimal('coupon_value', 10, 0)->comment('value not percent');
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
