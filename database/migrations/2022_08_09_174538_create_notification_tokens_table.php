<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('notification_tokens')) {
            return;
        }

		Schema::create('notification_tokens', function(Blueprint $table)
		{
			$table->integer('not_token_id', true);
			$table->integer('user_id')->index('user_id');
			$table->string('token', 300);
			$table->string('device_type', 300);
			$table->string('app_version_id', 300);
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('notification_tokens', function(Blueprint $table)
        {
            $table->foreign('user_id', 'notification_tokens_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
