<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('ads')) {
            return;
        }

		Schema::create('ads', function(Blueprint $table)
		{
			$table->integer('ad_id', true);
			$table->integer('vendor_id')->index('user_type_vendor_id')->comment('who make ad');
			$table->string('ad_title', 300);
			$table->integer('ad_days');
			$table->date('ad_start_at');
			$table->date('ad_end_at');
			$table->integer('ad_cost');
			$table->string('ad_img', 300);
			$table->boolean('ad_at_homepage')->default(0)->comment('0 => no , 1=> yes');
			$table->boolean('ad_at_discover_page')->default(0)->comment('0 => no, 1=> yes');
			$table->timestamps();
			$table->softDeletes();
		});

        Schema::table('ads', function(Blueprint $table)
        {
            $table->foreign('vendor_id', 'ads_ibfk_1')->references('user_id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
