<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'active'        => true,
            'approved'      => true,
            'name'          => fake()->randomElement([
                'Random Service #1',
                'Random Service #2',
                'Random Service #3',
                'Random Service #4',
                'Random Service #5'
            ]),
            'description'   => fake()->text(),
            'price'         => fake()->numberBetween(100,1000),
            'unit'          => fake()->randomElement([
                'hour',
                'day'
            ]),
            'capacity'      => fake()->numberBetween(1,12),
            'currency'      => 'AED',
            'provider_id'   => Provider::all('id')->random()->id,
            'category_id'   => Category::all('id')->random()->id
        ];
    }
}
