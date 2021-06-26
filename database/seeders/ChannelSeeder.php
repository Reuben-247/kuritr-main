<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('channels')->insert([
            [
            'title' => 'opinion',
            'description' => 'This is the opinion box of the platform anyone can post here provided you keep tp the rules of the channel',
            'rules' => '1. No misinformation. 2. No nude pictures are allowed. 3. Threats to humans or organizations are not allowed.
             4. We want facts, so provide them in your post',
             'status' => 'active',
             'user_id' => 1,
             'channel_content_type_id' => 3,
             'channel_type_id' => 3,

            ],

        ]);
    }
}
