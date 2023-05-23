<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'admin',
            'nickname' => Str::slug('admin'),
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => bcrypt(123456), // password
            'rate' => number_format(mt_rand(0, 10000) / 100, 2),
            'remember_token' => Str::random(10),
        ])->assignRole('admin');

        User::create([
            'name' => 'levi',
            'nickname' => Str::slug('levi'),
            'email' => 'levi@manager.com',
            'email_verified_at' => now(),
            'password' => bcrypt(123456), // password
            'rate' => number_format(mt_rand(0, 10000) / 100, 2),
            'remember_token' => Str::random(10),
        ])->assignRole('player');
    }
}
