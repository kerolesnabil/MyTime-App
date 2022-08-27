<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $adsCheck = DB::table('ads')->get();
        if (!count($adsCheck)) {
            DB::table('ads')->insert(
                [

                    [
                        'vendor_id'           => 1,
                        'ad_title'            => 'ad_title 2',
                        'ad_days'             => 19,
                        'ad_start_at'         => '2022-08-01',
                        'ad_end_at'           => '2022-08-20',
                        'ad_cost'             => 300,
                        'ad_img'              => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'ad_at_homepage'      => 1,
                        'ad_at_discover_page' => 1,
                        'created_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'vendor_id'           => 2,
                        'ad_title'            => 'ad_title 2',
                        'ad_days'             => 29,
                        'ad_start_at'         => '2022-08-01',
                        'ad_end_at'           => '2022-08-30',
                        'ad_cost'             => 350,
                        'ad_img'              => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'ad_at_homepage'      => 1,
                        'ad_at_discover_page' => 1,
                        'created_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'vendor_id'           => 3,
                        'ad_title'            => 'ad_title 3',
                        'ad_days'             => 24,
                        'ad_start_at'         => '2022-08-01',
                        'ad_end_at'           => '2022-08-25',
                        'ad_cost'             => 450,
                        'ad_img'              => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'ad_at_homepage'      => 1,
                        'ad_at_discover_page' => 1,
                        'created_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                    ]
                ]


            );
        }


    }
}
