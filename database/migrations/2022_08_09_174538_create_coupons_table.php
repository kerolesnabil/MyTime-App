<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('coupons')) {
            return;
        }

		Schema::create('coupons', function(Blueprint $table)
		{
			$table->integer('coupon_id', true);
			$table->string('coupon_code', 300)->unique('coupon_code');
			$table->decimal('coupon_value')->comment('percent or value');
			$table->date('coupon_start_at');
			$table->date('coupon_end_at');
			$table->enum('coupon_type', array('percent','value'));
			$table->integer('coupon_limited_num');
			$table->integer('coupon_used_times')->default(0);
			$table->boolean('is_active')->default(0)->comment('0 => not active, 1 => active');
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
