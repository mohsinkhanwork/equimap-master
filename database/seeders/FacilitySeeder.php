<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder {
    /**
     * Seed the application's user table.
     *
     * @return void
     */
    public function run(){
        $categories = [
            'Free Parking',
            'Wifi',
            'Air Condition'
        ];

        foreach( $categories as $sort => $category ){
            Facility::create([
                'name'  => $category,
                'sort'  => $sort
            ]);
        }
    }
}
