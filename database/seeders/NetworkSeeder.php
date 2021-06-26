<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class NetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('networks')->insert([
            [
            'name' => 'Airtel',
            'system_code' => '1',
            ],

            [
            'name' => '9Mobile',
            'system_code' => '2',
             ],
             [
                'name' => 'MTN',
                'system_code' => '15',
             ],
             [
                'name' => 'Glo',
                'system_code' => '6',
                ],

            ]
    );
    }
}
