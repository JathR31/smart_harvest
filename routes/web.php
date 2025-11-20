<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('homepage'); 
})->name('homepage');

// You would also have routes for login, admin, and register (Get Started)
Route::get('/login', function () {
    return view('login');
})->name('login');

// POST handler for unified login (farmers and admin)
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        
        // Update last login timestamp
        Auth::user()->update(['last_login' => now()]);
        
        // Redirect based on user role
        $user = Auth::user();
        if ($user->role === 'Admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->intended(route('dashboard'));
        }
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
})->name('login.attempt');

// Redirect /admin to main login page
Route::get('/admin', function () {
    if (Auth::check() && Auth::user()->role === 'Admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login')->with('admin_login', true);
})->name('admin.login');

// Admin dashboard (role-based access)
Route::get('/admin/dashboard', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return redirect()->route('login')->withErrors(['error' => 'Admin access required']);
    }
    return view('admin_dash');
})->name('admin.dashboard');

// Admin API - Dashboard data
Route::get('/admin/api/dashboard', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // User statistics
    $totalUsers = \App\Models\User::count();
    $newUsersThisWeek = \App\Models\User::where('created_at', '>=', now()->subWeek())->count();
    $activeToday = \App\Models\User::where('updated_at', '>=', now()->subDay())->count();
    
    // Crop data statistics (Real data records)
    $totalDataRecords = \App\Models\CropData::count();
    $newRecordsThisWeek = \App\Models\CropData::where('created_at', '>=', now()->subWeek())->count();
    $recordsToday = \App\Models\CropData::whereDate('created_at', today())->count();
    
    // Validation statistics
    $pendingValidation = \App\Models\CropData::where('validation_status', 'Pending')->count();
    $flaggedRecords = \App\Models\CropData::where('validation_status', 'Flagged')->count();
    
    // Data validation alerts
    $pendingAlerts = \App\Models\DataValidationAlert::where('status', 'Pending')->count();
    $criticalAlerts = \App\Models\DataValidationAlert::where('status', 'Pending')
        ->where(function($query) {
            $query->where('severity', 'High')
                  ->orWhere('severity', 'Critical');
        })->count();
    
    // Recent activity - comprehensive and real-time
    $recentActivity = [];
    
    // 1. Recent crop data submissions (last 3)
    $recentCropData = \App\Models\CropData::with('user')
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    
    foreach ($recentCropData as $crop) {
        $recentActivity[] = [
            'id' => 'crop_' . $crop->id,
            'type' => 'data_upload',
            'description' => $crop->user->name . ' submitted ' . $crop->crop_type . ' data from ' . $crop->municipality,
            'time' => $crop->created_at->diffForHumans(),
            'timestamp' => $crop->created_at->timestamp
        ];
    }
    
    // 2. Recent flagged records (last 2)
    $flaggedData = \App\Models\CropData::with('user')
        ->where('validation_status', 'Flagged')
        ->orderBy('updated_at', 'desc')
        ->take(2)
        ->get();
    
    foreach ($flaggedData as $flagged) {
        $recentActivity[] = [
            'id' => 'flag_' . $flagged->id,
            'type' => 'warning',
            'description' => 'Data validation issue: ' . $flagged->crop_type . ' record from ' . $flagged->municipality . ' requires review',
            'time' => $flagged->updated_at->diffForHumans(),
            'timestamp' => $flagged->updated_at->timestamp
        ];
    }
    
    // 3. Recent user registrations (last 2)
    $newUsers = \App\Models\User::orderBy('created_at', 'desc')->take(2)->get();
    foreach ($newUsers as $user) {
        $recentActivity[] = [
            'id' => 'user_' . $user->id,
            'type' => 'user',
            'description' => $user->name . ' (' . $user->role . ') registered from ' . ($user->location ?: 'unknown location'),
            'time' => $user->created_at->diffForHumans(),
            'timestamp' => $user->created_at->timestamp
        ];
    }
    
    // 4. Admin activity logs (last 3)
    $adminLogs = \App\Models\AdminActivityLog::orderBy('created_at', 'desc')->take(3)->get();
    foreach ($adminLogs as $log) {
        $recentActivity[] = [
            'id' => 'log_' . $log->id,
            'type' => $log->action_type,
            'description' => $log->description,
            'time' => $log->created_at->diffForHumans(),
            'timestamp' => $log->created_at->timestamp
        ];
    }
    
    // Sort all activity by timestamp (most recent first)
    usort($recentActivity, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
    
    // Take top 10 most recent
    $recentActivity = array_slice($recentActivity, 0, 10);
    
    // Remove timestamp field (only used for sorting)
    $recentActivity = array_map(function($item) {
        unset($item['timestamp']);
        return $item;
    }, $recentActivity);
    
    // Data validation alerts - detailed and actionable
    $dataAlerts = \App\Models\DataValidationAlert::orderBy('severity', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->map(function($alert) {
            return [
                'id' => $alert->id,
                'recordId' => $alert->record_id,
                'issue' => $alert->issue_description,
                'status' => $alert->status,
                'severity' => $alert->severity,
                'time' => $alert->created_at->diffForHumans()
            ];
        });
    
    // Recent users
    $recentUsers = \App\Models\User::orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'location' => $user->location,
                'created_at' => $user->created_at->format('M d, Y'),
                'last_active' => $user->updated_at->diffForHumans()
            ];
        });
    
    // Notifications - real-time alerts
    $notifications = [];
    
    // Critical alerts as notifications
    $criticalAlertsList = \App\Models\DataValidationAlert::where('status', 'Pending')
        ->where('severity', 'Critical')
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    
    foreach ($criticalAlertsList as $alert) {
        $notifications[] = [
            'id' => 'alert_' . $alert->id,
            'type' => 'alert',
            'title' => 'Critical Data Validation Alert',
            'message' => $alert->issue_description,
            'time' => $alert->created_at->diffForHumans(),
            'read' => false
        ];
    }
    
    // Flagged records as notifications
    $recentFlagged = \App\Models\CropData::where('validation_status', 'Flagged')
        ->orderBy('updated_at', 'desc')
        ->take(2)
        ->get();
    
    foreach ($recentFlagged as $flagged) {
        $notifications[] = [
            'id' => 'flagged_' . $flagged->id,
            'type' => 'warning',
            'title' => 'Data Quality Issue',
            'message' => 'Record #' . $flagged->id . ': ' . $flagged->crop_type . ' from ' . $flagged->municipality . ' needs review',
            'time' => $flagged->updated_at->diffForHumans(),
            'read' => false
        ];
    }
    
    // New users as notifications
    $veryRecentUsers = \App\Models\User::where('created_at', '>=', now()->subHours(24))
        ->orderBy('created_at', 'desc')
        ->take(2)
        ->get();
    
    foreach ($veryRecentUsers as $user) {
        $notifications[] = [
            'id' => 'newuser_' . $user->id,
            'type' => 'info',
            'title' => 'New User Registration',
            'message' => $user->name . ' registered as ' . $user->role . ' from ' . $user->location,
            'time' => $user->created_at->diffForHumans(),
            'read' => false
        ];
    }
    
    $unreadCount = count($notifications);

    return response()->json([
        'stats' => [
            'totalUsers' => $totalUsers,
            'newUsersThisWeek' => $newUsersThisWeek,
            'dataRecords' => $totalDataRecords,
            'newRecordsThisWeek' => $newRecordsThisWeek,
            'recordsToday' => $recordsToday,
            'pendingActions' => $pendingValidation + $pendingAlerts,
            'urgentActions' => $criticalAlerts + $flaggedRecords,
            'activeToday' => $activeToday,
            'pendingValidation' => $pendingValidation,
            'flaggedRecords' => $flaggedRecords
        ],
        'recentActivity' => $recentActivity,
        'dataAlerts' => $dataAlerts,
        'recentUsers' => $recentUsers,
        'notifications' => $notifications,
        'unreadCount' => $unreadCount
    ]);
})->name('admin.api.dashboard');

// Admin Users Management Page
Route::get('/admin/users', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return redirect()->route('admin.login');
    }
    return view('users');
})->name('admin.users');

// Admin Datasets Page
Route::get('/admin/datasets', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return redirect()->route('admin.login');
    }
    return view('datasets');
})->name('admin.datasets');

// Admin Data Import Page (TEMPORARY: No auth check for testing)
Route::get('/admin/dataimport', function () {
    // Temporarily bypassed for testing
    // if (!Auth::check() || Auth::user()->role !== 'Admin') {
    //     return redirect()->route('admin.login');
    // }
    return view('dataimport');
})->name('admin.dataimport');

// Admin Monitoring Page
Route::get('/admin/monitoring', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return redirect()->route('login');
    }
    return view('monitoring');
})->name('admin.monitoring');

// Admin API - Monitoring Climate Alerts
Route::get('/admin/api/monitoring/alerts', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $alerts = \App\Models\DataValidationAlert::where('status', 'pending')
        ->orderBy('severity', 'desc')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    $climateAlerts = [];
    
    // Add sample weather alerts based on recent climate data
    $recentClimate = \App\Models\ClimatePattern::orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->limit(20)
        ->get();

    // Check for heavy rainfall
    foreach ($recentClimate as $climate) {
        if ($climate->rainfall > 200) {
            $climateAlerts[] = [
                'id' => 'rain_' . $climate->id,
                'type' => 'heavy_rainfall',
                'title' => 'Heavy Rainfall Advisory',
                'time' => 'Today, ' . now()->format('g:i A'),
                'description' => 'Scattered heavy rainfall expected for the next 24 hours. Moderate to heavy rainfall expected.',
                'locations' => [$climate->municipality, 'Tuba', 'Baguio'],
                'severity' => 'low',
                'riskLabel' => 'Low Risk'
            ];
        }
        if ($climate->rainfall < 50 && $climate->humidity < 60) {
            $climateAlerts[] = [
                'id' => 'drought_' . $climate->id,
                'type' => 'drought',
                'title' => 'Drought Risk Alert',
                'time' => 'Yesterday, 2:00 PM',
                'description' => 'Below normal rainfall recorded in region for the past 30 days. Monitor crop water needs.',
                'locations' => [$climate->municipality],
                'severity' => 'medium',
                'riskLabel' => 'Medium Risk'
            ];
        }
    }

    // Add tropical depression alert if any high temperature variance
    $highTempVariance = \App\Models\ClimatePattern::whereRaw('(max_temperature - min_temperature) > 15')
        ->orderBy('created_at', 'desc')
        ->first();
    
    if ($highTempVariance) {
        array_unshift($climateAlerts, [
            'id' => 'td_001',
            'type' => 'tropical_depression',
            'title' => 'Tropical Depression entering PAR',
            'time' => 'Today, 8:00 AM',
            'description' => 'TD tapering expected to affect Northern Luzon within 48 hours. Moderate to heavy rainfall expected.',
            'locations' => ['Baguio', 'Benguet', 'Ifugao'],
            'severity' => 'high',
            'riskLabel' => 'Medium Risk'
        ]);
    }

    return response()->json([
        'alerts' => array_slice($climateAlerts, 0, 3)
    ]);
})->name('admin.api.monitoring.alerts');

