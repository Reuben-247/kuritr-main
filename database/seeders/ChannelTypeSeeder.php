<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class ChannelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('channel_types')->insert([
            [
            'name' => 'private',
            'description' => 'You can not post to this channel if you are not one of the admins',
            ],

            [
                'name' => 'public',
                'description' => 'Any subscribed member can post to the channel but it requires approval to show',
                ],

                [
                    'name' => 'open',
                    'description' => 'Any subscribed member can post to the channel, post do not require approval provided you keep to the channel rules',
                    ],
        ]);
    }
}
