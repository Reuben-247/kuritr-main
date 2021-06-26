<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('levels')->insert([
            [
            'name' => 'Level 1',
            'description' => 'Basic entry level for a new user',
            ],

            [
                'name' => 'Level 2',
                'description' => 'second entry level for a  user',
                ],
             [
                    'name' => 'Level 3',
                    'description' => 'Level 3',
                    ],
                    [
                        'name' => 'Level 4',
                        'description' => 'level 4',
                        ],
                        [
                            'name' => 'Level 5',
                            'description' => 'level 5',
                            ],
                            [
                                'name' => 'Level 6',
                                'description' => 'level 6',
                                ],

                                [
                                    'name' => 'Level 7',
                                    'description' => 'level 7',
                                    ],

        ]);
    }
}
