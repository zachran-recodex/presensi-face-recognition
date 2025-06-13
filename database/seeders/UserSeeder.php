<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
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

        // Create roles
        $adminRole = Role::create(['name' => 'Admin']);
        $employeeRole = Role::create(['name' => 'Karyawan']);

        // Create admin users
        $zachran = User::create([
            'name' => 'Zachran Razendra',
            'username' => 'zachranraze',
            'email' => 'zachranraze@recodex.id',
            'password' => bcrypt('admin123'),
        ]);
        $zachran->assignRole($adminRole);

        $adnin = User::create([
            'name' => 'Adnin Farizie',
            'username' => 'adninfarizie',
            'email' => 'adninfarizie@recodex.id',
            'password' => bcrypt('admin123'),
        ]);
        $adnin->assignRole($employeeRole);

        // Create sample locations
        $mainOffice = Location::create([
            'name' => 'Main Office',
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'radius' => 100,
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'is_active' => true,
        ]);

        $branchOffice = Location::create([
            'name' => 'Branch Office Bandung',
            'address' => 'Jl. Dago No. 456, Bandung',
            'latitude' => -6.917464,
            'longitude' => 107.619123,
            'radius' => 150,
            'work_start_time' => '08:30:00',
            'work_end_time' => '17:30:00',
            'is_active' => true,
        ]);

        // Create employee records
        $zachranEmployee = Employee::create([
            'employee_id' => 'EMP001',
            'name' => 'Zachran Razendra',
            'email' => 'zachranraze@recodex.id',
            'phone' => '+62 812-3456-7890',
            'department' => 'IT',
            'position' => 'System Administrator',
            'face_registered' => false,
            'is_active' => true,
        ]);

        $adninEmployee = Employee::create([
            'employee_id' => 'EMP002',
            'name' => 'Adnin Farizie',
            'email' => 'adninfarizie@recodex.id',
            'phone' => '+62 812-9876-5432',
            'department' => 'IT',
            'position' => 'Software Developer',
            'face_registered' => false,
            'is_active' => true,
        ]);

        // Create sample employees without user accounts
        $sampleEmployee1 = Employee::create([
            'employee_id' => 'EMP003',
            'name' => 'John Doe',
            'email' => 'john.doe@recodex.id',
            'phone' => '+62 813-1111-2222',
            'department' => 'Finance',
            'position' => 'Accountant',
            'face_registered' => false,
            'is_active' => true,
        ]);

        $sampleEmployee2 = Employee::create([
            'employee_id' => 'EMP004',
            'name' => 'Jane Smith',
            'email' => 'jane.smith@recodex.id',
            'phone' => '+62 814-3333-4444',
            'department' => 'HR',
            'position' => 'HR Manager',
            'face_registered' => true,
            'face_gallery_id' => 'attendance_system',
            'is_active' => true,
        ]);

        // Assign employees to locations
        $zachranEmployee->locations()->attach($mainOffice->id, ['is_primary' => true]);
        $zachranEmployee->locations()->attach($branchOffice->id, ['is_primary' => false]);

        $adninEmployee->locations()->attach($mainOffice->id, ['is_primary' => true]);

        $sampleEmployee1->locations()->attach($mainOffice->id, ['is_primary' => true]);

        $sampleEmployee2->locations()->attach($branchOffice->id, ['is_primary' => true]);
        $sampleEmployee2->locations()->attach($mainOffice->id, ['is_primary' => false]);
    }
}