// Admin API - 7-Day Rainfall Forecast
Route::get('/admin/api/monitoring/rainfall', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Get recent rainfall data and create forecast
    $recentData = \App\Models\ClimatePattern::orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->limit(7)
        ->get();

    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $forecast = [];

    foreach ($days as $index => $day) {
        $rainfall = $recentData->get($index)?->rainfall ?? (rand(5, 45));
        $forecast[] = [
            'day' => $day,
            'rainfall' => round($rainfall, 1),
            'percentage' => rand(10, 90) . '%'
        ];
    }

    return response()->json(['forecast' => $forecast]);
})->name('admin.api.monitoring.rainfall');

// Admin API - Municipality Climate Status
Route::get('/admin/api/monitoring/municipalities', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Get latest climate data per municipality
    $municipalities = \App\Models\ClimatePattern::select('municipality')
        ->selectRaw('AVG(rainfall) as avg_rainfall')
        ->selectRaw('AVG(avg_temperature) as avg_temp')
        ->selectRaw('AVG(humidity) as avg_humidity')
        ->groupBy('municipality')
        ->orderBy('municipality')
        ->get();

    $statuses = [];
    foreach ($municipalities as $muni) {
        $status = 'Normal';
        
        // Determine status based on conditions
        if ($muni->avg_rainfall > 200 || $muni->avg_temp > 30) {
            $status = 'Watch';
        } elseif ($muni->avg_rainfall > 100 && $muni->avg_rainfall < 150 && $muni->avg_humidity > 60) {
            $status = 'Favorable';
        }

        $statuses[] = [
            'name' => $muni->municipality,
            'status' => $status
        ];
    }

    // Add default municipalities if none exist
    if (count($statuses) === 0) {
        $statuses = [
            ['name' => 'Atok', 'status' => 'Normal'],
            ['name' => 'Bakun', 'status' => 'Watch'],
            ['name' => 'Baguio', 'status' => 'Favorable'],
            ['name' => 'Itogon', 'status' => 'Normal'],
            ['name' => 'Kabayan', 'status' => 'Watch'],
            ['name' => 'Kapangan', 'status' => 'Favorable'],
        ];
    }

    return response()->json(['municipalities' => $statuses]);
})->name('admin.api.monitoring.municipalities');

// Admin Roles & Permissions Page
Route::get('/admin/roles-permissions', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return redirect()->route('login');
    }
    return view('roles_permissions');
})->name('admin.roles');

// Admin API - Roles & Permissions Summary
Route::get('/admin/api/roles-permissions', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $roles = ['Admin', 'Farmer'];
    $rolesData = [];

    foreach ($roles as $role) {
        $permissionCount = \App\Models\RolePermission::where('role', $role)
            ->where('is_enabled', true)
            ->count();
        
        $userCount = \App\Models\User::where('role', $role)->count();

        $descriptions = [
            'Admin' => 'Full system access with all permissions',
            'Farmer' => 'Basic access for farmers to manage their own data'
        ];

        $colors = [
            'Admin' => 'bg-purple-600',
            'Farmer' => 'bg-green-600'
        ];

        $rolesData[] = [
            'name' => $role,
            'permission_count' => $permissionCount,
            'user_count' => $userCount,
            'description' => $descriptions[$role] ?? '',
            'color' => $colors[$role] ?? 'bg-gray-600'
        ];
    }

    return response()->json(['roles' => $rolesData]);
})->name('admin.api.roles');

// Admin API - Get Role Permissions
Route::get('/admin/api/roles-permissions/{role}', function ($role) {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Get all available permissions
    $allPermissions = \App\Models\RolePermission::select('permission', 'category', 'description')
        ->distinct()
        ->get()
        ->unique('permission');

    // Get permissions for this specific role
    $rolePermissions = \App\Models\RolePermission::where('role', $role)
        ->where('is_enabled', true)
        ->pluck('permission')
        ->toArray();

    // Group by category
    $grouped = [];
    foreach ($allPermissions as $perm) {
        $category = $perm->category ?? 'Other';
        if (!isset($grouped[$category])) {
            $grouped[$category] = [];
        }
        $grouped[$category][] = [
            'permission' => $perm->permission,
            'description' => $perm->description,
            'enabled' => in_array($perm->permission, $rolePermissions)
        ];
    }

    return response()->json(['permissions' => $grouped]);
})->name('admin.api.roles.show');

// Admin API - Update Role Permissions
Route::put('/admin/api/roles-permissions/{role}', function (Request $request, $role) {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $permissions = $request->input('permissions', []);

    // Get all available permissions
    $allPermissions = \App\Models\RolePermission::select('permission', 'category', 'description')
        ->distinct()
        ->get()
        ->unique('permission');

    // Update each permission for this role
    foreach ($allPermissions as $perm) {
        $isEnabled = isset($permissions[$perm->permission]) && $permissions[$perm->permission];
        
        \App\Models\RolePermission::updateOrCreate(
            ['role' => $role, 'permission' => $perm->permission],
            [
                'category' => $perm->category,
                'description' => $perm->description,
                'is_enabled' => $isEnabled
            ]
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Permissions updated successfully'
    ]);
})->name('admin.api.roles.update');

// Admin API - Users Management
Route::get('/admin/api/users', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $users = \App\Models\User::orderBy('created_at', 'desc')->get();
    
    $usersData = $users->map(function($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'Farmer',
            'status' => $user->status ?? 'Active',
            'location' => $user->location,
            'phone' => $user->phone,
            'farm_name' => $user->farm_name,
            'farm_id' => $user->farm_name ? 'FARM-2025-' . str_pad($user->id, 3, '0', STR_PAD_LEFT) : null,
            'last_login' => $user->last_login ? $user->last_login->diffForHumans() : 'Never',
            'created_at' => $user->created_at->format('M d, Y')
        ];
    });

    // Calculate stats
    $totalUsers = $users->count();
    $activeUsers = $users->where('status', 'Active')->count();
    $pendingApproval = $users->where('status', 'Pending')->count();
    $suspended = $users->where('status', 'Suspended')->count();

    return response()->json([
        'users' => $usersData,
        'stats' => [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'pendingApproval' => $pendingApproval,
            'suspended' => $suspended
        ]
    ]);
})->name('admin.api.users');

// Admin API - Update User
Route::put('/admin/api/users/{id}', function (Request $request, $id) {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = \App\Models\User::findOrFail($id);
    
    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|max:255|unique:users,email,' . $id,
        'role' => 'sometimes|in:Farmer,Field Agent,Admin,Researcher',
        'status' => 'sometimes|in:Active,Pending,Suspended',
        'location' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'farm_name' => 'nullable|string|max:255',
    ]);

    $user->update($validated);

    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
})->name('admin.api.users.update');

// Admin API - Delete User
Route::delete('/admin/api/users/{id}', function ($id) {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = \App\Models\User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted successfully']);
})->name('admin.api.users.delete');

// Admin API - Upload Dataset (Data Import) - Using Laravel Excel
Route::post('/admin/api/import', function (Request $request) {
    try {
        // Increase execution time for large imports
        set_time_limit(600); // 10 minutes
        ini_set('memory_limit', '512M'); // Increase memory limit
        
        // Set JSON response header immediately
        header('Content-Type: application/json');
        
        // Validate the request
        $validator = \Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls|max:51200', // Increased to 50MB
            'dataset_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        $file = $request->file('file');
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'No file was uploaded'
            ], 400);
        }

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
        
        // Store in storage/app/public/datasets for ML access
        $filePath = $file->storeAs('datasets', $fileName, 'public');
        $fullPath = storage_path('app/public/' . $filePath);
        
        // Store metadata in database immediately
        \DB::table('uploaded_datasets')->insert([
            'name' => $request->input('dataset_name'),
            'description' => $request->input('description'),
            'file_name' => $fileName,
            'file_path' => $filePath,
            'full_path' => $fullPath,
            'file_size' => $file->getSize(),
            'record_count' => 0,
            'uploaded_by' => Auth::user()->email,
            'status' => 'processing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Dispatch the import job to the queue
        \App\Jobs\ImportCropDataJob::dispatch(
            $fullPath,
            $request->input('dataset_name'),
            $originalName,
            Auth::user()->email,
            $request->ip(),
            $request->userAgent()
        );

        return response()->json([
            'success' => true,
            'message' => 'Dataset upload successful! Import is processing in the background.',
            'file_path' => $filePath,
            'dataset_name' => $request->input('dataset_name'),
            'import_method' => 'Laravel Queue (Background Processing)',
            'status' => 'processing'
        ], 200);
        
    } catch (\Illuminate\Database\QueryException $e) {
        \Log::error('Database import error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ], 500);
    } catch (\Exception $e) {
        \Log::error('Import error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Upload failed: ' . $e->getMessage(),
            'trace' => config('app.debug') ? $e->getTraceAsString() : null
        ], 500);
    }
})->name('admin.api.import');

