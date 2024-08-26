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
    public function run(){
        $this->call([
            CategorySeeder::class,
            FacilitySeeder::class,
            UserSeeder::class,
            ProviderSeeder::class,
            ServiceSeeder::class,
            ScheduleSeeder::class,
            CalendarSeeder::class,
            //ImageSeeder::class,
        ]);
    }
}
