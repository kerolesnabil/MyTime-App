<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        
        $servicesCheck = DB::table('services')->get();

        if (!count($servicesCheck)){
            DB::table('services')->insert(
                [
                    [
                        'cat_id'               => 4,
                        'vendor_id'            => null,
                        'service_name'         => '{"ar": "قص شعر طويل/قصير", "en": "Haircut (long/short)"}',
                        'service_type'         => 'service',
                        'package_services_ids' => null,
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'cat_id'               => 4,
                        'vendor_id'            => null,
                        'service_name'         => '{"ar": "قص اطراف الشعر", "en": "Cut the ends of the hair"}',
                        'service_type'         => 'service',
                        'package_services_ids' => null,
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'cat_id'               => 5,
                        'vendor_id'            => null,
                        'service_name'         => '{"ar": "صبغة شعر لون واحد طويل/قصير", "en": "Hair dye one color long/short"}',
                        'service_type'         => 'service',
                        'package_services_ids' => null,
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'cat_id'               => 5,
                        'vendor_id'            => null,
                        'service_name'         => '{"ar": "صبغة جذور الشعر", "en": "Hair root dye"}',
                        'service_type'         => 'service',
                        'package_services_ids' => null,
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'cat_id'               => 6,
                        'vendor_id'            => null,
                        'service_name'         => '{"ar": "استشوار شعر طويل/قصير", "en": "Long/short hairdryer"}',
                        'service_type'         => 'service',
                        'package_services_ids' => null,
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'cat_id'               => 6,
                        'vendor_id'            => null,
                        'service_name'         => '{"ar": " شعر ويفي طويل / قصير", "en": "Long/short wavy hair"}',
                        'service_type'         => 'service',
                        'package_services_ids' => null,
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ],

                    [
                        'cat_id'               => 3,
                        'vendor_id'            => null,
                        'service_name'         => '{"ar": "لون اظافر اليد", "en": "Hand nail color"}',
                        'service_type'         => 'service',
                        'package_services_ids' => null,
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'cat_id'               => 3,
                        'vendor_id'            => null,
                        'service_name'         => '{"ar": "لون اظافر القدم", "en": "Toe nails color"}',
                        'service_type'         => 'service',
                        'package_services_ids' => null,
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'cat_id'               => null,
                        'vendor_id'            => 1,
                        'service_name'         => '{"ar": "باكج 1", "en": "package1"}',
                        'service_type'         => 'package',
                        'package_services_ids' => '1,2,3',
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'cat_id'               => null,
                        'vendor_id'            => 1,
                        'service_name'         => '{"ar": "باكج 2", "en": "package 2"}',
                        'service_type'         => 'package',
                        'package_services_ids' => '1,2,3,4',
                        'created_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'           => Carbon::now()->format('Y-m-d H:i:s'),
                    ]

                ]
            );
        }


    }
}
