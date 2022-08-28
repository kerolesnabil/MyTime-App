<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $settingCheck = DB::table('settings')->where('setting_key', 'social_media')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(

                [
                    'setting_name'  => '{"ar":"وسائل التواصل الاجتماعي", "en":"Social media"}',
                    'setting_key'   => 'social_media',
                    'setting_value' => '[{"link": "www.fb", "name": "facebook", "classs": "bi bi-facebook"}, {"link": "www.youtube", "name": "youtube", "classs": "bi bi-youtube"}]',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }


        $settingCheck = DB::table('settings')->where('setting_key', 'whatsapp')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(

                [
                    'setting_name'  => '{"ar":"رقم الواتساب", "en":"whatsapp number"}',
                    'setting_key'   => 'whatsapp',
                    'setting_value' => '999999990',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }


        if (!count($settingCheck)) {
            DB::table('settings')->insert(

                [

                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }
      
        if (!count($settingCheck)) {
            DB::table('settings')->insert(

                [

                    'setting_value' => '15',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }




    }
}
