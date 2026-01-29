<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperadminSecurityQuestion;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superadmin user
        // Note: Don't use Hash::make() because the User model has 'password' => 'hashed' cast
        $superadmin = User::updateOrCreate(
            ['username' => 'smartharvestsuperadmin'],
            [
                'name' => 'SmartHarvest Super Administrator',
                'email' => 'superadmin@smartharvest.ph',
                'username' => 'smartharvestsuperadmin',
                'password' => 'Admin123', // Will be auto-hashed by model cast
                'role' => 'Admin',
                'is_superadmin' => true,
                'admin_type' => 'superadmin',
                'status' => 'Active',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Superadmin user created/updated:');
        $this->command->info('  Username: smartharvestsuperadmin');
        $this->command->info('  Password: Admin123');

        // Create Security Questions about DA-CAR (Department of Agriculture - Cordillera Administrative Region)
        $questions = [
            [
                'question' => 'What is the regional office of the Department of Agriculture in the Cordillera Administrative Region?',
                'answer' => 'DA-CAR',
            ],
            [
                'question' => 'In which city is the DA-CAR Regional Office located?',
                'answer' => 'Baguio City',
            ],
            [
                'question' => 'How many provinces are covered by the Cordillera Administrative Region?',
                'answer' => '6',
            ],
            [
                'question' => 'What is the main agricultural export product of Benguet province?',
                'answer' => 'Vegetables',
            ],
            [
                'question' => 'Which province in CAR is known as the "Salad Bowl of the Philippines"?',
                'answer' => 'Benguet',
            ],
            [
                'question' => 'What is the Summer Capital of the Philippines located in CAR?',
                'answer' => 'Baguio City',
            ],
            [
                'question' => 'What municipality in Benguet is known as the vegetable trading center?',
                'answer' => 'La Trinidad',
            ],
            [
                'question' => 'What rice terraces in CAR are listed as UNESCO World Heritage Site?',
                'answer' => 'Banaue Rice Terraces',
            ],
            [
                'question' => 'What is the indigenous coffee variety grown in the Cordillera highlands?',
                'answer' => 'Arabica',
            ],
            [
                'question' => 'What year was the Cordillera Administrative Region created?',
                'answer' => '1987',
            ],
        ];

        foreach ($questions as $q) {
            SuperadminSecurityQuestion::updateOrCreate(
                ['question' => $q['question']],
                [
                    'answer' => $q['answer'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('10 security questions for DA-CAR created successfully!');
    }
}
