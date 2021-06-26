<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DataPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('data_plans')->insert([
            [
            'network_id' => '3',
            'amount' => 100,
            'data_size' => 80,
            'bonus' => 0,
            'total_data_size' => 80,
            'duration' => 30
            ],

            [
                'network_id' => '3',
                'amount' => 200,
                'data_size' => 150,
                'bonus' => 0,
                'total_data_size' => 150,
                'duration' => 30
                ],

            [
                    'network_id' => '3',
                    'amount' => 300,
                    'data_size' => 500,
                    'bonus' => 0,
                    'total_data_size' => 500,
                    'duration' => 30
                    ],

                    [
                        'network_id' => '3',
                        'amount' => 390,
                        'data_size' => 1000,
                        'bonus' => 0,
                        'total_data_size' => 1000,
                        'duration' => 30
                        ],

                        [
                            'network_id' => '1',
                            'amount' => 100,
                            'data_size' => 80,
                            'bonus' => 0,
                            'total_data_size' => 80,
                            'duration' => 30
                            ],

                            [
                                'network_id' => '2',
                                'amount' => 100,
                                'data_size' => 80,
                                'bonus' => 0,
                                'total_data_size' => 80,
                                'duration' => 30
                                ],

                                [
                                    'network_id' => '4',
                                    'amount' => 100,
                                    'data_size' => 80,
                                    'bonus' => 0,
                                    'total_data_size' => 80,
                                    'duration' => 30
                                    ],

            ]
         );
    }
}
