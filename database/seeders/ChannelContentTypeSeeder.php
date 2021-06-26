<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class ChannelContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('channel_content_types')->insert([
            [
            'name' => 'text-only',
            'description' => 'Only text are allowed to be posted to this channel',
            ],

            [
                'name' => 'no-url',
                'description' => 'Text and images are allowed but not url',
                ],

                [
                    'name' => 'all-media',
                    'description' => 'Text, images and url are allowed',
                    ],
        ]);
    }
}
