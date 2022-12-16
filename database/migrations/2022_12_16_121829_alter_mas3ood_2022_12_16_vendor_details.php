<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221216VendorDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('vendor_details', 'vendor_app_profit_percentage')) {
            DB::statement("
                ALTER TABLE `vendor_details` ADD `vendor_app_profit_percentage` VARCHAR(20) NOT NULL AFTER `vendor_categories_ids`;
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
