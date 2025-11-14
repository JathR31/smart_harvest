<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Admin Activity Logs
        \App\Models\AdminActivityLog::insert([
            [
                'action_type' => 'upload',
                'description' => 'Admin uploaded a new dataset (2025-Q3-yields.csv)',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'metadata' => json_encode(['file' => '2025-Q3-yields.csv', 'size' => '2.5MB']),
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'action_type' => 'warning',
                'description' => 'System flagged 3 data anomalies in Banaue records',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'System',
                'metadata' => json_encode(['location' => 'Banaue', 'count' => 3]),
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'action_type' => 'security',
                'description' => 'System blocked login attempt from unknown IP',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Unknown',
                'metadata' => json_encode(['blocked_ip' => '192.168.1.100', 'attempts' => 5]),
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);

        // Seed Data Validation Alerts
        \App\Models\DataValidationAlert::create([
            'record_id' => 'FARM-2025-487',
            'issue_description' => 'Unusually high yield value (15.2 mt/ha)',
            'status' => 'Pending',
            'severity' => 'High',
            'data_snapshot' => json_encode(['yield' => 15.2, 'expected_range' => '8-12']),
            'created_at' => now()->subHours(10),
            'updated_at' => now()->subHours(10),
        ]);

        \App\Models\DataValidationAlert::create([
            'record_id' => 'FARM-2025-156',
            'issue_description' => 'Missing soil moisture data',
            'status' => 'Pending',
            'severity' => 'Medium',
            'data_snapshot' => json_encode(['missing_field' => 'soil_moisture']),
            'created_at' => now()->subHours(8),
            'updated_at' => now()->subHours(8),
        ]);

        \App\Models\DataValidationAlert::create([
            'record_id' => 'FARM-2025-892',
            'issue_description' => 'Duplicate entry detected',
            'status' => 'Resolved',
            'severity' => 'Low',
            'data_snapshot' => json_encode(['duplicate_of' => 'FARM-2025-891']),
            'resolution_notes' => 'Duplicate removed from database',
            'resolved_at' => now()->subHours(3),
            'created_at' => now()->subDay(),
            'updated_at' => now()->subHours(3),
        ]);

        \App\Models\DataValidationAlert::create([
            'record_id' => 'FARM-2025-223',
            'issue_description' => 'Temperature reading outside normal range (-5°C)',
            'status' => 'Pending',
            'severity' => 'High',
            'data_snapshot' => json_encode(['temperature' => -5, 'location' => 'Kabayan']),
            'created_at' => now()->subHours(6),
            'updated_at' => now()->subHours(6),
        ]);
    }
}
