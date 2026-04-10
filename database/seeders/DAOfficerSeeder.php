<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DAOfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Create DA Officer user
            $daOfficer = User::updateOrCreate(
                ['email' => 'daofficer@smartharvest.ph'],
                [
                    'name' => 'DA Officer',
                    'email' => 'daofficer@smartharvest.ph',
                    'username' => 'daofficer',
                    'password' => 'AdminAccess123', // Will be auto-hashed by model cast
                    'role' => 'DA Admin',
                    'admin_type' => 'da_officer',
                    'status' => 'Active',
                    'email_verified_at' => now(),
                    'office' => 'Department of Agriculture - Cordillera Administrative Region',
                    'position' => 'Agricultural Extension Officer',
                    'employee_id' => 'DA-CAR-001',
                ]
            );

            $this->command->info('✅ DA Officer user created/updated:');
            $this->command->info('  Email: daofficer@smartharvest.ph');
            $this->command->info('  Username: daofficer');
            $this->command->info('  Password: AdminAccess123');
        } catch (\Exception $e) {
            $this->command->error('❌ DA Officer seeder failed: ' . $e->getMessage());
        }
    }
}
