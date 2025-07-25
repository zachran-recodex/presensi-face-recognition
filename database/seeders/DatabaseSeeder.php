<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample locations first
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

        // Create super admin user (zachranraze)
        User::create([
            'name' => 'Zachran Razendra',
            'username' => 'zachranraze',
            'email' => 'zachranraze@recodex.id',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'employee_id' => 'JKN000',
            'location_id' => 2,
            'phone' => '+62812345678',
        ]);

        // Create regular admin user
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'employee_id' => 'JKN001',
            'location_id' => 1,
            'phone' => '+62812345677',
        ]);

        // Create sample regular users
        $users = [
            [
                'name' => 'John Doe',
                'username' => 'johndoe',
                'email' => 'john@example.com',
                'employee_id' => 'JKN002',
                'location_id' => 2,
                'phone' => '+62812345679',
            ],
            [
                'name' => 'Jane Smith',
                'username' => 'janesmith',
                'email' => 'jane@example.com',
                'employee_id' => 'JKN003',
                'location_id' => 1,
                'phone' => '+62812345680',
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'username' => $userData['username'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'employee_id' => $userData['employee_id'],
                'location_id' => $userData['location_id'],
                'phone' => $userData['phone'],
            ]);
        }
    }
}
