<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define all available permissions
        $permissions = [
            // Dashboard & Overview
            ['permission' => 'view_dashboard', 'category' => 'Dashboard', 'description' => 'View main dashboard'],
            ['permission' => 'view_analytics', 'category' => 'Dashboard', 'description' => 'View analytics and reports'],
            ['permission' => 'view_statistics', 'category' => 'Dashboard', 'description' => 'View system statistics'],
            
            // User Management
            ['permission' => 'view_users', 'category' => 'User Management', 'description' => 'View user list'],
            ['permission' => 'create_users', 'category' => 'User Management', 'description' => 'Create new users'],
            ['permission' => 'edit_users', 'category' => 'User Management', 'description' => 'Edit user information'],
            ['permission' => 'delete_users', 'category' => 'User Management', 'description' => 'Delete users'],
            ['permission' => 'manage_user_status', 'category' => 'User Management', 'description' => 'Activate/suspend users'],
            ['permission' => 'manage_roles', 'category' => 'User Management', 'description' => 'Manage user roles and permissions'],
            
            // Data Management
            ['permission' => 'view_datasets', 'category' => 'Data Management', 'description' => 'View datasets'],
            ['permission' => 'import_data', 'category' => 'Data Management', 'description' => 'Import crop data'],
            ['permission' => 'export_data', 'category' => 'Data Management', 'description' => 'Export data'],
            ['permission' => 'delete_datasets', 'category' => 'Data Management', 'description' => 'Delete datasets'],
            ['permission' => 'validate_data', 'category' => 'Data Management', 'description' => 'Validate and approve data'],
            
            // Crop Data
            ['permission' => 'view_crop_data', 'category' => 'Crop Data', 'description' => 'View crop records'],
            ['permission' => 'create_crop_data', 'category' => 'Crop Data', 'description' => 'Add crop records'],
            ['permission' => 'edit_crop_data', 'category' => 'Crop Data', 'description' => 'Edit crop records'],
            ['permission' => 'delete_crop_data', 'category' => 'Crop Data', 'description' => 'Delete crop records'],
            
            // ML & Predictions
            ['permission' => 'view_predictions', 'category' => 'ML & Predictions', 'description' => 'View ML predictions'],
            ['permission' => 'generate_predictions', 'category' => 'ML & Predictions', 'description' => 'Generate new predictions'],
            ['permission' => 'view_forecasts', 'category' => 'ML & Predictions', 'description' => 'View yield forecasts'],
            
            // Planting Schedule
            ['permission' => 'view_planting_schedule', 'category' => 'Planting Schedule', 'description' => 'View planting recommendations'],
            ['permission' => 'customize_schedule', 'category' => 'Planting Schedule', 'description' => 'Customize planting schedules'],
            
            // Reports
            ['permission' => 'view_reports', 'category' => 'Reports', 'description' => 'View system reports'],
            ['permission' => 'generate_reports', 'category' => 'Reports', 'description' => 'Generate custom reports'],
            ['permission' => 'download_reports', 'category' => 'Reports', 'description' => 'Download reports'],
        ];

        // Admin role - all permissions
        foreach ($permissions as $perm) {
            RolePermission::updateOrCreate(
                ['role' => 'Admin', 'permission' => $perm['permission']],
                [
                    'category' => $perm['category'],
                    'description' => $perm['description'],
                    'is_enabled' => true
                ]
            );
        }

        // Farmer role - basic permissions
        $farmerPermissions = [
            'view_dashboard', 'view_statistics', 'view_crop_data', 'create_crop_data', 
            'edit_crop_data', 'view_predictions', 'view_forecasts', 
            'view_planting_schedule', 'view_reports'
        ];
        foreach ($permissions as $perm) {
            if (in_array($perm['permission'], $farmerPermissions)) {
                RolePermission::updateOrCreate(
                    ['role' => 'Farmer', 'permission' => $perm['permission']],
                    [
                        'category' => $perm['category'],
                        'description' => $perm['description'],
                        'is_enabled' => true
                    ]
                );
            }
        }

        // Field Agent role - moderate permissions
        $fieldAgentPermissions = [
            'view_dashboard', 'view_analytics', 'view_statistics', 'view_users',
            'view_datasets', 'import_data', 'export_data', 'validate_data',
            'view_crop_data', 'create_crop_data', 'edit_crop_data', 'delete_crop_data',
            'view_predictions', 'generate_predictions', 'view_forecasts',
            'view_planting_schedule', 'view_reports', 'generate_reports'
        ];
        foreach ($permissions as $perm) {
            if (in_array($perm['permission'], $fieldAgentPermissions)) {
                RolePermission::updateOrCreate(
                    ['role' => 'Field Agent', 'permission' => $perm['permission']],
                    [
                        'category' => $perm['category'],
                        'description' => $perm['description'],
                        'is_enabled' => true
                    ]
                );
            }
        }

        // Researcher role
        $researcherPermissions = [
            'view_dashboard', 'view_analytics', 'view_statistics',
            'view_datasets', 'export_data', 'view_crop_data',
            'view_predictions', 'generate_predictions', 'view_forecasts',
            'view_planting_schedule', 'view_reports', 'generate_reports', 'download_reports'
        ];
        foreach ($permissions as $perm) {
            if (in_array($perm['permission'], $researcherPermissions)) {
                RolePermission::updateOrCreate(
                    ['role' => 'Researcher', 'permission' => $perm['permission']],
                    [
                        'category' => $perm['category'],
                        'description' => $perm['description'],
                        'is_enabled' => true
                    ]
                );
            }
        }
    }
}
