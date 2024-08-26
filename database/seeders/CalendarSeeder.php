<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class CalendarSeeder extends Seeder {
    /**
     * Seed the application's service table.
     *
     * @return void
     */
    public function run(){
        Service::all()
            ->each( function( $service ){
                $schedule   = $service->schedules()->inRandomOrder()->first();
                $service->calendars()->create([
                    'event_type'    => fake()->randomElement( [ 'booking', 'block' ] ),
                    'notes'         => fake()->realText(),
                    'check_in'      => fake()->dateTimeBetween('-1 year', 'now'),
                    'check_out'     => fake()->dateTimeBetween( 'now', '+1 year' ),
                ]);
            });
    }
}
