<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder 
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // $admin = Role::create(['name' => 'admin']);
        // $player = Role::create(['name' => 'player']);

        // Permission::create(['name' => 'users.login'])->syncRoles([$admin, $player]);
        // Permission::create(['name' => 'users.register'])->syncRoles([$admin, $player]);

        // Permission::create(['name' => 'players.listPlayersRate'])->assignRole($admin);
        // Permission::create(['name' => 'players.update'])->assignRole($player);
        // Permission::create(['name' => 'players.logout'])->syncRoles([$admin, $player]);
        
        // Permission::create(['name' => 'players.throwingDices'])->assignRole($player);
        // Permission::create(['name' => 'players.listThrowedGames'])->assignRole($player);
        // Permission::create(['name' => 'players.deleteAllThrowsOfAPlayer'])->assignRole($player);

        // Permission::create(['name' => 'players.indexRanking'])->assignRole($admin);
        // Permission::create(['name' => 'players.winnerRanking'])->assignRole($admin);
        // Permission::create(['name' => 'players.loserRanking'])->assignRole($admin);
    }
}