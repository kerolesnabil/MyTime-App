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
                    'setting_value' => '[{"link": "www.fb", "name": "facebook", "class": "bi bi-facebook"}, {"link": "www.youtube", "name": "youtube", "class": "bi bi-youtube"}]',
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

        $settingCheck = DB::table('settings')->where('setting_key', 'price_ad_in_homepage')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"سعر الاعلان في الصفحة الرئيسية", "en":"Price ad in homepage"}',
                    'setting_key'   => 'price_ad_in_homepage',
                    'setting_value' => 100,
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $settingCheck = DB::table('settings')->where('setting_key', 'price_ad_in_discover_page')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"سعر الاعلان في صفحة اكتشف", "en":"Price ad in discover page"}',
                    'setting_key'   => 'price_ad_in_discover_page',
                    'setting_value' => 80,
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $settingCheck = DB::table('settings')->where('setting_key', 'user_splash_screen')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"صورة شاشة البداية للمستخدم", "en":"User splash screen"}',
                    'setting_key'   => 'user_splash_screen',
                    'setting_value' => 'images/default_ad_img.jpg',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $settingCheck = DB::table('settings')->where('setting_key', 'vendor_splash_screen')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"صورة شاشة البداية للصالون او الاخصائي", "en":"Salon or specialist splash screen"}',
                    'setting_key'   => 'vendor_splash_screen',
                    'setting_value' => 'images/default_ad_img.jpg',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $settingCheck = DB::table('settings')->where('setting_key', 'user_logo')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"لوجو المستخدم", "en":"User logo"}',
                    'setting_key'   => 'user_logo',
                    'setting_value' => 'images/default_ad_img.jpg',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $settingCheck = DB::table('settings')->where('setting_key', 'vendor_logo')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"لوجو الصالون او الاخصائي", "en":"Salon or specialist logo"}',
                    'setting_key'   => 'vendor_logo',
                    'setting_value' => 'images/default_ad_img.jpg',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }


        $settingCheck = DB::table('settings')->where('setting_key', 'diameter_search')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"نطاق البحث التطبيق", "en":"App diameter search"}',
                    'setting_key'   => 'diameter_search',
                    'setting_value' => 50,
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $settingCheck = DB::table('settings')->where('setting_key', 'bank_account_details')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"تفاصيل الحساب البنكي للتطبيق", "en":"App bank account details"}',
                    'setting_key'   => 'bank_account_details',
                    'setting_value' => '{"bank_name":"saudi bank name", "account_number":"0000", "iban_number":"12345"}',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $settingCheck = DB::table('settings')->where('setting_key', 'app_profit_percentage')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"نسبة ارباح التطبيق", "en":"Application profit percentage"}',
                    'setting_key'   => 'app_profit_percentage',
                    'setting_value' => 10,
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }

        $settingCheck = DB::table('settings')->where('setting_key', 'tax_rate')->get();
        if (!count($settingCheck)) {
            DB::table('settings')->insert(
                [
                    'setting_name'  => '{"ar":"نسبة الضريبة المضافة", "en":"Added tax rate"}',
                    'setting_key'   => 'tax_rate',
                    'setting_value' => 10,
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );
        }


    }
}
