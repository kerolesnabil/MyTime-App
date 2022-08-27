<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('orders')) {
            return;
        }

		Schema::create('orders', function(Blueprint $table)
		{
			$table->integer('order_id', true);
			$table->integer('user_id')->index('user_id')->comment('who make order ');
			$table->integer('vendor_id')->index('user_type_vendor_id')->comment('user type vendor ');
			$table->integer('payment_method_id')->index('payment_method_id');
			$table->string('order_address', 300);
			$table->string('order_phone', 20);
			$table->string('order_type', 300)->comment('home or salon');
			$table->text('order_notes');
			$table->string('order_status', 300);
			$table->boolean('is_paid')->default(0)->comment('0 => not paid, 1=> paid');
			$table->decimal('order_total_items_price_before_discount');
			$table->decimal('order_total_discount');
			$table->decimal('order_total_items_price_after_discount');
			$table->decimal('order_taxes_rate')->comment('precent');
			$table->decimal('order_taxes_cost')->comment('value');
			$table->date('order_custom_date');
			$table->time('order_custom_time');
			$table->decimal('order_total_price');
			$table->decimal('order_app_profit');
			$table->string('order_lat', 5)->nullable();
			$table->string('order_long', 50)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('orders', function(Blueprint $table)
        {
            $table->foreign('user_id', 'orders_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('vendor_id', 'orders_ibfk_2')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('payment_method_id', 'orders_ibfk_3')->references('payment_method_id')->on('payment_methods')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
