<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('categories')) {
            return;
        }

		Schema::create('categories', function(Blueprint $table)
		{
			$table->integer('cat_id', true);
			$table->integer('parent_id')->default(0)->index('parent_id')->comment('0 => parent , any num => child');
			$table->text('cat_name');
			$table->text('cat_description')->nullable();
			$table->string('cat_img', 300);
			$table->boolean('cat_is_active')->default(0)->comment('0 => is not active, 1 => is active');
			$table->boolean('has_children')->default(0)->comment('0 => is not has children, 1 => is has children');
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
