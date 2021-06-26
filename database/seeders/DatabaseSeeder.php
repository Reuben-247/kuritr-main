<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            NetworkSeeder::class,
            ChannelAdminTypeSeeder::class,
            DataPlanSeeder::class ,
            LevelSeeder::class,
            AppSettingSeeder::class,
            ChannelContentTypeSeeder::class,
            ChannelTypeSeeder::class,
            UserLevelSeeder::class,
            UserTypeSeeder::class,
            AdRateSeeder::class,
                
        ]);
    }
}
