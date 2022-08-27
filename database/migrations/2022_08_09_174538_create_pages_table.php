<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('pages')) {
            return;
        }

		Schema::create('pages', function(Blueprint $table)
		{
			$table->integer('page_id', true);
			$table->integer('page_title');
			$table->text('page_content');
			$table->text('img')->nullable();
			$table->enum('page_position', array('first','last'));
			$table->boolean('is_active')->default(1)->comment('0 => not active, 1 => active');
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
