<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $userCheck = DB::table('users')->where("user_phone", "999999999")->get()->first();
        if (!is_object($userCheck)) {

            DB::table('users')->insert(

                [
                    'user_wallet'            => 0,
                    'user_name'              => 'WoW Beauty salon',
                    'user_type'              => 'vendor',
                    'user_address'           => 'WoW Beauty salon address',
                    'user_phone'             => '999999999',
                    'user_phone_verified_at' => null,
                    'user_email'             => 'WoW@gmail.com',
                    'user_date_of_birth'     => null,
                    'user_lat'               => 45.25274,
                    'user_long'              => 85.752527,
                    'user_is_active'         => 1,
                    'user_img'               => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                    'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                ]

            );


        }

        $userCheck = DB::table('users')->where("user_phone", "999999998")->get()->first();
        if (!is_object($userCheck)) {
            DB::table('users')->insert(
                [
                    [
                        'user_wallet'            => 0,
                        'user_name'              => 'Senses salon',
                        'user_type'              => 'vendor',
                        'user_address'           => 'Senses salon address',
                        'user_phone'             => '999999998',
                        'user_phone_verified_at' => null,
                        'user_email'             => 'Senses@gmail.com',
                        'user_date_of_birth'     => null,
                        'user_lat'               => 45.25274,
                        'user_long'              => 85.752527,
                        'user_is_active'         => 1,
                        'user_img'               => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                ]
            );

        }


        $userCheck = DB::table('users')->where("user_phone", "999999997")->get()->first();
        if (!is_object($userCheck)) {
            DB::table('users')->insert(
                [
                    [
                        'user_wallet'            => 0,
                        'user_name'              => 'Nour Makeup',
                        'user_type'              => 'vendor',
                        'user_address'           => 'Nour Makeup address',
                        'user_phone'             => '999999997',
                        'user_phone_verified_at' => null,
                        'user_email'             => 'Senses@gmail.com',
                        'user_date_of_birth'     => null,
                        'user_lat'               => 45.25274,
                        'user_long'              => 85.752527,
                        'user_is_active'         => 1,
                        'user_img'               => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                ]
            );

        }


        $userCheck = DB::table('users')->where("user_phone", "999999996")->get()->first();
        if (!is_object($userCheck)) {
            DB::table('users')->insert(
                [
                    [
                        'user_wallet'            => 0,
                        'user_name'              => 'Asma makeup artist',
                        'user_type'              => 'vendor',
                        'user_address'           => 'Asma makeup artist address',
                        'user_phone'             => '999999996',
                        'user_phone_verified_at' => null,
                        'user_email'             => 'AsmaAr@gmail.com',
                        'user_date_of_birth'     => null,
                        'user_lat'               => 45.25274,
                        'user_long'              => 85.752527,
                        'user_is_active'         => 1,
                        'user_img'               => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                ]
            );

        }


        $userCheck = DB::table('users')->where("user_phone", "999999995")->get()->first();
            if (!is_object($userCheck)) {
            DB::table('users')->insert(
                [
                    [
                        'user_wallet'            => 0,
                        'user_name'              => "Reta Spa",
                        'user_type'              => 'vendor',
                        'user_address'           => 'Reta Spa address',
                        'user_phone'             => '999999995',
                        'user_phone_verified_at' => null,
                        'user_email'             => 'Senses@gmail.com',
                        'user_date_of_birth'     => null,
                        'user_lat'               => 45.25274,
                        'user_long'              => 85.752527,
                        'user_is_active'         => 1,
                        'user_img'               => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                ]
            );

        }

        $userCheck = DB::table('users')->where("user_phone", "999999994")->get()->first();
        if (!is_object($userCheck)) {
            DB::table('users')->insert(
                [
                    [
                        'user_wallet'            => 0,
                        'user_name'              => "Mona Hair Specialist",
                        'user_type'              => 'vendor',
                        'user_address'           => 'Reta Spa address',
                        'user_phone'             => '999999994',
                        'user_phone_verified_at' => null,
                        'user_email'             => 'Senses@gmail.com',
                        'user_date_of_birth'     => null,
                        'user_lat'               => 45.25274,
                        'user_long'              => 85.752527,
                        'user_is_active'         => 1,
                        'user_img'               => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'             => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                ]
            );

        }



    }
}
