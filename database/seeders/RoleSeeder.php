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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::create(['name' => 'admin']);
        $player = Role::create(['name' => 'player']);

        Permission::create(['name' => 'users.login'])->syncRoles([$admin, $player]);
        Permission::create(['name' => 'users.register'])->syncRoles([$admin, $player]);

        
        Permission::create(['name' => 'players.listPlayersRate'])->syncRoles([$admin]);
        Permission::create(['name' => 'players.update'])->syncRoles([$player]);
        Permission::create(['name' => 'players.logout'])->syncRoles([$admin, $player]);
        
        Permission::create(['name' => 'players.throwingDices'])->syncRoles([$player]);
        Permission::create(['name' => 'players.listThrowedGames'])->syncRoles([$player]);
        Permission::create(['name' => 'players.deleteAllThrowsOfAPlayer'])->syncRoles([$player]);

        
        Permission::create(['name' => 'players.indexRanking'])->syncRoles([$admin]);
        Permission::create(['name' => 'players.winnerRanking'])->syncRoles([$admin]);
        Permission::create(['name' => 'players.loserRanking'])->syncRoles([$admin]);
    }
}
