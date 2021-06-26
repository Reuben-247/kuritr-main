<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ChannelAdminTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('channel_admin_types')->insert([
            [
            'name' => 'Admin',
            'status' => 'active',
            'description' => 'Can add and delete other admins, moderate and make post on the channel',
            ],

            [
            'name' => 'Editor',
            'status' => 'active',
            'description' => 'Can moderate and post content on the channel.'
               ],

               [
                'name' => 'Publisher',
                'status' => 'active',
                'description' => 'Can only post content on the channel.'
                   ],

                   [
                    'name' => 'Moderator',
                    'status' => 'active',
                    'description' => 'Can only moderate contents on the channel.'
                       ],

            ]
    );
    }
}
