<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;

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
            'password' => Hash::make('123456'), // 123456
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


    public function configure()
{
    return $this->afterCreating(function (User $user) {
        $role = Role::findOrCreate('player');
        $user->assignRole($role);
    });
}

}
