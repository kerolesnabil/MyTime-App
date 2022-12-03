<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LangsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $settingCheck = DB::table('langs')->where('lang_symbol', 'ar')->get();
        if (!count($settingCheck)) {
            DB::table('langs')->insert(

                [
                    'lang_symbol'    => 'ar',
                    'lang_name'      => 'اللغة العربية',
                    'lang_direction' => 'rtl',
                    'lang_img'       => 'dashboard_files/images/langs/ar.png',
                    'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $settingCheck = DB::table('langs')->where('lang_symbol', 'en')->get();
        if (!count($settingCheck)) {
            DB::table('langs')->insert(

                [
                    'lang_symbol'    => 'en',
                    'lang_name'      => 'English',
                    'lang_direction' => 'ltr',
                    'lang_img'       => 'dashboard_files/images/langs/en.png',
                    'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }


    }
}
