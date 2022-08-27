<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('vendor_details')) {
            return;
        }

		Schema::create('vendor_details', function(Blueprint $table)
		{
			$table->integer('vendor_details_id', true);
			$table->integer('user_id')->index('user_id')->comment('user type vendor');
			$table->enum('vendor_type', array('salon','specialist'))->comment('at salon or home ');
			$table->text('vendor_available_days');
			$table->time('vendor_start_time');
			$table->time('vendor_end_time');
			$table->string('vendor_commercial_registration_num', 300);
			$table->string('vendor_tax_num', 300);
			$table->text('vendor_description')->nullable();
			$table->text('vendor_slider')->nullable();
			$table->float('vendor_reviews_sum', 10, 0)->default(0);
			$table->float('vendor_reviews_count', 10, 0)->default(0);
			$table->float('vendor_views_count', 10, 0)->default(0);
			$table->text('vendor_categories_ids')->nullable();
			$table->timestamps();
            $table->softDeletes();
		});

        Schema::table('vendor_details', function(Blueprint $table)
        {
            $table->foreign('user_id', 'vendor_details_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
