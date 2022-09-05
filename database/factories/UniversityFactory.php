<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\University>
 */
class UniversityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => "University of ".fake()->name(),
            'description' => fake()->text(200),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'logo_image_path' => fake()->imageUrl(640, 480),
            'website' => fake()->url(),
            'enabled' => fake()->boolean(50),
            'premium' => fake()->boolean(50)
        ];
    }
}
