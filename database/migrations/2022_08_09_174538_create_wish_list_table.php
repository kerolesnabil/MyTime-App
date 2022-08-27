<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('wish_list')) {
            return;
        }

		Schema::create('wish_list', function(Blueprint $table)
		{
			$table->integer('wish_list_id', true);
			$table->integer('user_id')->index('user_id')->comment('user type normal user');
			$table->integer('vendor_id')->index('vendor_id');
			$table->integer('service_id')->index('service_id');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('wish_list', function(Blueprint $table)
        {
            $table->foreign('user_id', 'wish_list_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('service_id', 'wish_list_ibfk_2')->references('service_id')->on('services')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('vendor_id', 'wish_list_ibfk_3')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
