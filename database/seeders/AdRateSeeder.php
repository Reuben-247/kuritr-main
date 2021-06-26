<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AdRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ads_rates')->insert([
            [
            'daily_amount' => 2797,
            'estimated_views' => 100000,
            'ads_name' => 'index',
            'ads_location' => 'home-page',
            'description' => 'This ad shows on the home page'
            ],

            [
                'daily_amount' => 1007,
                'estimated_views' => 10000,
                'ads_name' => 'k-sports',
                'ads_location' => 'spaorts-channel',
                'description' => 'This ad shows on the sports channel'
                ],

                [
                    'daily_amount' => 1997,
                    'estimated_views' => 12000,
                    'ads_name' => 'k-member',
                    'ads_location' => 'dasboard',
                    'description' => 'This ad shows on the dasboard for users who login to the dasboard'
                    ],

                    [
                        'daily_amount' => 3197,
                        'estimated_views' => 120000,
                        'ads_name' => 'political',
                        'ads_location' => 'everywhere',
                        'description' => 'This ad shows on the platforms everywhere'
                        ],

        ]);
    }
}
