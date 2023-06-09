<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionServiceProvider;
use Spatie\Permission\PermissionRegistrar;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dice_1 = fake()->numberBetween(1,6);
        $dice_2 = fake()->numberBetween(1,6);
        $win = $dice_1 + $dice_2 === 7;
        
        return [
            'user_id' => User::pluck('id')->random(),
            'dice_1' => $dice_1,
            'dice_2' => $dice_2,
            'win' => $win,
            
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $role = Role::findOrCreate('player');
            $user->assignRole($role);
            $role = Role::findOrCreate('admin');
            $user->assignRole($role);
        });
    }
}
