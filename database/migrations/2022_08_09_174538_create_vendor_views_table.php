<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorViewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('vendor_views')) {
            return;
        }

		Schema::create('vendor_views', function(Blueprint $table)
		{
			$table->integer('view_id', true);
			$table->integer('vendor_id')->index('user_vendor_id')->comment('user type vendor');
			$table->integer('view_count');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('vendor_views', function(Blueprint $table)
        {
            $table->foreign('vendor_id', 'vendor_views_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
