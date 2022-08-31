<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mas3ood2022830CreateLangs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('langs')) {
            return;
        }

        Schema::create('langs', function(Blueprint $table)
        {
            $table->integer('lang_id', true);
            $table->string('lang_symbol', 3);
            $table->string('lang_name', 100);
            $table->string('lang_direction', 100)->comment('lrt , rtl');
            $table->string('lang_img', 300);
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
        //
    }
}