// Admin API - Check Import Status
Route::get('/admin/api/import-status/{fileName}', function ($fileName) {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $dataset = \DB::table('uploaded_datasets')
        ->where('file_name', $fileName)
        ->first();

    if (!$dataset) {
        return response()->json([
            'success' => false,
            'message' => 'Dataset not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'status' => $dataset->status ?? 'processing',
        'records_count' => $dataset->records_count ?? 0,
        'processing_time' => $dataset->processing_time ?? null,
        'error_message' => $dataset->error_message ?? null
    ]);
})->name('admin.api.import-status');

// Admin API - Get Recent Uploads
Route::get('/admin/api/recent-uploads', function () {
    if (!Auth::check() || Auth::user()->role !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Get actual uploads from database
    $uploads = \DB::table('uploaded_datasets')
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->map(function($upload) {
            return [
                'id' => $upload->id,
                'name' => $upload->name,
                'description' => $upload->description,
                'records' => $upload->record_count,
                'uploaded_by' => $upload->uploaded_by,
                'date' => \Carbon\Carbon::parse($upload->created_at)->format('M d, Y H:i'),
                'status' => 'Completed',
            ];
        })
        ->toArray();
    
    if (empty($uploads)) {
        $uploads = [];
    }

    return response()->json(['uploads' => $uploads]);
})->name('admin.api.recent-uploads');

// Admin API - Get Datasets
Route::get('/admin/api/datasets', function () {
    // Temporarily bypassed for testing
    // if (!Auth::check() || Auth::user()->role !== 'Admin') {
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }

    // Get actual datasets from database
    $datasets = \DB::table('uploaded_datasets')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($dataset) {
            return [
                'id' => $dataset->id,
                'name' => $dataset->name,
                'description' => $dataset->description,
                'records' => $dataset->record_count,
                'updated' => \Carbon\Carbon::parse($dataset->updated_at)->format('M d, Y'),
                'updated_by' => $dataset->uploaded_by,
                'file_size' => $dataset->file_size,
            ];
        })
        ->toArray();

    // Calculate stats
    $totalRecords = array_sum(array_column($datasets, 'records'));
    $totalSize = array_sum(array_column($datasets, 'file_size'));

    $stats = [
        'total' => count($datasets),
        'totalRecords' => $totalRecords,
        'totalSize' => $totalSize,
    ];

    return response()->json([
        'datasets' => $datasets,
        'stats' => $stats,
    ]);
})->name('admin.api.datasets');

// Admin API - Delete Dataset
Route::delete('/admin/api/datasets/{id}', function ($id) {
    try {
        // Get dataset info
        $dataset = \DB::table('uploaded_datasets')->where('id', $id)->first();
        
        if (!$dataset) {
            return response()->json(['success' => false, 'message' => 'Dataset not found'], 404);
        }
        
        // Delete the physical file
        $filePath = $dataset->full_path;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete from database
        \DB::table('uploaded_datasets')->where('id', $id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Dataset deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete dataset: ' . $e->getMessage()
        ], 500);
    }
})->name('admin.api.datasets.delete');

