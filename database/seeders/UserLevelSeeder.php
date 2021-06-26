<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class UserLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_levels')->insert([
            [
            'name' => 'admin',
            'description' => 'Admin can create editors and moderate them',
            ],

            [
                'name' => 'editor',
                'description' => 'Editors moderate contents and users on the platform',
                ],

                [
                    'name' => 'user',
                    'description' => 'A user can post contents but does not have administrative priveledges',
                    ],

                    [
                        'name' => 'super-admin',
                        'description' => 'A super admin manages admin and can operate as all types of admins on the platform',
                        ],
        ]);
    }
}
