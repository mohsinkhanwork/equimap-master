<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeeder extends Seeder {
    /**
     * Seed the application's user table.
     *
     * @return void
     */
    public function run(){
        Provider::factory()
            ->count(50)
            ->create()
            ->each( function( $provider ){
                // seed services
                $service    = \App\Models\Service::factory()->count(5)->make();
                $provider->services()->saveMany( $service );

                // seed horses
                $horse      = \App\Models\Horse::factory()->count(5)->make();
                $provider->horses()->saveMany( $horse );

                // seed trainers
                $trainer    = \App\Models\Trainer::factory()->count(5)->make();
                $provider->horses()->saveMany( $trainer );
            });
    }
}
