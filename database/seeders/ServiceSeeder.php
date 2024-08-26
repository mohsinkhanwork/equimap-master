<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder {
    /**
     * Seed the application's service table.
     *
     * @return void
     */
    public function run(){
        Service::all()
            ->each( function( $service ){
                // seed reviews
                $review = \App\Models\Review::factory()
                                ->count(3)
                                ->make();
                $service
                    ->reviews()
                    ->saveMany( $review );

                // add horses connection
                $horse      = \App\Models\Horse::where( 'provider_id', $service->provider_id )
                                ->inRandomOrder()
                                ->first();

                ( new \App\Models\ServicesHorse )->create([
                    'service_id'    => $service->id,
                    'horse_id'      => $horse->id
                ]);

                // add trainer connection
                $trainer    = \App\Models\Trainer::where( 'provider_id', $service->provider_id )
                    ->inRandomOrder()
                    ->first();

                ( new \App\Models\ServicesTrainer )->create([
                    'service_id'    => $service->id,
                    'trainer_id'    => $trainer->id
                ]);
            });
    }
}
