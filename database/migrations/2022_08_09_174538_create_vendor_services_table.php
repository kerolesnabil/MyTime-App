<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('vendor_services')) {
            return;
        }

		Schema::create('vendor_services', function(Blueprint $table)
		{
			$table->integer('vendor_service_id', true);
			$table->integer('vendor_id')->index('vendors_services_ibfk_1');
			$table->integer('service_id')->index('service_id');
			$table->text('service_title')->nullable();
			$table->decimal('service_price_at_salon')->nullable();
			$table->decimal('service_discount_price_at_salon')->nullable();
			$table->decimal('service_price_at_home');
			$table->decimal('service_discount_price_at_home');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('vendor_services', function(Blueprint $table)
        {
            $table->foreign('vendor_id', 'vendor_services_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('service_id', 'vendor_services_ibfk_2')->references('service_id')->on('services')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
