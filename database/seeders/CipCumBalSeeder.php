<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CCB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CipCumBalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('ccb')->insert([
            [
                'period_cip' => '2024-09',
                'bal_usd' => 1000,
                'bal_rp' => 15000000,
                'cumbal_usd' => 5000,
                'cumbal_rp' => 75000000,
                'report_status' => 1,
                'status' => 1
            ],
            [
                'period_cip' => '2024-10',
                'bal_usd' => 2000,
                'bal_rp' => 30000000,
                'cumbal_usd' => 10000,
                'cumbal_rp' => 150000000,
                'report_status' => 0,
                'status' => 1
            ],
            // Tambahkan lebih banyak data jika diperlukan
        ]);
    }
}