<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $areaManager = Role::create(['name' => 'area_manager', 'guard_name' => 'web']);
        $employee = Role::create(['name' => 'employee', 'guard_name' => 'web']);

        $permissions = [
            'manage_users',
            'create_user',
            'manage_districts',
            'assign_districts',
            'manage_roles',
            'manage_permissions',
            'view_district_details',
            'view_own_details',
            'update_own_details',
            'view_users_in_district'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $admin->syncPermissions([
            'manage_users',
            'create_user',
            'manage_districts',
            'assign_districts',
            'manage_roles',
            'manage_permissions',
            'view_district_details',
            'view_own_details',
            'update_own_details',
            'view_users_in_district'

        ]);
        $areaManager->syncPermissions([
            'view_users_in_district'
        ]);
        $employee->syncPermissions([
            'view_own_details',
            'update_own_details'
        ]);
    }
}
