<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('users')) {
            return;
        }

		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('user_id', true);
			$table->float('user_wallet', 10, 0)->default(0);
			$table->enum('user_type', array('user','vendor','admin','dev'))->comment('user or vendor');
			$table->string('user_name', 300);
			$table->string('user_address', 300)->nullable();
			$table->string('user_phone', 25)->unique('phone');
			$table->dateTime('user_phone_verified_at')->nullable();
			$table->string('user_email', 300);
			$table->date('user_date_of_birth')->nullable();
			$table->string('user_lat', 50)->nullable();
			$table->string('user_long', 50)->nullable();
			$table->integer('user_is_active')->default(1)->comment('0 => not active, 1=> active');
			$table->string('user_img', 300);
			$table->timestamps();
			$table->softDeletes();
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
