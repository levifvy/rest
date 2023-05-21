<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
         // \App\Models\User::factory(10)->create();

         User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'nickname' => 'test-user',
            'email_verified_at' => now(),
            'password' => bcrypt(654321), // password
            'remember_token' => Str::random(10),
         ]);

        //$this->call(RoleSeeder::class);

        /* User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt(654321), // password
            'remember_token' => Str::random(10),
            
         ]);*/

         
        User::factory(2)
            ->has(Game::factory()->count(3))
            ->create()
            ->each(function ($user) {
                //;
            });
    }
}
