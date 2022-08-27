<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('notifications')) {
            return;
        }

		Schema::create('notifications', function(Blueprint $table)
		{
			$table->integer('not_id', true);
			$table->integer('from_user_id')->index('from_user_id')->comment('who push notification');
			$table->integer('to_user_id')->index('to_user_id');
			$table->integer('order_id')->index('order_id');
			$table->text('not_title');
			$table->text('not_available_actions');
			$table->boolean('is_seen')->default(0)->comment('0 => not active, 1=> active');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('notifications', function(Blueprint $table)
        {
            $table->foreign('from_user_id', 'notifications_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('to_user_id', 'notifications_ibfk_2')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('order_id', 'notifications_ibfk_3')->references('order_id')->on('orders')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
