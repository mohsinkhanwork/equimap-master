<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $file   = fake()->image(storage_path('app/public/gallery'), 500, 500, 'animals', true, true, 'horse' );
        $file   = new File( $file );

        return [
            'name' => $file->getFilename(),
            'path' => 'gallery/' . $file->getFilename(),
            'ext'  => $file->getExtension(),
            'hash' => md5_file( $file ),
            'type' => 'gallery',
            'user_id'   => 3
        ];
    }
}
