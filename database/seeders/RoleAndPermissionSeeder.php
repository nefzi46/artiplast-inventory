<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'manage sales']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage purchases']);
        Permission::create(['name' => 'manage suppliers']);
        Permission::create(['name' => 'manage customers']);

        // Rôles
        $admin = Role::create(['name' => 'admin']);
        $agent = Role::create(['name' => 'agent']);

        // Attribution
        $admin->givePermissionTo(Permission::all());
        $agent->givePermissionTo(['manage products', 'manage sales', 'manage customers']);
    }
}