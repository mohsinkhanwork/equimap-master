<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder {
    /**
     * Seed the application's service table.
     *
     * @return void
     */
    public function run(){
        Service::all()
            ->each( function( $service ){
                $service->schedules()->create([
                    'day'       => fake()->randomElement( [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ] ),
                    'price'     => fake()->randomElement( [ fake()->numberBetween( 50, 400 ) ] ),
                    'start_time'=> fake()->time( 'H:i:s', 12 ),
                    'end_time'  => fake()->time( 'H:i:s', 24 ),
                ]);
            });
    }
}
