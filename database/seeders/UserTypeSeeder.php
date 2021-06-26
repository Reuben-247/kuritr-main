<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->insert([
            [
            'name' => 'publisher',
            'description' => 'Publishers are media organization providing content on the platform',
            ],

            [
                'name' => 'journalist',
                'description' => 'A professional journalist that also provides content on the platform',
                ],

                [
                    'name' => 'user',
                    'description' => 'A user can post contents but does not have administrative priveledges',
                    ],

                    [
                        'name' => 'freelancers',
                        'description' => 'Freelancers are special contributors on the platform that provides content',
                        ],
        ]);
    }
}
