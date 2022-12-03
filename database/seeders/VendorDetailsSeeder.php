<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $vendorIsUserCheck = DB::table('users')->where("user_phone", "999999999")->first();
        $vendorCheck  = DB::table('vendor_details')->where("user_id", $vendorIsUserCheck->user_id)->first();
        if (!is_object($vendorCheck)) {
            DB::table('vendor_details')->insert(
                [
                    'user_id'                            => $vendorIsUserCheck->user_id,
                    'vendor_type'                        => 'salon',
                    'vendor_available_days'              => "['monday','tuesday']",
                    'vendor_start_time'                  => '01:00:00',
                    'vendor_end_time'                    => '20:00:00',
                    'vendor_commercial_registration_num' => '12345',
                    'vendor_tax_num'                     => '12345678',
                    'vendor_description'                 => 'this is desc',
                    'vendor_slider'                      => '["uploads/vendor_imgs/images.jpg","uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg"]',
                    'vendor_reviews_sum'                 => 12,
                    'vendor_reviews_count'               => 5,
                    'vendor_views_count'                 => 0,
                    'vendor_categories_ids'              => ',1,3,',
                    'created_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );

        }

        $vendorIsUserCheck = DB::table('users')->where("user_phone", "999999998")->first();
        $vendorCheck  = DB::table('vendor_details')->where("user_id", $vendorIsUserCheck->user_id)->first();
        if (!is_object($vendorCheck)) {
            DB::table('vendor_details')->insert(
                [
                    'user_id'                            => $vendorIsUserCheck->user_id,
                    'vendor_type'                        => 'salon',
                    'vendor_available_days'              => "['monday','tuesday','wednesday']",
                    'vendor_start_time'                  => '02:00:00',
                    'vendor_end_time'                    => '18:00:00',
                    'vendor_commercial_registration_num' => '12345555',
                    'vendor_tax_num'                     => '12345678',
                    'vendor_description'                 => 'this is desc',
                    'vendor_slider'                      => '["uploads/vendor_imgs/images.jpg","uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg"]',
                    'vendor_reviews_sum'                 => 13,
                    'vendor_reviews_count'               => 3,
                    'vendor_views_count'                 => 0,
                    'vendor_categories_ids'              => ',1,',
                    'created_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );

        }

        $vendorIsUserCheck = DB::table('users')->where("user_phone", "999999997")->first();
        $vendorCheck  = DB::table('vendor_details')->where("user_id", $vendorIsUserCheck->user_id)->first();
        if (!is_object($vendorCheck)) {
            DB::table('vendor_details')->insert(
                [
                    'user_id'                            => $vendorIsUserCheck->user_id,
                    'vendor_type'                        => 'specialist',
                    'vendor_available_days'              => "['monday','tuesday','wednesday','thursday']",
                    'vendor_start_time'                  => '01:00:00',
                    'vendor_end_time'                    => '19:00:00',
                    'vendor_commercial_registration_num' => '12344455',
                    'vendor_tax_num'                     => '12345478',
                    'vendor_description'                 => 'this is desc',
                    'vendor_slider'                      => '["uploads/vendor_imgs/images.jpg","uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg"]',
                    'vendor_reviews_sum'                 => 40,
                    'vendor_reviews_count'               => 10,
                    'vendor_views_count'                 => 0,
                    'vendor_categories_ids'              => ',1,',
                    'created_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );

        }

        $vendorIsUserCheck = DB::table('users')->where("user_phone", "999999996")->first();
        $vendorCheck  = DB::table('vendor_details')->where("user_id", $vendorIsUserCheck->user_id)->first();
        if (!is_object($vendorCheck)) {
            DB::table('vendor_details')->insert(
                [
                    'user_id'                            => $vendorIsUserCheck->user_id,
                    'vendor_type'                        => 'specialist',
                    'vendor_available_days'              => "['monday','tuesday','friday']",
                    'vendor_start_time'                  => '02:00:00',
                    'vendor_end_time'                    => '20:00:00',
                    'vendor_commercial_registration_num' => '12345555',
                    'vendor_tax_num'                     => '12345678',
                    'vendor_description'                 => 'this is desc',
                    'vendor_slider'                      => '["uploads/vendor_imgs/images.jpg","uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg"]',
                    'vendor_reviews_sum'                 => 25,
                    'vendor_reviews_count'               => 7,
                    'vendor_views_count'                 => 0,
                    'vendor_categories_ids'              => ',1,',
                    'created_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );

        }

        $vendorIsUserCheck = DB::table('users')->where("user_phone", "999999995")->first();
        $vendorCheck  = DB::table('vendor_details')->where("user_id", $vendorIsUserCheck->user_id)->first();
        if (!is_object($vendorCheck)) {
            DB::table('vendor_details')->insert(
                [
                    'user_id'                            => $vendorIsUserCheck->user_id,
                    'vendor_type'                        => 'salon',
                    'vendor_available_days'              => "['monday','tuesday','wednesday','thursday','friday']",
                    'vendor_start_time'                  => '01:00:00',
                    'vendor_end_time'                    => '20:00:00',
                    'vendor_commercial_registration_num' => '12340055',
                    'vendor_tax_num'                     => '123467908',
                    'vendor_description'                 => 'this is desc',
                    'vendor_slider'                      => '["uploads/vendor_imgs/images.jpg","uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg"]',
                    'vendor_reviews_sum'                 => 19,
                    'vendor_reviews_count'               => 5,
                    'vendor_views_count'                 => 0,
                    'vendor_categories_ids'              => ',1,',
                    'created_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );

        }

        $vendorIsUserCheck = DB::table('users')->where("user_phone", "999999994")->first();
        $vendorCheck  = DB::table('vendor_details')->where("user_id", $vendorIsUserCheck->user_id)->first();
        if (!is_object($vendorCheck)) {
            DB::table('vendor_details')->insert(
                [
                    'user_id'                            => $vendorIsUserCheck->user_id,
                    'vendor_type'                        => 'specialist',
                    'vendor_available_days'              => "['monday','wednesday']",
                    'vendor_start_time'                  => '01:00:00',
                    'vendor_end_time'                    => '21:00:00',
                    'vendor_commercial_registration_num' => '12333555',
                    'vendor_tax_num'                     => '12366678',
                    'vendor_description'                 => 'this is desc',
                    'vendor_slider'                      => '["uploads/vendor_imgs/images.jpg","uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg"]',
                    'vendor_reviews_sum'                 => 30,
                    'vendor_reviews_count'               => 9,
                    'vendor_views_count'                 => 0,
                    'vendor_categories_ids'              => ',1,',
                    'created_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'                         => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );

        }

    }
}
