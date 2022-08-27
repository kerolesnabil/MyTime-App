<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $categoriesCheck = DB::table('categories')->get()->toArray();

        if (!count($categoriesCheck)){
            DB::table('categories')->insert(
                [
                    [
                        'parent_id'       => 0,
                        'cat_name'        => '{"ar": "خدمات الشعر", "en": "hair services"}',
                        'cat_description' => '{"ar": "هذا الوصف لخدمات الشعر  ", "en": "This is a description of hair services"}',
                        'cat_img'         => 'uploads/2xHmr4M6JLYWBdxpO7Dy2KUMu7tlaLINEWc0GNxr.jpg',
                        'cat_is_active'   => 1,
                        'has_children'    => 1,
                        'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'parent_id'       => 0,
                        'cat_name'        => '{"ar": "خدمات البشرة", "en": "Skin Services"}',
                        'cat_description' => '{"ar": "وصف خدمات البشرة ", "en": "description of Skin Services"}',
                        'cat_img'         => 'uploads/2xHmr4M6JLYWBdxpO7Dy2KUMu7tlaLINEWc0GNxr.jpg',
                        'cat_is_active'   => 1,
                        'has_children'    => 0,
                        'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'parent_id'       => 0,
                        'cat_name'        => '{"ar": "خدمات الأظافر", "en": "Nails Services"}',
                        'cat_description' => '{"ar": "وصف خدمات الأظافر ", "en": "description of Nails Services"}',
                        'cat_img'         => 'uploads/2xHmr4M6JLYWBdxpO7Dy2KUMu7tlaLINEWc0GNxr.jpg',
                        'cat_is_active'   => 1,
                        'has_children'    => 0,
                        'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                    ],

                    [
                        'parent_id'       => 1,
                        'cat_name'        => '{"ar": "قص الشعر", "en": "cut hair"}',
                        'cat_description' => '{"ar": "قص الشعر", "en": "cut hair"}',
                        'cat_img'         => 'uploads/2xHmr4M6JLYWBdxpO7Dy2KUMu7tlaLINEWc0GNxr.jpg',
                        'cat_is_active'   => 1,
                        'has_children'    => 0,
                        'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'parent_id'       => 1,
                        'cat_name'        => '{"ar": "صبغات  الشعر", "en": "hair dyes"}',
                        'cat_description' => '{"ar": "صبغات  الشعر", "en": "hair dyes"}',
                        'cat_img'         => 'uploads/2xHmr4M6JLYWBdxpO7Dy2KUMu7tlaLINEWc0GNxr.jpg',
                        'cat_is_active'   => 1,
                        'has_children'    => 0,
                        'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'parent_id'       => 1,
                        'cat_name'        => '{"ar": "تساريح الشعر", "en": "Hairstyles"}',
                        'cat_description' => '{"ar": "تساريح الشعر", "en": "Hairstyles"}',
                        'cat_img'         => 'uploads/2xHmr4M6JLYWBdxpO7Dy2KUMu7tlaLINEWc0GNxr.jpg',
                        'cat_is_active'   => 1,
                        'has_children'    => 0,
                        'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'      => Carbon::now()->format('Y-m-d H:i:s'),
                    ],


                ]
            );
        }

    }
}
