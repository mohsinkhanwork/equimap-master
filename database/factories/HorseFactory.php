<?php

namespace Database\Factories;

use App\Models\Horse;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class HorseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(){
        return [
            'active'        => 1,
            'name'          => fake()->name(),
            'provider_id'   => Provider::all('id')->random()->id,
            'gender'        => fake()->randomElement( array_keys( Horse::getGenders() ) ),
            'level'         => fake()->randomElement( array_keys( Horse::getLevels() ) )
        ];
    }
}
