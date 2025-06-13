<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage employee',
            'manage location',
            'see report',
        ];

        // Create permissions in the database
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'Admin']);
        $employeeRole = Role::create(['name' => 'Karyawan']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo($permissions);

        // Create users by role
        User::create([
            'name' => 'Zachran Razendra',
            'username' => 'zachranraze',
            'email' => 'zachranraze@recodex.id',
            'password' => bcrypt('admin123'),
        ])->assignRole($adminRole);

        User::create([
            'name' => 'Adnin Farizie',
            'username' => 'adninfarizie',
            'email' => 'adninfarizie@recodex.id',
            'password' => bcrypt('admin123'),
        ])->assignRole($employeeRole);
    }
}
