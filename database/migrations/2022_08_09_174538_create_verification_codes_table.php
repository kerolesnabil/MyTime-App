<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('verification_codes')) {
            return;
        }

		Schema::create('verification_codes', function(Blueprint $table)
		{
			$table->increments('verification_code_id');
			$table->integer('user_id')->comment('code sent to user => (user, vendor)');
			$table->string('code', 10);
			$table->dateTime('verification_code_expire_time');
			$table->softDeletes();
			$table->timestamps();
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
