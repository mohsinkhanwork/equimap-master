<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $countryCode    = fake()->countryCode();
        $countriesRaw   = File::get(storage_path('app/countries.json'));
        $countriesList  = json_decode($countriesRaw, true);
        $currency       = $countriesList[$countryCode];

        return [
            'name' => fake()->company(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country' => fake()->countryCode(),
            'currency' => $currency,
            'geo_loc'   => implode(',',fake()->localCoordinates()),
            'description' => fake()->realText(),
            'user_id' => User::all('id')->random()->id,
        ];
    }
}
