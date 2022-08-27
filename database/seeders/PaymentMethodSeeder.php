<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $paymentMethodCheck = DB::table('payment_methods')->get();

        if(!count($paymentMethodCheck)){
            DB::table('payment_methods')->insert(
                [
                    [
                        'payment_method_name' => '{"ar": "كاش", "en": "cash"}',
                        'payment_method_type' => 'cash',
                        'is_active'           => 1,
                        'created_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'payment_method_name' => '{"ar": "محفظتي", "en": "my wallet"}',
                        'payment_method_type' => 'wallet',
                        'is_active'           => 1,
                        'created_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'payment_method_name' => '{"ar": "اونلاين", "en": "online"}',
                        'payment_method_type' => 'online',
                        'is_active'           => 1,
                        'created_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'          => Carbon::now()->format('Y-m-d H:i:s'),
                    ]
                ]
            );
        }

    }
}