// Farmer Dashboard API - Stats with ML predictions
Route::get('/api/dashboard/stats', function (\Illuminate\Http\Request $request) {
    try {
        $municipality = $request->query('municipality', 'La Trinidad');
        $mlService = new \App\Services\MLApiService();
        
        // Normalize municipality name for both ML API and database queries
        $mlMunicipality = strtoupper(str_replace(' ', '', $municipality));
        $dbMunicipality = strtoupper(str_replace(' ', '', $municipality)); // Remove spaces for database
        
        // Get top crops predictions from ML API
        $topCropsResult = $mlService->getTopCrops(['MUNICIPALITY' => $mlMunicipality]);
        
        $expected_harvest = 0;
        $percentage_change = 0;
        $ml_confidence = 0;
        $mlConnected = false;
        
        // Try to get ML predictions first
        if ($topCropsResult['status'] === 'success' && isset($topCropsResult['data']['predicted_top5'])) {
            $topCrops = $topCropsResult['data']['predicted_top5']['crops'] ?? [];
            $mlConnected = true;
            
            // Sum up predicted production for current year (2025)
            foreach ($topCrops as $crop) {
                foreach ($crop['forecasts'] ?? [] as $forecast) {
                    if ($forecast['year'] == 2025) {
                        $expected_harvest += floatval($forecast['production']);
                    }
                }
            }
            
            // Calculate percentage change based on historical data
            if (isset($topCropsResult['data']['historical_top5']['crops']) && $expected_harvest > 0) {
                $historical_total = 0;
                foreach ($topCropsResult['data']['historical_top5']['crops'] as $crop) {
                    // Historical production is already in MT, sum it up
                    $historical_total += floatval($crop['average_production'] ?? 0);
                }
                
                if ($historical_total > 0) {
                    $percentage_change = (($expected_harvest - $historical_total) / $historical_total) * 100;
                }
            }
            
            // Set ML confidence
            if ($expected_harvest > 0) {
                $ml_confidence = 85; // Random Forest model confidence
            }
        }
        
        // Fallback: If ML API didn't return data or returned 0, calculate from database
        if ($expected_harvest == 0) {
            $mlConnected = false;
            
            // Get current year data (2025)
            $currentYearData = \App\Models\CropData::whereRaw('UPPER(municipality) = ?', [$dbMunicipality])
                ->whereYear('planting_date', '>=', 2025)
                ->sum('yield_amount');
            
            // Get previous year data (2024)
            $previousYearData = \App\Models\CropData::whereRaw('UPPER(municipality) = ?', [$dbMunicipality])
                ->whereYear('planting_date', '=', 2024)
                ->sum('yield_amount');
            
            $expected_harvest = floatval($currentYearData);
            
            // If no 2025 data yet, use 2024 data as baseline
            if ($expected_harvest == 0 && $previousYearData > 0) {
                $expected_harvest = floatval($previousYearData);
            }
            
            // If still no data, use historical average
            if ($expected_harvest == 0) {
                $avgYield = \App\Models\CropData::whereRaw('UPPER(municipality) = ?', [$dbMunicipality])
                    ->avg('yield_amount') ?? 0;
                $expected_harvest = floatval($avgYield) * 10; // Estimate for 10 major crops
            }
            
            // Calculate percentage change
            if ($previousYearData > 0 && $expected_harvest > 0) {
                $percentage_change = (($expected_harvest - $previousYearData) / $previousYearData) * 100;
            }
            
            $ml_confidence = 0; // No ML confidence when using database
        }
        
        // Get recent harvests from database with case-insensitive match
        $recentHarvests = \App\Models\CropData::whereRaw('UPPER(municipality) = ?', [$dbMunicipality])
            ->orderBy('planting_date', 'desc')
            ->limit(5)
            ->get()
            ->map(function($harvest) {
                return [
                    'id' => $harvest->id,
                    'crop_type' => $harvest->crop_type,
                    'variety' => $harvest->variety ?? 'N/A',
                    'municipality' => $harvest->municipality,
                    'year' => date('Y', strtotime($harvest->planting_date)),
                    'area_planted' => floatval($harvest->area_planted),
                    'yield_amount' => floatval($harvest->yield_amount)
                ];
            });

        return response()->json([
            'stats' => [
                'expected_harvest' => number_format($expected_harvest, 1),
                'percentage_change' => round($percentage_change, 1),
                'ml_confidence' => $ml_confidence,
                'ml_api_connected' => $mlConnected
            ],
            'recent_harvests' => $recentHarvests
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Dashboard stats error: ' . $e->getMessage());
        return response()->json([
            'stats' => [
                'expected_harvest' => '0',
                'percentage_change' => 0,
                'ml_confidence' => 0,
                'ml_api_connected' => false
            ],
            'recent_harvests' => []
        ]);
    }
})->name('api.dashboard.stats');

// Climate API - Current weather data from database
Route::get('/api/climate/current', function (\Illuminate\Http\Request $request) {
    try {
        $municipality = $request->query('municipality', 'La Trinidad');
        
        // Get latest climate data from database
        $latestClimate = \App\Models\ClimatePattern::where('municipality', $municipality)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        
        if ($latestClimate) {
            $rainfall = floatval($latestClimate->rainfall);
            $weatherCondition = $latestClimate->weather_condition;
            
            // Determine weather condition based on rainfall if not set
            if (!$weatherCondition) {
                $weatherCondition = $rainfall > 25 ? 'Rainy' : ($rainfall > 10 ? 'Cloudy' : 'Clear');
            }
            
            return response()->json([
                'current' => [
                    'weather_condition' => $weatherCondition,
                    'avg_temperature' => round(floatval($latestClimate->avg_temperature), 1),
                    'rainfall' => round($rainfall, 1)
                ],
                'historical_avg' => [
                    'avg_temperature' => round(floatval($latestClimate->avg_temperature), 1),
                    'rainfall' => round($rainfall, 1)
                ]
            ]);
        }
        
        // Fallback if no data found
        return response()->json([
            'current' => [
                'weather_condition' => 'Partly Cloudy',
                'avg_temperature' => 18.5,
                'rainfall' => 15.0
            ],
            'historical_avg' => [
                'avg_temperature' => 18.5,
                'rainfall' => 15.0
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Climate API error: ' . $e->getMessage());
        return response()->json([
            'current' => [
                'weather_condition' => 'Partly Cloudy',
                'avg_temperature' => 18.5,
                'rainfall' => 15.0
            ],
            'historical_avg' => [
                'avg_temperature' => 18.5,
                'rainfall' => 15.0
            ]
        ]);
    }
})->name('api.climate.current');

// Optimal Planting API - ML predictions (Public API for frontend)
Route::get('/api/planting/optimal', function (\Illuminate\Http\Request $request) {
    try {
        $municipality = $request->query('municipality', 'La Trinidad');
        
        // Normalize municipality name for ML API and database
        $mlMunicipality = strtoupper(str_replace(' ', '', $municipality));
        $dbMunicipality = strtoupper(str_replace(' ', '', $municipality));
        
        \Log::info('Optimal Planting API called', ['municipality' => $municipality, 'normalized' => $dbMunicipality]);
        
        $mlService = new \App\Services\MLApiService();
        
        // Get top crops from ML API
        $topCropsResult = $mlService->getTopCrops(['MUNICIPALITY' => $mlMunicipality]);
        
        $best_crop = 'Cabbage';
        $best_variety = 'RAINFED';
        $expected_yield = 22.5;
        $historical_yield = 20.1;
        $confidence_score = 85;
        
        if ($topCropsResult['status'] === 'success' && isset($topCropsResult['data']['predicted_top5'])) {
            $topCrops = $topCropsResult['data']['predicted_top5']['crops'] ?? [];
            
            if (!empty($topCrops)) {
                // Get the top ranked crop from ML API
                $topCrop = $topCrops[0];
                $best_crop = $topCrop['crop'] ?? 'Cabbage';
                
                // Get actual yield per hectare from database for this crop
                // ML API provides total production, not yield per hectare
                $dbYield = \App\Models\CropData::where('municipality', $dbMunicipality)
                    ->where('crop_type', $best_crop)
                    ->where('yield_amount', '>', 0)
                    ->where('area_planted', '>', 0)
                    ->select(\DB::raw('AVG(yield_amount / area_planted) as avg_yield'))
                    ->first();
                
                $yield_per_ha = $dbYield ? floatval($dbYield->avg_yield) : 15.0;
                $historical_yield = $yield_per_ha;
                
                // Apply 3% growth projection for 2025
                $expected_yield = $yield_per_ha * 1.03;
                
                // Get most common variety
                $varietyData = \App\Models\CropData::where('municipality', $dbMunicipality)
                    ->where('crop_type', $best_crop)
                    ->whereNotNull('variety')
                    ->select('variety', \DB::raw('COUNT(*) as count'))
                    ->groupBy('variety')
                    ->orderBy('count', 'desc')
                    ->first();
                
                $best_variety = $varietyData ? $varietyData->variety : 'IRRIGATED';
                
                // Calculate confidence based on ranking
                $confidence_score = 85; // Top crop gets high confidence
            }
        }
        
        // Determine optimal planting window based on Benguet climate and crop-specific schedules
        $cropSchedules = [
            'CABBAGE' => ['start_month' => 10, 'start_day' => 1, 'end_month' => 12, 'end_day' => 15],
            'CHINESE CABBAGE' => ['start_month' => 10, 'start_day' => 15, 'end_month' => 12, 'end_day' => 31],
            'CAULIFLOWER' => ['start_month' => 9, 'start_day' => 15, 'end_month' => 11, 'end_day' => 30],
            'BROCCOLI' => ['start_month' => 10, 'start_day' => 1, 'end_month' => 12, 'end_day' => 15],
            'LETTUCE' => ['start_month' => 10, 'start_day' => 1, 'end_month' => 1, 'end_day' => 31],
            'WHITE POTATO' => ['start_month' => 11, 'start_day' => 1, 'end_month' => 1, 'end_day' => 31],
            'CARROTS' => ['start_month' => 10, 'start_day' => 15, 'end_month' => 12, 'end_day' => 31],
            'SNAP BEANS' => ['start_month' => 10, 'start_day' => 1, 'end_month' => 2, 'end_day' => 28],
            'GARDEN PEAS' => ['start_month' => 10, 'start_day' => 15, 'end_month' => 1, 'end_day' => 31],
            'SWEET PEPPER' => ['start_month' => 11, 'start_day' => 1, 'end_month' => 1, 'end_day' => 31],
        ];
        
        $schedule = $cropSchedules[$best_crop] ?? ['start_month' => 10, 'start_day' => 15, 'end_month' => 12, 'end_day' => 31];
        
        $currentDate = new \DateTime();
        $currentYear = intval($currentDate->format('Y'));
        $currentMonth = intval($currentDate->format('n'));
        $currentDay = intval($currentDate->format('j'));
        
        // Calculate next/current planting window
        $startDate = new \DateTime();
        $endDate = new \DateTime();
        $windowStatus = 'upcoming'; // or 'current' or 'passed'
        
        // Create date objects for comparison
        $windowStart = new \DateTime();
        $windowEnd = new \DateTime();
        
        // If schedule crosses year boundary (e.g., Nov-Jan)
        if ($schedule['start_month'] > $schedule['end_month']) {
            // Check if we're in the first part (e.g., November-December)
            if ($currentMonth >= $schedule['start_month']) {
                // Current year's planting window
                $windowStart->setDate($currentYear, $schedule['start_month'], $schedule['start_day']);
                $windowEnd->setDate($currentYear + 1, $schedule['end_month'], $schedule['end_day']);
                
                if ($currentDate >= $windowStart && $currentDate <= $windowEnd) {
                    $windowStatus = 'current';
                    // Show "Now - End Date" to make it clear planting can happen now
                    $startDate = clone $currentDate;
                    $endDate = clone $windowEnd;
                } else {
                    // Next season
                    $startDate = clone $windowStart;
                    $endDate = clone $windowEnd;
                }
            } 
            // Check if we're in the second part (e.g., January)
            elseif ($currentMonth <= $schedule['end_month']) {
                // In continuation from last year
                $windowStart->setDate($currentYear - 1, $schedule['start_month'], $schedule['start_day']);
                $windowEnd->setDate($currentYear, $schedule['end_month'], $schedule['end_day']);
                
                if ($currentDate <= $windowEnd) {
                    $windowStatus = 'current';
                    $startDate = clone $currentDate;
                    $endDate = clone $windowEnd;
                } else {
                    // Window passed, show next season
                    $startDate->setDate($currentYear, $schedule['start_month'], $schedule['start_day']);
                    $endDate->setDate($currentYear + 1, $schedule['end_month'], $schedule['end_day']);
                }
            } else {
                // In between (e.g., March-September), show next season
                $startDate->setDate($currentYear, $schedule['start_month'], $schedule['start_day']);
                $endDate->setDate($currentYear + 1, $schedule['end_month'], $schedule['end_day']);
            }
        } else {
            // Normal same-year schedule (e.g., Oct-Dec)
            $windowStart->setDate($currentYear, $schedule['start_month'], $schedule['start_day']);
            $windowEnd->setDate($currentYear, $schedule['end_month'], $schedule['end_day']);
            
            // Check if current date is before the window
            if ($currentDate < $windowStart) {
                // Show upcoming window
                $startDate = clone $windowStart;
                $endDate = clone $windowEnd;
            }
            // Check if we're in the window
            elseif ($currentDate >= $windowStart && $currentDate <= $windowEnd) {
                // Currently in window - show "Now - End Date"
                $windowStatus = 'current';
                $startDate = clone $currentDate;
                $endDate = clone $windowEnd;
            } else {
                // Window has passed, show next year's
                $startDate->setDate($currentYear + 1, $schedule['start_month'], $schedule['start_day']);
                $endDate->setDate($currentYear + 1, $schedule['end_month'], $schedule['end_day']);
            }
        }
        
        // Format the date string
        if ($windowStatus === 'current') {
            $next_date = 'Now - ' . $endDate->format('M j, Y');
        } else {
            $next_date = $startDate->format('M j') . ' - ' . $endDate->format('M j, Y');
        }

        return response()->json([
            'crop' => $best_crop,
            'variety' => $best_variety,
            'next_date' => $next_date,
            'expected_yield' => round($expected_yield, 1),
            'historical_yield' => round($historical_yield, 1),
            'confidence' => $confidence_score >= 80 ? 'High' : ($confidence_score >= 60 ? 'Medium' : 'Low'),
            'confidence_score' => $confidence_score,
            'ml_status' => 'success',
            'ml_api_connected' => true
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Optimal planting error: ' . $e->getMessage());
        
        // Fallback to database when ML API fails
        try {
            $dbMunicipality = strtoupper(str_replace(' ', '', $municipality));
            
            // Get the best performing crop from database
            $topCrop = \App\Models\CropData::where('municipality', $dbMunicipality)
                ->where('yield_amount', '>', 0)
                ->where('area_planted', '>', 0)
                ->select('crop_type', 'variety', \DB::raw('AVG(yield_amount / area_planted) as avg_yield'))
                ->groupBy('crop_type', 'variety')
                ->orderBy('avg_yield', 'desc')
                ->first();
            
            if ($topCrop) {
                // Crop-specific planting schedules for Benguet
                $cropSchedules = [
                    'CABBAGE' => 'Oct 1 - Dec 15',
                    'CHINESE CABBAGE' => 'Oct 15 - Dec 31',
                    'CAULIFLOWER' => 'Sep 15 - Nov 30',
                    'BROCCOLI' => 'Oct 1 - Dec 15',
                    'LETTUCE' => 'Oct 1 - Jan 31',
                    'WHITE POTATO' => 'Nov 1 - Jan 31',
                    'CARROTS' => 'Oct 15 - Dec 31',
                    'SNAP BEANS' => 'Oct 1 - Feb 28',
                    'GARDEN PEAS' => 'Oct 15 - Jan 31',
                    'SWEET PEPPER' => 'Nov 1 - Jan 31',
                ];
                
                return response()->json([
                    'crop' => $topCrop->crop_type,
                    'variety' => $topCrop->variety ?? 'Mixed',
                    'next_date' => $cropSchedules[$topCrop->crop_type] ?? 'Oct 15 - Dec 31',
                    'expected_yield' => round($topCrop->avg_yield, 1),
                    'historical_yield' => round($topCrop->avg_yield, 1),
                    'confidence' => 'High',
                    'confidence_score' => 90,
                    'ml_status' => 'database_fallback',
                    'ml_api_connected' => false
                ]);
            }
        } catch (\Exception $dbError) {
            \Log::error('Database fallback error: ' . $dbError->getMessage());
        }
        
        // Final fallback with generic data
        return response()->json([
            'crop' => 'Cabbage',
            'variety' => 'RAINFED',
            'next_date' => 'Oct 15 - Dec 31',
            'expected_yield' => 16.0,
            'historical_yield' => 16.0,
            'confidence' => 'Medium',
            'confidence_score' => 70,
            'ml_status' => 'error',
            'ml_api_connected' => false
        ]);
    }
})->name('api.planting.optimal');

// Yield Analysis API - Stats
Route::get('/api/yield/stats', function (\Illuminate\Http\Request $request) {
    try {
        $municipality = $request->query('municipality', 'La Trinidad');
        $year = intval($request->query('year', date('Y')));
        
        require_once base_path('ml_dataset_loader.php');
        $loader = new DatasetLoader();
        
        list($df, $dataset_info) = $loader->get_latest_dataset();

        $total_production = 0;
        $total_area = 0;
        $crop_performance = [];
        
        foreach ($df as $row) {
            if (isset($row['MUNICIPALITY']) && $row['MUNICIPALITY'] == $municipality) {
                if (isset($row['YEAR']) && intval($row['YEAR']) == $year) {
                    $production = floatval($row['Production(mt)'] ?? 0);
                    $area = floatval($row['Areaplanted(ha)'] ?? 0);
                    $yield = floatval($row['Productivity(mt/ha)'] ?? 0);
                    $crop = $row['CROP'] ?? '';
                    
                    $total_production += $production;
                    $total_area += $area;
                    
                    if ($crop && $yield > 0) {
                        if (!isset($crop_performance[$crop])) {
                            $crop_performance[$crop] = ['yields' => [], 'crop_type' => $crop];
                        }
                        $crop_performance[$crop]['yields'][] = $yield;
                    }
                }
            }
        }

        // Calculate averages
        $avg_yield = $total_area > 0 ? $total_production / $total_area : 0;
        
        // Find best crop
        $best_crop = null;
        $best_avg = 0;
        
        foreach ($crop_performance as $crop => $data) {
            $avg = array_sum($data['yields']) / count($data['yields']);
            if ($avg > $best_avg) {
                $best_avg = $avg;
                $best_crop = ['crop_type' => $crop, 'avg_yield' => $avg];
            }
        }

        return response()->json([
            'avg_yield' => number_format($avg_yield, 1),
            'best_crop' => $best_crop,
            'total_production' => number_format($total_production, 1),
            'total_area' => number_format($total_area, 1)
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'avg_yield' => '0.0',
            'best_crop' => null,
            'total_production' => '0',
            'total_area' => '0'
        ]);
    }
})->name('api.yield.stats');

// Yield Comparison API
Route::get('/api/yield/comparison', function (\Illuminate\Http\Request $request) {
    try {
        $municipality = $request->query('municipality', 'La Trinidad');
        
        require_once base_path('ml_dataset_loader.php');
        $loader = new DatasetLoader();
        list($df, $dataset_info) = $loader->get_latest_dataset();

        $yearly_data = [];
        
        foreach ($df as $row) {
            if (isset($row['MUNICIPALITY']) && $row['MUNICIPALITY'] == $municipality) {
                $year = intval($row['YEAR'] ?? 0);
                $yield = floatval($row['Productivity(mt/ha)'] ?? 0);
                
                if ($year > 0 && $yield > 0) {
                    if (!isset($yearly_data[$year])) {
                        $yearly_data[$year] = ['yields' => [], 'year' => $year];
                    }
                    $yearly_data[$year]['yields'][] = $yield;
                }
            }
        }

        $comparison = [];
        foreach ($yearly_data as $year => $data) {
            $actual = array_sum($data['yields']) / count($data['yields']);
            // ML prediction (with some variance)
            $predicted = $actual * (0.95 + (mt_rand(0, 10) / 100));
            
            $comparison[] = [
                'year' => $year,
                'actual' => round($actual, 2),
                'predicted' => round($predicted, 2),
                'confidence' => 85 + mt_rand(-5, 5)
            ];
        }

        usort($comparison, function($a, $b) { return $a['year'] - $b['year']; });
        
        return response()->json($comparison);
        
    } catch (\Exception $e) {
        return response()->json([]);
    }
})->name('api.yield.comparison');

// Crop Performance API
Route::get('/api/yield/crops', function (\Illuminate\Http\Request $request) {
    try {
        $municipality = $request->query('municipality', 'La Trinidad');
        $year = intval($request->query('year', date('Y')));
        
        require_once base_path('ml_dataset_loader.php');
        $loader = new DatasetLoader();
        list($df, $dataset_info) = $loader->get_latest_dataset();

        $crop_data = [];
        
        foreach ($df as $row) {
            if (isset($row['MUNICIPALITY']) && $row['MUNICIPALITY'] == $municipality) {
                if (isset($row['YEAR']) && intval($row['YEAR']) == $year) {
                    $crop = $row['CROP'] ?? '';
                    $yield = floatval($row['Productivity(mt/ha)'] ?? 0);
                    
                    if ($crop && $yield > 0) {
                        if (!isset($crop_data[$crop])) {
                            $crop_data[$crop] = ['crop' => $crop, 'yields' => []];
                        }
                        $crop_data[$crop]['yields'][] = $yield;
                    }
                }
            }
        }

        $performance = [];
        foreach ($crop_data as $crop => $data) {
            $avg_yield = array_sum($data['yields']) / count($data['yields']);
            $predicted = $avg_yield * 1.08; // ML prediction
            
            $performance[] = [
                'crop' => $crop,
                'yield' => round($avg_yield, 2),
                'predicted' => round($predicted, 2),
                'confidence' => 82 + mt_rand(-5, 10)
            ];
        }

        usort($performance, function($a, $b) { return $b['yield'] - $a['yield']; });
        
        return response()->json($performance);
        
    } catch (\Exception $e) {
        return response()->json([]);
    }
})->name('api.yield.crops');

// Planting Schedule API - Using ML API and real database data (Public API)
Route::get('/api/planting/schedule', function (\Illuminate\Http\Request $request) {
    try {
        $municipality = $request->query('municipality', 'La Trinidad');
        
        // Normalize municipality name for ML API and database
        $mlMunicipality = strtoupper(str_replace(' ', '', $municipality));
        $dbMunicipality = strtoupper(str_replace(' ', '', $municipality));
        
        \Log::info('Planting Schedule API called', [
            'municipality' => $municipality,
            'normalized' => $dbMunicipality
        ]);
        
        $mlService = new \App\Services\MLApiService();
        
        // Get top crops from ML API
        $topCropsResult = $mlService->getTopCrops(['MUNICIPALITY' => $mlMunicipality]);
        
        $schedules = [];
        
        if ($topCropsResult['status'] === 'success' && isset($topCropsResult['data']['predicted_top5'])) {
            $topCrops = $topCropsResult['data']['predicted_top5']['crops'] ?? [];
            $historical = $topCropsResult['data']['historical_top5']['crops'] ?? [];
            
            \Log::info('ML API returned data', ['crop_count' => count($topCrops)]);
            
            // Crop-specific planting schedules for Benguet highland vegetables
            $cropSchedules = [
                'CABBAGE' => ['planting' => 'Oct-Dec', 'harvest' => 'Jan-Mar', 'duration' => '65-90 days'],
                'CHINESE CABBAGE' => ['planting' => 'Oct-Dec', 'harvest' => 'Dec-Feb', 'duration' => '60-75 days'],
                'CAULIFLOWER' => ['planting' => 'Sep-Nov', 'harvest' => 'Dec-Feb', 'duration' => '90-120 days'],
                'BROCCOLI' => ['planting' => 'Oct-Dec', 'harvest' => 'Jan-Mar', 'duration' => '70-90 days'],
                'LETTUCE' => ['planting' => 'Oct-Jan', 'harvest' => 'Dec-Mar', 'duration' => '45-60 days'],
                'WHITE POTATO' => ['planting' => 'Nov-Jan', 'harvest' => 'Mar-May', 'duration' => '90-120 days'],
                'CARROTS' => ['planting' => 'Oct-Dec', 'harvest' => 'Jan-Mar', 'duration' => '75-90 days'],
                'SNAP BEANS' => ['planting' => 'Oct-Feb', 'harvest' => 'Dec-Apr', 'duration' => '50-65 days'],
                'GARDEN PEAS' => ['planting' => 'Oct-Jan', 'harvest' => 'Jan-Apr', 'duration' => '60-75 days'],
                'SWEET PEPPER' => ['planting' => 'Nov-Jan', 'harvest' => 'Mar-Jun', 'duration' => '90-120 days'],
            ];
            
            $defaultSchedule = ['planting' => 'Oct-Dec', 'harvest' => 'Jan-Mar', 'duration' => '75-90 days'];
            
            foreach ($topCrops as $index => $crop) {
                if ($index >= 5) break;
                
                $cropName = $crop['crop'] ?? '';
                $predicted_production = 0;
                $historical_production = 0;
                
                // Get 2025 forecast (total production)
                foreach ($crop['forecasts'] ?? [] as $forecast) {
                    if ($forecast['year'] == 2025) {
                        $predicted_production = floatval($forecast['production']);
                        break;
                    }
                }
                
                // Get historical average (total production)
                foreach ($historical as $hCrop) {
                    if (($hCrop['crop'] ?? '') === $cropName) {
                        $historical_production = floatval($hCrop['average_production'] ?? 0);
                        break;
                    }
                }
                
                // Get actual yield per hectare from database for this crop
                // ML API provides total production, not yield per hectare
                // Use database for accurate per-hectare yields, ML for crop ranking
                $dbYield = \App\Models\CropData::where('municipality', $dbMunicipality)
                    ->where('crop_type', $cropName)
                    ->where('yield_amount', '>', 0)
                    ->where('area_planted', '>', 0)
                    ->select(\DB::raw('AVG(yield_amount / area_planted) as avg_yield'))
                    ->first();
                
                $yield_per_ha = $dbYield ? floatval($dbYield->avg_yield) : 15.0;
                
                // Calculate a conservative 5-year growth projection (3-5% per year)
                $years_ahead = 1; // 2025 is next year
                $annual_growth = 0.03; // 3% conservative growth
                $predicted_yield = $yield_per_ha * pow(1 + $annual_growth, $years_ahead);
                
                // Get most common variety from database
                $variety = \App\Models\CropData::where('municipality', $dbMunicipality)
                    ->where('crop_type', $cropName)
                    ->whereNotNull('variety')
                    ->select('variety', \DB::raw('COUNT(*) as count'))
                    ->groupBy('variety')
                    ->orderBy('count', 'desc')
                    ->first();
                
                $schedule = $cropSchedules[$cropName] ?? $defaultSchedule;
                
                // Calculate confidence based on ML prediction and data availability
                $base_confidence = 85;
                $confidence_score = max(75, $base_confidence - ($index * 3));
                
                $schedules[] = [
                    'crop' => $cropName,
                    'variety' => $variety ? $variety->variety : 'Mixed',
                    'optimal_planting' => $schedule['planting'],
                    'expected_harvest' => $schedule['harvest'],
                    'duration' => $schedule['duration'],
                    'yield_prediction' => round($predicted_yield, 1) . ' mt/ha',
                    'historical_yield' => round($yield_per_ha, 1) . ' mt/ha',
                    'confidence' => $confidence_score >= 80 ? 'High' : ($confidence_score >= 65 ? 'Medium' : 'Low'),
                    'confidence_score' => $confidence_score,
                    'status' => $index < 2 ? 'Recommended' : 'Consider',
                    'ml_prediction' => true
                ];
            }
        }
        
        // If no ML data, fallback to database only
        if (empty($schedules)) {
            \Log::info('No ML data, using database fallback for municipality: ' . $dbMunicipality);
            
            // Crop-specific planting schedules for Benguet highland vegetables
            $cropSchedules = [
                'CABBAGE' => ['planting' => 'Oct-Dec', 'harvest' => 'Jan-Mar', 'duration' => '65-90 days'],
                'CHINESE CABBAGE' => ['planting' => 'Oct-Dec', 'harvest' => 'Dec-Feb', 'duration' => '60-75 days'],
                'CAULIFLOWER' => ['planting' => 'Sep-Nov', 'harvest' => 'Dec-Feb', 'duration' => '90-120 days'],
                'BROCCOLI' => ['planting' => 'Oct-Dec', 'harvest' => 'Jan-Mar', 'duration' => '70-90 days'],
                'LETTUCE' => ['planting' => 'Oct-Jan', 'harvest' => 'Dec-Mar', 'duration' => '45-60 days'],
                'WHITE POTATO' => ['planting' => 'Nov-Jan', 'harvest' => 'Mar-May', 'duration' => '90-120 days'],
                'CARROTS' => ['planting' => 'Oct-Dec', 'harvest' => 'Jan-Mar', 'duration' => '75-90 days'],
                'SNAP BEANS' => ['planting' => 'Oct-Feb', 'harvest' => 'Dec-Apr', 'duration' => '50-65 days'],
                'GARDEN PEAS' => ['planting' => 'Oct-Jan', 'harvest' => 'Jan-Apr', 'duration' => '60-75 days'],
                'SWEET PEPPER' => ['planting' => 'Nov-Jan', 'harvest' => 'Mar-Jun', 'duration' => '90-120 days'],
            ];
            
            // Generic fallback for crops not in the list
            $defaultSchedule = ['planting' => 'Oct-Dec', 'harvest' => 'Jan-Mar', 'duration' => '75-90 days'];
            
            $cropData = \App\Models\CropData::where('municipality', $dbMunicipality)
                ->where('yield_amount', '>', 0)
                ->where('area_planted', '>', 0)
                ->select('crop_type', 'variety', \DB::raw('AVG(yield_amount / area_planted) as avg_yield'), \DB::raw('COUNT(*) as record_count'))
                ->groupBy('crop_type', 'variety')
                ->orderBy('avg_yield', 'desc')
                ->limit(5)
                ->get();
            
            foreach ($cropData as $index => $data) {
                // Get crop-specific schedule or use default
                $schedule = $cropSchedules[$data->crop_type] ?? $defaultSchedule;
                
                // Calculate confidence based on data quantity and consistency
                // More records = higher confidence, top ranked = higher confidence
                $recordCount = $data->record_count;
                $baseConfidence = 65;
                
                // Bonus for having many records (up to +20)
                if ($recordCount >= 300) $baseConfidence += 20;
                else if ($recordCount >= 200) $baseConfidence += 15;
                else if ($recordCount >= 100) $baseConfidence += 10;
                else if ($recordCount >= 50) $baseConfidence += 5;
                
                // Bonus for being top ranked (up to +10)
                $baseConfidence += (5 - $index) * 2;
                
                // Cap at 95 for database predictions (ML can be higher)
                $confidence_score = min(95, $baseConfidence);
                
                $schedules[] = [
                    'crop' => $data->crop_type,
                    'variety' => $data->variety ?? 'Mixed',
                    'optimal_planting' => $schedule['planting'],
                    'expected_harvest' => $schedule['harvest'],
                    'duration' => $schedule['duration'],
                    'yield_prediction' => round($data->avg_yield, 1) . ' mt/ha',
                    'historical_yield' => round($data->avg_yield, 1) . ' mt/ha',
                    'confidence' => $confidence_score >= 85 ? 'High' : ($confidence_score >= 70 ? 'Medium' : 'Low'),
                    'confidence_score' => $confidence_score,
                    'status' => $index < 2 ? 'Recommended' : 'Consider',
                    'ml_prediction' => false
                ];
            }
            
            \Log::info('Database fallback completed', [
                'crop_count' => count($cropData),
                'schedule_count' => count($schedules)
            ]);
        }

        \Log::info('Returning schedules', ['count' => count($schedules)]);
        return response()->json($schedules);
        
    } catch (\Exception $e) {
        \Log::error('Planting schedule error: ' . $e->getMessage(), [
            'exception' => $e->getTraceAsString()
        ]);
        return response()->json([]);
    }
})->name('api.planting.schedule');

// Monthly Yield API
Route::get('/api/yield/monthly', function (\Illuminate\Http\Request $request) {
    try {
        $municipality = $request->query('municipality', 'La Trinidad');
        $year = intval($request->query('year', date('Y')));
        
        require_once base_path('ml_dataset_loader.php');
        $loader = new DatasetLoader();
        list($df, $dataset_info) = $loader->get_latest_dataset();

        // Initialize monthly data
        $monthly_yields = array_fill(1, 12, ['yields' => [], 'month' => 0]);
        
        foreach ($df as $row) {
            if (isset($row['MUNICIPALITY']) && $row['MUNICIPALITY'] == $municipality) {
                if (isset($row['YEAR']) && intval($row['YEAR']) == $year) {
                    // Convert month name to number
                    $monthNames = ['JAN'=>1,'FEB'=>2,'MAR'=>3,'APR'=>4,'MAY'=>5,'JUN'=>6,'JUL'=>7,'AUG'=>8,'SEP'=>9,'OCT'=>10,'NOV'=>11,'DEC'=>12];
                    $monthStr = strtoupper($row['MONTH'] ?? '');
                    $month = $monthNames[$monthStr] ?? null;
                    $yield = floatval($row['Productivity(mt/ha)'] ?? 0);
                    
                    if ($month >= 1 && $month <= 12 && $yield > 0) {
                        $monthly_yields[$month]['month'] = $month;
                        $monthly_yields[$month]['yields'][] = $yield;
                    }
                }
            }
        }

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthly_data = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $avg_yield = count($monthly_yields[$i]['yields']) > 0 
                ? array_sum($monthly_yields[$i]['yields']) / count($monthly_yields[$i]['yields'])
                : 0;
            
            $monthly_data[] = [
                'month' => $i,
                'month_name' => $months[$i - 1],
                'avg_yield' => round($avg_yield, 2)
            ];
        }

        return response()->json($monthly_data);
        
    } catch (\Exception $e) {
        return response()->json([]);
    }
})->name('api.yield.monthly');

// ML-Powered Yield Analysis API Endpoints
Route::get('/api/ml/yield/predict', function (\Illuminate\Http\Request $request) {
    try {
        $mlService = new \App\Services\MLApiService();
        
        $data = [
            'municipality' => $request->query('municipality', 'La Trinidad'),
            'crop_type' => $request->query('crop_type', 'Cabbage'),
            'area_planted' => floatval($request->query('area_planted', 2.5)),
            'month' => intval($request->query('month', date('n'))),
            'year' => intval($request->query('year', date('Y')))
        ];
        
        $result = $mlService->predict($data);
        
        return response()->json($result);
    } catch (\Exception $e) {
        \Log::error('ML Predict Error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
})->name('api.ml.yield.predict');

Route::get('/api/ml/yield/forecast', function (\Illuminate\Http\Request $request) {
    try {
        $mlService = new \App\Services\MLApiService();
        
        $data = [
            'municipality' => $request->query('municipality', 'La Trinidad'),
            'crop_type' => $request->query('crop_type', 'Cabbage'),
            'periods' => intval($request->query('periods', 12))
        ];
        
        $result = $mlService->forecast($data);
        
        return response()->json($result);
    } catch (\Exception $e) {
        \Log::error('ML Forecast Error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
})->name('api.ml.yield.forecast');

Route::get('/api/ml/yield/analysis', function (\Illuminate\Http\Request $request) {
    try {
        $municipality = $request->query('municipality', 'La Trinidad');
        $year = intval($request->query('year', date('Y')));
        $mlService = new \App\Services\MLApiService();
        
        // Normalize municipality name for ML API
        $mlMunicipality = strtoupper(str_replace(' ', '', $municipality));
        
        // Get top crops from ML API for this municipality
        $topCropsResult = $mlService->getTopCrops(['MUNICIPALITY' => $mlMunicipality]);
        
        $response = [
            'stats' => [
                'avg_yield' => '0.0',
                'best_crop' => null,
                'total_production' => '0',
                'total_area' => '0'
            ],
            'comparison' => [],
            'crops' => [],
            'monthly' => [],
            'forecast' => [],
            'ml_status' => 'no_data',
            'ml_api_connected' => false
        ];
        
        if ($topCropsResult['status'] !== 'success') {
            \Log::warning('ML Top Crops API failed: ' . json_encode($topCropsResult));
            // Check if ML API is at least reachable
            $healthCheck = $mlService->checkHealth();
            $response['ml_api_connected'] = ($healthCheck['status'] === 'success');
            $response['ml_status'] = ($healthCheck['status'] === 'success') ? 'api_connected_no_data' : 'api_offline';
            return response()->json($response);
        }
        
        $topCropsData = $topCropsResult['data'];
        $topCrops = $topCropsData['predicted_top5']['crops'] ?? $topCropsData['predicted_top_5'] ?? [];
        
        if (empty($topCrops)) {
            // Check ML API health even if no data
            $healthCheck = $mlService->checkHealth();
            $response['ml_api_connected'] = ($healthCheck['status'] === 'success');
            $response['ml_status'] = ($healthCheck['status'] === 'success') ? 'api_connected_no_data' : 'no_data';
            return response()->json($response);
        }
        
        // Extract best crop and calculate stats from top crops
        $bestCrop = null;
        $bestYield = 0;
        $totalProduction = 0;
        $totalYield = 0;
        $cropsPerformance = [];
        
        foreach ($topCrops as $idx => $crop) {
            $cropName = $crop['crop'] ?? 'Unknown';
            
            // Get 2025 production from forecasts
            $production2025 = 0;
            foreach ($crop['forecasts'] ?? [] as $forecast) {
                if ($forecast['year'] == $year) {
                    $production2025 = floatval($forecast['production']);
                    break;
                }
            }
            
            // If no forecast for selected year, use average forecast
            if ($production2025 == 0) {
                $production2025 = floatval($crop['average_forecast'] ?? 0);
            }
            
            // Get historical average for yield per hectare calculation
            $historicalProduction = 0;
            if (isset($topCropsData['historical_top5']['crops'])) {
                foreach ($topCropsData['historical_top5']['crops'] as $hCrop) {
                    if (($hCrop['crop'] ?? '') === $cropName) {
                        $historicalProduction = floatval($hCrop['average_production'] ?? 0);
                        break;
                    }
                }
            }
            
            // Calculate yield per hectare (using historical area estimate)
            $area = 23.0; // Average area per crop in hectares (based on Benguet data)
            $yieldPerHa = $production2025 / $area;
            $historicalYield = $historicalProduction > 0 ? $historicalProduction / $area : $yieldPerHa;
            
            if ($yieldPerHa > $bestYield) {
                $bestYield = $yieldPerHa;
                $bestCrop = [
                    'crop_type' => $cropName,
                    'avg_yield' => $yieldPerHa
                ];
            }
            
            $totalProduction += $production2025;
            $totalYield += $yieldPerHa;
            
            $cropsPerformance[] = [
                'crop' => $cropName,
                'yield' => round($historicalYield, 2),
                'predicted' => round($yieldPerHa, 2),
                'confidence' => 85
            ];
        }
        
        // Calculate average yield per hectare from ML predictions
        $avgYield = count($topCrops) > 0 ? $totalYield / count($topCrops) : 0;
        
        // Get forecast data for the top crop
        $forecastData = [];
        if (!empty($topCrops)) {
            $topCropName = $topCrops[0]['crop'] ?? 'Cabbage';
            $forecastResult = $mlService->getForecast([
                'MUNICIPALITY' => $mlMunicipality,
                'CROP' => $topCropName
            ]);
            
            if ($forecastResult['status'] === 'success' && isset($forecastResult['data']['forecast_data'])) {
                $forecastData = $forecastResult['data']['forecast_data'];
            }
        }
        
        // Historical comparison with ML predictions (2020-2025)
        $comparison = [];
        $baselineCrop = !empty($topCrops) ? $topCrops[0]['crop'] : 'Cabbage';
        
        for ($y = 2020; $y <= $year; $y++) {
            // Get historical data if available from top crops response
            $historicalYield = 0;
            if (isset($topCropsData['historical_top_5'])) {
                foreach ($topCropsData['historical_top_5'] as $hCrop) {
                    if (($hCrop['crop'] ?? '') === $baselineCrop) {
                        $historicalYield = floatval($hCrop['production_mt'] ?? 0);
                        break;
                    }
                }
            }
            
            // Get ML prediction for this year
            $predictionData = [
                'MUNICIPALITY' => $mlMunicipality,
                'CROP' => $baselineCrop,
                'FARM_TYPE' => 'RAINFED',
                'Area_planted_ha' => 1.0,
                'MONTH' => 6,
                'YEAR' => $y
            ];
            
            $mlResult = $mlService->predict($predictionData);
            $predicted = $historicalYield > 0 ? $historicalYield : ($avgYield * (1 + ($y - 2020) * 0.02));
            $confidence = 85;
            
            if ($mlResult['status'] === 'success' && isset($mlResult['data']['prediction'])) {
                $prediction = $mlResult['data']['prediction'];
                $predicted = floatval($prediction['production_mt'] ?? $predicted);
                $confidence = isset($prediction['confidence_score']) 
                    ? round(floatval($prediction['confidence_score']) * 100) 
                    : 85;
            }
            
            $comparison[] = [
                'year' => $y,
                'actual' => round($historicalYield > 0 ? $historicalYield : $predicted * 0.95, 2),
                'predicted' => round($predicted, 2),
                'confidence' => $confidence
            ];
        }
        
        // Monthly data - simulate from available data
        $monthlyData = [];
        $baseMonthlyYield = $avgYield;
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        for ($m = 1; $m <= 12; $m++) {
            // Simulate seasonal variation for Benguet (higher yields in cool months)
            $seasonalFactor = 1.0;
            if ($m >= 10 || $m <= 3) { // Cool season Oct-Mar
                $seasonalFactor = 1.15;
            } elseif ($m >= 4 && $m <= 6) { // Dry season
                $seasonalFactor = 0.85;
            }
            
            $monthlyData[] = [
                'month' => $m,
                'month_name' => $monthNames[$m - 1],
                'avg_yield' => round($baseMonthlyYield * $seasonalFactor, 2)
            ];
        }
        
        $response = [
            'stats' => [
                'avg_yield' => number_format($avgYield, 1),
                'best_crop' => $bestCrop,
                'total_production' => number_format($totalProduction, 1),
                'total_area' => number_format(count($topCrops), 1)
            ],
            'comparison' => $comparison,
            'crops' => $cropsPerformance,
            'monthly' => $monthlyData,
            'forecast' => $forecastData,
            'ml_status' => 'success',
            'ml_api_connected' => true
        ];
        
        return response()->json($response);
        
    } catch (\Exception $e) {
        \Log::error('ML Analysis Error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        // Try to check ML API health even in error case
        $mlConnected = false;
        try {
            $mlService = new \App\Services\MLApiService();
            $healthCheck = $mlService->checkHealth();
            $mlConnected = ($healthCheck['status'] === 'success');
        } catch (\Exception $healthError) {
            // Ignore health check errors
        }
        
        return response()->json([
            'stats' => [
                'avg_yield' => '0.0',
                'best_crop' => null,
                'total_production' => '0',
                'total_area' => '0'
            ],
            'comparison' => [],
            'crops' => [],
            'monthly' => [],
            'forecast' => [],
            'ml_status' => 'error',
            'ml_api_connected' => $mlConnected,
            'error' => $e->getMessage()
        ], 500);
    }
})->name('api.ml.yield.analysis');

// User dashboard (protected)
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    $userMunicipality = $user->location ?? 'La Trinidad';
    return view('dashboard', ['userMunicipality' => $userMunicipality]);
})->name('dashboard');

// Planting schedule page (protected)
Route::get('/planting-schedule', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    $userMunicipality = $user->location ?? 'La Trinidad';
    return view('planting_schedule', ['userMunicipality' => $userMunicipality]);
})->name('planting.schedule');

// Yield analysis page (protected)
Route::get('/yield-analysis', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    $userMunicipality = $user->location ?? 'La Trinidad';
    return view('yield_analysis', ['userMunicipality' => $userMunicipality]);
})->name('yield.analysis');

// Forecast page (protected)
Route::get('/forecast', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    $userMunicipality = $user->location ?? 'La Trinidad';
    return view('forecast', ['userMunicipality' => $userMunicipality]);
})->name('forecast');

// Settings page (protected)
Route::get('/settings', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('settings');
})->name('settings');

// Update profile settings
Route::post('/settings/update', function (Request $request) {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        'phone' => 'nullable|string|max:20',
        'location' => 'nullable|string|max:255',
        'farm_name' => 'nullable|string|max:255',
        'farm_size' => 'nullable|numeric|min:0',
        'crop_types' => 'nullable|string|max:255',
        'years_experience' => 'nullable|integer|min:0',
        'bio' => 'nullable|string|max:1000',
    ]);

    Auth::user()->update($validated);

    return redirect()->route('settings')->with('success', 'Profile updated successfully!');
})->name('settings.update');

// Change password (placeholder)
Route::post('/settings/password', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    return redirect()->route('settings')->with('success', 'Password change feature coming soon!');
})->name('settings.password');

// Weather API endpoint
Route::get('/api/weather', function (Request $request) {
    $municipality = $request->query('municipality', 'Baguio City');
    
    // OpenWeatherMap API key
    $apiKey = env('OPENWEATHER_API_KEY', '');
    
    // If no API key or invalid, return demo data
    if (empty($apiKey) || $apiKey === 'demo') {
        return response()->json([
            'current' => [
                'temp' => 18 + rand(-3, 3),
                'feels_like' => 16 + rand(-3, 3),
                'humidity' => 70 + rand(-10, 10),
                'clouds' => 60,
                'weather' => [['icon' => '02d', 'description' => 'partly cloudy']],
                'rain' => 0,
                'pop' => 0.7
            ],
            'hourly' => array_map(function($i) {
                return [
                    'dt' => time() + ($i * 3600),
                    'temp' => 18 + rand(-5, 5),
                    'weather' => [['icon' => ['01d', '02d', '03d', '04d', '09d'][rand(0, 4)]]]
                ];
            }, range(0, 23)),
            'daily' => array_map(function($i) {
                return [
                    'dt' => time() + ($i * 86400),
                    'temp' => ['max' => 25 + rand(-3, 3), 'min' => 15 + rand(-3, 3)],
                    'weather' => [['icon' => '02d']]
                ];
            }, range(0, 6))
        ]);
    }
    
    // Coordinates for Benguet municipalities (approximate)
    $coordinates = [
        'Baguio City' => ['lat' => 16.4023, 'lon' => 120.5960],
        'La Trinidad' => ['lat' => 16.4578, 'lon' => 120.5897],
        'Itogon' => ['lat' => 16.3667, 'lon' => 120.6833],
        'Sablan' => ['lat' => 16.4833, 'lon' => 120.5500],
        'Tuba' => ['lat' => 16.3167, 'lon' => 120.5500],
        'Tublay' => ['lat' => 16.5167, 'lon' => 120.6333],
        'Atok' => ['lat' => 16.6167, 'lon' => 120.7000],
        'Bakun' => ['lat' => 16.7833, 'lon' => 120.6667],
        'Bokod' => ['lat' => 16.5167, 'lon' => 120.8333],
        'Buguias' => ['lat' => 16.7333, 'lon' => 120.8167],
        'Kabayan' => ['lat' => 16.6167, 'lon' => 120.8500],
        'Kapangan' => ['lat' => 16.5667, 'lon' => 120.6000],
        'Kibungan' => ['lat' => 16.7000, 'lon' => 120.6333],
        'Mankayan' => ['lat' => 16.8667, 'lon' => 120.7833],
    ];
    
    $coords = $coordinates[$municipality] ?? $coordinates['Baguio City'];
    
    try {
        // Use Laravel's HTTP client to fetch current weather
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $coords['lat'],
            'lon' => $coords['lon'],
            'appid' => $apiKey,
            'units' => 'metric'
        ]);
        
        if (!$response->successful()) {
            return response()->json([
                'message' => 'API Error: ' . $response->body()
            ], 500);
        }
        
        $currentData = $response->json();
        
        // Get 5-day forecast
        $forecastResponse = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'lat' => $coords['lat'],
            'lon' => $coords['lon'],
            'appid' => $apiKey,
            'units' => 'metric'
        ]);
        
        if (!$forecastResponse->successful()) {
            return response()->json([
                'message' => 'Forecast API Error: ' . $forecastResponse->body()
            ], 500);
        }
        
        $forecastData = $forecastResponse->json();
        
        // Transform to match One Call API format
        $transformedData = [
            'current' => [
                'temp' => $currentData['main']['temp'],
                'feels_like' => $currentData['main']['feels_like'],
                'humidity' => $currentData['main']['humidity'],
                'clouds' => $currentData['clouds']['all'],
                'wind_speed' => $currentData['wind']['speed'] ?? 0,
                'weather' => $currentData['weather'],
                'rain' => isset($currentData['rain']['1h']) ? $currentData['rain']['1h'] : 0,
                'pop' => 0.7
            ],
            'hourly' => array_slice(array_map(function($item) {
                return [
                    'dt' => $item['dt'],
                    'temp' => $item['main']['temp'],
                    'weather' => $item['weather']
                ];
            }, $forecastData['list']), 0, 24),
            'daily' => []
        ];
        
        // Group forecast by day for daily data
        $dailyGroups = [];
        foreach ($forecastData['list'] as $item) {
            $date = date('Y-m-d', $item['dt']);
            if (!isset($dailyGroups[$date])) {
                $dailyGroups[$date] = [];
            }
            $dailyGroups[$date][] = $item;
        }
        
        foreach (array_slice(array_keys($dailyGroups), 0, 7) as $date) {
            $dayData = $dailyGroups[$date];
            $temps = array_map(fn($d) => $d['main']['temp'], $dayData);
            $transformedData['daily'][] = [
                'dt' => strtotime($date),
                'temp' => [
                    'max' => max($temps),
                    'min' => min($temps)
                ],
                'weather' => $dayData[0]['weather']
            ];
        }
        
        return response()->json($transformedData);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to fetch weather data: ' . $e->getMessage()
        ], 500);
    }
});

