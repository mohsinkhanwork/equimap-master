<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $company    = fake()->company();

        return [
            'rate' => fake()->numberBetween(1,5),
            'review' => fake()->text(100),
            'service_id' => Service::all('id')->random()->id,
            'user_id' => User::all('id')->random()->id,
        ];
    }
}
