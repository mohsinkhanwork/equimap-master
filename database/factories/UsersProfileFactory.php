<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UsersProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(){
        return [
            'name' => fake()->name(),
            'gender' => fake()->randomElement([
                'male', 'female'
            ]),
            'birthday' => fake()->dateTime(),
            'language'   => fake()->randomElement([
                'en', 'ar'
            ]),
            'level' => fake()->randomElement([
                'beginner',
                'intermediate',
                'advanced'
            ]),
            'weight' => fake()->numberBetween(20,500),
            'user_id' => User::all('id')->random()->id,
            'email' => fake()->email()
        ];
    }
}
