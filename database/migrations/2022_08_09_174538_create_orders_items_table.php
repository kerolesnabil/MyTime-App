<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('orders_items')) {
            return;
        }

		Schema::create('orders_items', function(Blueprint $table)
		{
			$table->integer('order_item_id', true);
			$table->integer('order_id')->index('order_id');
			$table->integer('item_id')->comment('service_id or service_id');
			$table->enum('item_type', array('service','package'))->comment('service, package');
			$table->decimal('item_price_before_discount');
			$table->decimal('item_price_after_discount');
			$table->integer('item_count');
			$table->decimal('item_total_price_before_discount');
			$table->decimal('item_total_price_after_discount');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('orders_items', function(Blueprint $table)
        {
            $table->foreign('order_id', 'orders_items_ibfk_1')->references('order_id')->on('orders')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
