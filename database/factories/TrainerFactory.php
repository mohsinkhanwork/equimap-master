<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class TrainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(){
        return [
            'active' => 1,
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'provider_id' => Provider::all('id')->random()->id
        ];
    }
}
