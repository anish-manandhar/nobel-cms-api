<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'role-list']);
        Permission::create(['name' => 'role-store']);
        Permission::create(['name' => 'role-show']);
        Permission::create(['name' => 'role-update']);
        Permission::create(['name' => 'role-delete']);

        Permission::create(['name' => 'permission-list']);
        Permission::create(['name' => 'permission-store']);
        Permission::create(['name' => 'permission-show']);
        Permission::create(['name' => 'permission-update']);
        Permission::create(['name' => 'permission-delete']);

        Permission::create(['name' => 'faculty-list']);
        Permission::create(['name' => 'faculty-store']);
        Permission::create(['name' => 'faculty-show']);
        Permission::create(['name' => 'faculty-update']);
        Permission::create(['name' => 'faculty-delete']);

        Permission::create(['name' => 'program-list']);
        Permission::create(['name' => 'program-store']);
        Permission::create(['name' => 'program-show']);
        Permission::create(['name' => 'program-update']);
        Permission::create(['name' => 'program-delete']);

        Permission::create(['name' => 'subject-list']);
        Permission::create(['name' => 'subject-store']);
        Permission::create(['name' => 'subject-show']);
        Permission::create(['name' => 'subject-update']);
        Permission::create(['name' => 'subject-delete']);

        $role = Role::create(['name' => 'Admin']);
        $role->givePermissionTo(Permission::all());
    }
}
