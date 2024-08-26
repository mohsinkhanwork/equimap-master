<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder {
    /**
     * Seed the application's user table.
     *
     * @return void
     */
    public function run(){
        $categories = [
            'Polo',
            'Farrier',
            'Vaulting',
            'Display'
        ];

        foreach( $categories as $sort => $category ){
            Category::create([
                'name'  => $category,
                'sort'  => $sort
            ]);
        }
    }
}
