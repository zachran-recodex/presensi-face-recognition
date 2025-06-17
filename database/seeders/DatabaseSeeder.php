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
            'name' => 'Zachran Razendra',
            'email' => 'zachranraze@recodex.id',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'employee_id' => 'JKN000',
            'phone' => '+62812345678',
        ]);

        // Create sample regular users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'employee_id' => 'JKN001',
                'phone' => '+62812345679',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'employee_id' => 'JKN002',
                'phone' => '+62812345680',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'employee_id' => 'JKN003',
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
                'name' => 'Telkom University',
                'address' => 'Jl. Telekomunikasi No. 1, Bandung Terusan Buahbatu - Bojongsoang, Sukapura, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40257',
                'latitude' => -6.973046375031589,
                'longitude' => 107.63031802195427,
                'radius' => 1000,
                'is_active' => true,
            ],
            [
                'name' => 'C57 Heavens House',
                'address' => 'Unnamed Road Blk. C No.101, Lengkong, Bojongsoang, Bandung Regency, West Java 40287',
                'latitude' => -6.972749080623897,
                'longitude' => 107.6387899118546,
                'radius' => 100,
                'is_active' => true,
            ],
        ];

        foreach ($locations as $locationData) {
            Location::create($locationData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Default accounts created:');
        $this->command->info('Admin: zachranraze@recodex.id / admin123');
        $this->command->info('Users: john@example.com, jane@example.com, bob@example.com / password');
        $this->command->info('');
        $this->command->info('Sample locations created:');
        $this->command->info('- Telkom University');
        $this->command->info('- C57 Heavens House');
        $this->command->info('');
        $this->command->warn('Remember to:');
        $this->command->warn('1. Update your .env file with Biznet Face API credentials');
        $this->command->warn('2. Change default passwords in production');
        $this->command->warn('3. Configure face gallery ID in .env');
    }
}
