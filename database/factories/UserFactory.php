<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends EloquentFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = FakerFactory::create()->name();
        return [
            'name' => $name,
            'nickname' => Str::slug($name),
            'email' => FakerFactory::create()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => Str::random(10),
            'rate' => FakerFactory::create()->randomFloat(2, 0, 100)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}