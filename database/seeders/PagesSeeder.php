<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $pagesCheck = DB::table('pages')->get();
        if (!count($pagesCheck)) {
            DB::table('pages')->insert(
                [

                    [
                        'page_title'         => '{"ar":"عن ماي تايم", "en":"About My Time"}',
                        'page_content'       => '{"ar":"عن ماي تايم نص او مقال ", "en":"about my time text body"}',
                        'images'                => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'page_position'      => 'first',
                        'show_in_user_app'   => 1,
                        'show_in_vendor_app' => 0,
                        'is_active'          => 1,
                        'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'page_title'         => '{"ar":"الشروط والاحكام", "en":"Terms and Conditions"}',
                        'page_content'       => '{"ar":"الشروط والاحكام نص او مقال", "en":"Terms and Conditions text body"}',
                        'images'                => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'page_position'      => 'last',
                        'show_in_user_app'   => 1,
                        'show_in_vendor_app' => 0,
                        'is_active'          => 1,
                        'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'page_title'         => '{"ar":"الاسئلة الاكثر شيوعا", "en":"Frequently Asked Questions"}',
                        'page_content'       => '{"ar":"الاسئلة الاكثر شيوعا نص او مقال", "en":"Frequently Asked Questions text body"}',
                        'images'                => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'page_position'      => 'last',
                        'show_in_user_app'   => 1,
                        'show_in_vendor_app' => 0,
                        'is_active'          => 1,
                        'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                    ],

                    [
                        'page_title'         => '{"ar":"عن ماي تايم للبائع", "en":"About My Time for vendor"}',
                        'page_content'       => '{"ar":"عن ماي تايم نص او مقال للبائع ", "en":"about my time text body for vendor"}',
                        'images'                => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'page_position'      => 'first',
                        'show_in_user_app'   => 0,
                        'show_in_vendor_app' => 1,
                        'is_active'          => 1,
                        'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'page_title'         => '{"ar":"الشروط والاحكام للبائع", "en":"Terms and Conditions for vendor"}',
                        'page_content'       => '{"ar":"الشروط والاحكام نص او مقال للبائع", "en":"Terms and Conditions text body for vendor"}',
                        'images'                => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'page_position'      => 'last',
                        'show_in_user_app'   => 0,
                        'show_in_vendor_app' => 1,
                        'is_active'          => 1,
                        'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'page_title'         => '{"ar":"الاسئلة الاكثر شيوعا للبائع", "en":"Frequently Asked Questions for vendor"}',
                        'page_content'       => '{"ar":"الاسئلة الاكثر شيوعا نص او مقال للبائع", "en":"Frequently Asked Questions text body for vendor"}',
                        'images'                => 'uploads/gmnXRGQkNh73LhBh7752vyjlibQWnj8GHOm4gdPe.jpg',
                        'page_position'      => 'last',
                        'show_in_user_app'   => 0,
                        'show_in_vendor_app' => 1,
                        'is_active'          => 1,
                        'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
                    ],



                ]


            );
        }


    }
}
