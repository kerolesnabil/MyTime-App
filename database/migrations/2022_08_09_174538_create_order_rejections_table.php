<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRejectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('order_rejections')) {
            return;
        }

		Schema::create('order_rejections', function(Blueprint $table)
		{
			$table->integer('rejection_id', true);
            $table->integer('order_id')->index('order_id');
            $table->string('rejection_reason', 300);
			$table->text('rejection_reason_msg')->nullable();
			$table->text('suggested_date');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('order_rejections', function(Blueprint $table)
        {
            $table->foreign('order_id', 'order_rejections_ibfk_1')->references('order_id')->on('orders')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
