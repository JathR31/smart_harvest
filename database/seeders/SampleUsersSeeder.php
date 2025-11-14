<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $municipalities = ['Atok', 'Baguio City', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 'Sablan', 'Tuba', 'Tublay'];
        
        $users = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan@example.com',
                'password' => bcrypt('password123'),
                'role' => 'Farmer',
                'status' => 'Active',
                'location' => 'La Trinidad',
                'phone' => '09171234567',
                'farm_name' => 'Dela Cruz Vegetable Farm',
                'farm_size' => 2.5,
                'crop_types' => 'Cabbage, Broccoli, Carrots',
                'years_experience' => 15,
                'last_login' => now()->subHours(2),
                'created_at' => now()->subMonths(2),
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'password' => bcrypt('password123'),
                'role' => 'Field Agent',
                'status' => 'Active',
                'location' => 'Baguio City',
                'phone' => '09181234568',
                'years_experience' => 8,
                'last_login' => now()->subHours(5),
                'created_at' => now()->subMonths(3),
            ],
            [
                'name' => 'Robert Lim',
                'email' => 'robert@example.com',
                'password' => bcrypt('password123'),
                'role' => 'Admin',
                'status' => 'Active',
                'location' => 'Baguio City',
                'phone' => '09191234569',
                'last_login' => now()->subDay(),
                'created_at' => now()->subYear(),
            ],
            [
                'name' => 'Ana Reyes',
                'email' => 'ana@example.com',
                'password' => bcrypt('password123'),
                'role' => 'Researcher',
                'status' => 'Active',
                'location' => 'La Trinidad',
                'phone' => '09201234570',
                'last_login' => now()->subHours(8),
                'created_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Pedro Gonzales',
                'email' => 'pedro@example.com',
                'password' => bcrypt('password123'),
                'role' => 'Farmer',
                'status' => 'Pending',
                'location' => 'Atok',
                'phone' => '09211234571',
                'farm_name' => 'Gonzales Highland Farm',
                'farm_size' => 3.0,
                'crop_types' => 'Potatoes, Lettuce',
                'years_experience' => 10,
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'Carmen Valdez',
                'email' => 'carmen@example.com',
                'password' => bcrypt('password123'),
                'role' => 'Farmer',
                'status' => 'Suspended',
                'location' => 'Buguias',
                'phone' => '09221234572',
                'farm_name' => 'Valdez Organic Farm',
                'farm_size' => 1.8,
                'crop_types' => 'Tomatoes, Peppers',
                'years_experience' => 12,
                'last_login' => now()->subWeeks(2),
                'created_at' => now()->subMonths(8),
            ],
            [
                'name' => 'Jose Martinez',
                'email' => 'jose@example.com',
                'password' => bcrypt('password123'),
                'role' => 'Farmer',
                'status' => 'Active',
                'location' => 'Tublay',
                'phone' => '09231234573',
                'farm_name' => 'Martinez Highland Crops',
                'farm_size' => 4.2,
                'crop_types' => 'Cabbage, Cauliflower, Broccoli',
                'years_experience' => 20,
                'last_login' => now()->subDays(1),
                'created_at' => now()->subMonths(4),
            ],
        ];

        foreach ($users as $userData) {
            \App\Models\User::create($userData);
        }
    }
}
