<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('order_reviews')) {
            return;
        }

		Schema::create('order_reviews', function(Blueprint $table)
		{
			$table->integer('order_review_id', true);
			$table->integer('user_id');
            $table->integer('vendor_id');
			$table->integer('order_id');
			$table->boolean('rate');
			$table->text('review_comment');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('order_reviews', function(Blueprint $table)
        {
            $table->foreign('user_id', 'order_reviews_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('vendor_id', 'order_reviews_ibfk_2')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('order_id', 'order_reviews_ibfk_3')->references('order_id')->on('orders')->onUpdate('RESTRICT')->onDelete('RESTRICT');

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
