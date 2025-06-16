<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'employee_id' => 'ADM001',
            'phone' => '+62812345678',
        ]);

        // Create sample regular users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'employee_id' => 'EMP001',
                'phone' => '+62812345679',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'employee_id' => 'EMP002',
                'phone' => '+62812345680',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'employee_id' => 'EMP003',
                'phone' => '+62812345681',
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'employee_id' => $userData['employee_id'],
                'phone' => $userData['phone'],
            ]);
        }

        // Create sample locations
        $locations = [
            [
                'name' => 'Main Office',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10110, Indonesia',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'radius' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Branch Office Bandung',
                'address' => 'Jl. Asia Afrika No. 456, Bandung, Jawa Barat 40111, Indonesia',
                'latitude' => -6.917464,
                'longitude' => 107.619123,
                'radius' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Warehouse Surabaya',
                'address' => 'Jl. Raya Industri No. 789, Surabaya, Jawa Timur 60111, Indonesia',
                'latitude' => -7.257472,
                'longitude' => 112.752090,
                'radius' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Remote Work Hub',
                'address' => 'Virtual Location for Remote Workers',
                'latitude' => null,
                'longitude' => null,
                'radius' => 1000,
                'is_active' => true,
            ],
            [
                'name' => 'Training Center (Inactive)',
                'address' => 'Jl. Pendidikan No. 321, Yogyakarta, DIY 55111, Indonesia',
                'latitude' => -7.795580,
                'longitude' => 110.369492,
                'radius' => 100,
                'is_active' => false,
            ],
        ];

        foreach ($locations as $locationData) {
            Location::create($locationData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Default accounts created:');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Users: john@example.com, jane@example.com, bob@example.com / password');
        $this->command->info('');
        $this->command->info('Sample locations created:');
        $this->command->info('- Main Office (Jakarta)');
        $this->command->info('- Branch Office (Bandung)');
        $this->command->info('- Warehouse (Surabaya)');
        $this->command->info('- Remote Work Hub (No coordinates)');
        $this->command->info('- Training Center (Inactive)');
        $this->command->info('');
        $this->command->warn('Remember to:');
        $this->command->warn('1. Update your .env file with Biznet Face API credentials');
        $this->command->warn('2. Change default passwords in production');
        $this->command->warn('3. Configure face gallery ID in .env');
    }
}