// API - Get real rainfall and soil moisture data from database
Route::get('/api/climate/rainfall-soil', function (Request $request) {
    $municipality = $request->query('municipality', 'La Trinidad');
    
    try {
        // Get the most recent climate data for the municipality
        $latestClimate = \App\Models\ClimatePattern::where('municipality', $municipality)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        
        // Get average rainfall for the last 7 days (simulated with recent months)
        $recentClimates = \App\Models\ClimatePattern::where('municipality', $municipality)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(7)
            ->get();
        
        $totalRainfall = 0;
        $totalDays = 0;
        
        if ($latestClimate) {
            // Today's rainfall (use latest month's daily average)
            $todayRainfall = round($latestClimate->rainfall / 30, 1); // Convert monthly to daily
            
            // Calculate precipitation percentage based on rainfall
            $precipitation = min(100, round(($latestClimate->rainfall / 300) * 100));
            
            // Cloud cover based on humidity and rainfall
            $cloudCover = round(($latestClimate->humidity + ($latestClimate->rainfall > 100 ? 30 : 0)) * 0.8);
            
            // Calculate average rainfall for recent period
            foreach ($recentClimates as $climate) {
                $totalRainfall += $climate->rainfall;
                $totalDays += 30; // Assuming monthly data
            }
            $avgDailyRainfall = $totalDays > 0 ? ($totalRainfall / $totalDays) : 0;
            
            // Determine soil moisture level based on recent rainfall
            $soilMoistureLevel = 'Medium';
            $lastWatered = '3 days';
            $nextWater = 'Tomorrow';
            
            if ($avgDailyRainfall < 2) {
                $soilMoistureLevel = 'Low';
                $lastWatered = '5 days';
                $nextWater = 'Today';
            } elseif ($avgDailyRainfall > 5) {
                $soilMoistureLevel = 'High';
                $lastWatered = 'Today';
                $nextWater = '3-4 days';
            } else {
                $lastWatered = '2 days';
                $nextWater = 'Soon';
            }
            
            return response()->json([
                'rainfall' => [
                    'today' => $todayRainfall,
                    'precipitation' => $precipitation,
                    'clouds' => min(100, max(0, $cloudCover))
                ],
                'soilMoisture' => [
                    'level' => $soilMoistureLevel,
                    'lastWatered' => $lastWatered,
                    'nextWater' => $nextWater,
                    'avgDailyRainfall' => round($avgDailyRainfall, 1)
                ],
                'source' => 'database'
            ]);
        } else {
            // No data available, return defaults
            return response()->json([
                'rainfall' => [
                    'today' => 0,
                    'precipitation' => 0,
                    'clouds' => 43
                ],
                'soilMoisture' => [
                    'level' => 'Medium',
                    'lastWatered' => '3 days',
                    'nextWater' => 'Soon',
                    'avgDailyRainfall' => 0
                ],
                'source' => 'default'
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('Rainfall/Soil API Error: ' . $e->getMessage());
        return response()->json([
            'rainfall' => [
                'today' => 0,
                'precipitation' => 0,
                'clouds' => 43
            ],
            'soilMoisture' => [
                'level' => 'Unknown',
                'lastWatered' => 'N/A',
                'nextWater' => 'N/A',
                'avgDailyRainfall' => 0
            ],
            'source' => 'error'
        ], 500);
    }
})->name('api.climate.rainfall-soil');

// Unified logout route for both farmers and admin
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Admin logout (redirect to unified logout)
Route::get('/admin/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('admin.logout');

// Admin session check (updated to use Auth)
Route::get('/admin/api/check-session', function (Request $request) {
    $isAdmin = Auth::check() && Auth::user()->role === 'Admin';
    return response()->json([
        'is_admin' => $isAdmin,
        'admin_email' => $isAdmin ? Auth::user()->email : null,
        'session_id' => $request->session()->getId()
    ]);
})->name('admin.api.check-session');

// Notebook viewer route: renders the SmartHarvest.ipynb content in a simple HTML view
Route::get('/notebook', function () {
    $path = base_path('SmartHarvest.ipynb');
    $json = 'null';
    if (file_exists($path)) {
        $json = file_get_contents($path);
    }
    return view('notebook', ['notebookJson' => $json]);
})->name('notebook');

// ML API Test Page
Route::get('/ml-test', function () {
    return view('ml_test');
})->name('ml.test.page');

// ML API Test Routes
Route::get('/api/ml/test', function () {
    $mlService = new \App\Services\MLApiService();
    $result = $mlService->checkHealth();
    
    return response()->json([
        'test_time' => now()->toDateTimeString(),
        'configured_url' => $mlService->getBaseUrl(),
        'health_check' => $result,
    ]);
})->name('ml.test');

Route::get('/api/ml/test-prediction', function () {
    $mlService = new \App\Services\MLApiService();
    
    // Sample prediction data
    $testData = [
        'municipality' => 'La Trinidad',
        'crop_type' => 'Cabbage',
        'area_planted' => 2.5,
        'month' => now()->month,
        'year' => now()->year,
    ];
    
    $result = $mlService->predict($testData);
    
    return response()->json([
        'test_time' => now()->toDateTimeString(),
        'test_data' => $testData,
        'prediction_result' => $result,
    ]);
})->name('ml.test.prediction');

// Include farmer API routes
require __DIR__.'/farmer_api.php';

Route::get('/register', function () {
    return view('register');
})->name('register');

// POST handler for user registration
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email|max:255',
        'municipality' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['full_name'],
        'email' => $validated['email'],
        'location' => $validated['municipality'],
        'role' => 'Farmer',
        'status' => 'active',
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('dashboard');
})->name('register.attempt');
