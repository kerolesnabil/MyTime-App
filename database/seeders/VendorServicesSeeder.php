<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 1)->where('service_id',1)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 1,
                    'service_id'                      => 1,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),

                ]
            );
        }

        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 1)->where('service_id',2)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 1,
                    'service_id'                      => 2,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }
        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 1)->where('service_id',3)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 1,
                    'service_id'                      => 3,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1200,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),

                ]
            );
        }

        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 1)->where('service_id',4)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 1,
                    'service_id'                      => 4,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }


        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 2)->where('service_id',1)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 2,
                    'service_id'                      => 1,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 2)->where('service_id',2)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 2,
                    'service_id'                      => 2,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }


        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 3)->where('service_id',1)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 3,
                    'service_id'                      => 1,
                    'service_title'                   => null,
                    'service_price_at_salon'          => null,
                    'service_discount_price_at_salon' => null,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),

                ]
            );
        }

        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 4)->where('service_id',2)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 4,
                    'service_id'                      => 2,
                    'service_title'                   => null,
                    'service_price_at_salon'          => null,
                    'service_discount_price_at_salon' => null,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }
        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 4)->where('service_id',4)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 4,
                    'service_id'                      => 4,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 5)->where('service_id',3)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 5,
                    'service_id'                      => 3,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }


        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 1)->where('service_id',5)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 1,
                    'service_id'                      => 5,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }


        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 1)->where('service_id',6)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 1,
                    'service_id'                      => 6,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 1)->where('service_id',7)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 1,
                    'service_id'                      => 7,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $vendorServiceCheck = DB::table('vendor_services')->where("vendor_id", 1)->where('service_id',8)->get()->first();
        if (!is_object($vendorServiceCheck)){

            DB::table('vendor_services')->insert(
                [
                    'vendor_id'                       => 1,
                    'service_id'                      => 8,
                    'service_title'                   => null,
                    'service_price_at_salon'          => 1000,
                    'service_discount_price_at_salon' => 900,
                    'service_price_at_home'           => 2000,
                    'service_discount_price_at_home'  => 1900,
                    'created_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                      => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

    }
}
