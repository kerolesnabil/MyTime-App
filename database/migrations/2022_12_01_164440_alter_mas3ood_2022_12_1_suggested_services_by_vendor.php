<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022121SuggestedServicesByVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('services_suggested_by_vendor', 'service_suggested_status')) {

            DB::statement("
                ALTER TABLE `services_suggested_by_vendor` ADD `service_suggested_status` TINYINT NULL DEFAULT NULL
                COMMENT 'null => pending, 0 => rejected, 1 => approved' AFTER `service_suggested_name`;
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
