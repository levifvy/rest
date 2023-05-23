<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Game;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        
        User::factory(10)->create()->each(function($user){
            $user->assignRole('player');
        });
        
        User::factory(2)
            ->has(Game::factory()->count(2))
            ->create()
            ->each(function ($user) {
                $user->assignRole('player');
            });
    }
}
