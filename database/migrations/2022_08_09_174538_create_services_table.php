<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('services')) {
            return;
        }

		Schema::create('services', function(Blueprint $table)
		{
			$table->integer('service_id', true);
			$table->integer('cat_id')->nullable()->index('cat_id');
			$table->integer('vendor_id')->nullable()->comment('added when insert package ');
			$table->text('service_name');
			$table->enum('service_type', array('service','package'));
			$table->string('package_services_ids', 300)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('services', function(Blueprint $table)
        {
            $table->foreign('cat_id', 'services_ibfk_1')->references('cat_id')->on('categories')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
