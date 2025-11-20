<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'permission',
        'category',
        'description',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Get all permissions for a specific role
     */
    public static function getPermissionsForRole($role)
    {
        return self::where('role', $role)
            ->where('is_enabled', true)
            ->pluck('permission')
            ->toArray();
    }

    /**
     * Check if a role has a specific permission
     */
    public static function hasPermission($role, $permission)
    {
        return self::where('role', $role)
            ->where('permission', $permission)
            ->where('is_enabled', true)
            ->exists();
    }

    /**
     * Get all available permissions grouped by category
     */
    public static function getAllPermissionsGrouped()
    {
        return self::select('category', 'permission', 'description')
            ->distinct()
            ->orderBy('category')
            ->orderBy('permission')
            ->get()
            ->groupBy('category');
    }
}
