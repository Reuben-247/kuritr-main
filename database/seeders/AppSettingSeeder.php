<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('app_settings')->insert([
            [
            'name' => 'Sign up bonus',
            'value' => 200,
            'access_level' => 'initial',
            ]
        ]);
    }
}
