<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesSuggestedByVendorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('services_suggested_by_vendor')) {
            return;
        }

		Schema::create('services_suggested_by_vendor', function(Blueprint $table)
		{
			$table->integer('service_suggested_id', true);
			$table->integer('vendor_id')->index('services_suggested_by_vendors_ibfk_1')->comment('user type vendor');
			$table->string('main_cat_suggested', 300);
			$table->string('sub_cat_suggested', 300)->nullable();
			$table->integer('service_suggested_name');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('services_suggested_by_vendor', function(Blueprint $table)
        {
            $table->foreign('vendor_id', 'services_suggested_by_vendor_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
