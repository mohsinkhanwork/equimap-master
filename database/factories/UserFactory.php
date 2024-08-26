<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'active'    => fake()->boolean(),
            'name'      => fake()->name(),
            'login'     => fake()->phoneNumber(),
            'password'  => utils()->hashPassword( '12345678' ),
            'role'      => fake()->randomElement( [ 'customer', 'vendor'] ),
            'login_verified_at' => fake()->date()
        ];
    }
}
