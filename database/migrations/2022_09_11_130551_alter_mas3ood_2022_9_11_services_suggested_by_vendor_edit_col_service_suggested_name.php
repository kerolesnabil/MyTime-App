<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022911ServicesSuggestedByVendorEditColServiceSuggestedName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('services_suggested_by_vendor', 'service_suggested_name')) {

            DB::statement("
                ALTER TABLE `services_suggested_by_vendor` CHANGE `service_suggested_name` `service_suggested_name`
                 VARCHAR(300) NOT NULL;
            ");

        }
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
