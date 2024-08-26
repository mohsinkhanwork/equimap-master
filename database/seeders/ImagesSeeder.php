<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;

class ImagesSeeder extends Seeder {
    /**
     * Seed the application's user table.
     *
     * @return void
     */
    public function run(){
        if( env('SEED_IMAGES', false ) ) {
            Provider::all()
                ->each(function ($provider) {
                    $image = \App\Models\Image::factory()->count(2)->make();
                    $provider->images()->saveMany($image);
                });
        }
    }
}
