<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $couponCheck = DB::table('coupons')->where("coupon_code", "2021code")->get()->first();
        if (!is_object($couponCheck)) {
            DB::table('coupons')->insert(
                [
                    'coupon_code'        => '2021code',
                    'coupon_value'       => 200,
                    'coupon_start_at'    => '2022-08-01',
                    'coupon_end_at'      => '2022-08-30',
                    'coupon_type'        => 'value',
                    'coupon_limited_num' => 20,
                    'coupon_used_times'  => 0,
                    'is_active'          => 1,
                    'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );

        }

        $couponCheck = DB::table('coupons')->where("coupon_code", "2022code")->get()->first();
        if (!is_object($couponCheck)) {
            DB::table('coupons')->insert(
                [
                    'coupon_code'        => '2022code',
                    'coupon_value'       => 10,
                    'coupon_start_at'    => '2022-08-01',
                    'coupon_end_at'      => '2022-08-30',
                    'coupon_type'        => 'percent',
                    'coupon_limited_num' => 20,
                    'coupon_used_times'  => 0,
                    'is_active'          => 1,
                    'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
