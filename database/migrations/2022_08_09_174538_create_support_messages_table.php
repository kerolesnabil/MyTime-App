<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('support_messages')) {
            return;
        }

		Schema::create('support_messages', function(Blueprint $table)
		{
			$table->integer('support_message_id', true);
			$table->integer('user_id')->index('user_id')->comment('user or vendor ');
			$table->string('user_name', 300);
			$table->string('phone', 20);
			$table->text('message');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('support_messages', function(Blueprint $table)
        {
            $table->foreign('user_id', 'support_messages_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
