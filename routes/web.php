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

// POST handler for user login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
})->name('login.attempt');

Route::get('/admin', function () {
    return view('admin_login');
})->name('admin.login');

// Admin login POST handler
Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $email = $request->input('email');
    $password = $request->input('password');

    // Admin credentials: smartharvestadmin@gmail.com / smartharvest
    if ($email === 'smartharvestadmin@gmail.com' && $password === 'smartharvest') {
        session(['is_admin' => true, 'admin_email' => $email]);
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors(['email' => 'Invalid admin credentials'])->withInput();
})->name('admin.login.attempt');

// Admin dashboard
Route::get('/admin/dashboard', function () {
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return view('admin_dash');
})->name('admin.dashboard');

// Admin API - Dashboard data
Route::get('/admin/api/dashboard', function () {
    if (!session('is_admin')) {
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
    if (!session('is_admin')) {
        return redirect()->route('admin.login');
    }
    return view('users');
})->name('admin.users');

// Admin API - Users Management
Route::get('/admin/api/users', function () {
    if (!session('is_admin')) {
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
    if (!session('is_admin')) {
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
    if (!session('is_admin')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = \App\Models\User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted successfully']);
})->name('admin.api.users.delete');

// User dashboard (protected)
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('dashboard');
})->name('dashboard');

// Planting schedule page (protected)
Route::get('/planting-schedule', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('planting_schedule');
})->name('planting.schedule');

// Yield analysis page (protected)
Route::get('/yield-analysis', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('yield_analysis');
})->name('yield.analysis');

// Forecast page (protected)
Route::get('/forecast', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('forecast');
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

// Logout route
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Notebook viewer route: renders the SmartHarvest.ipynb content in a simple HTML view
Route::get('/notebook', function () {
    $path = base_path('SmartHarvest.ipynb');
    $json = 'null';
    if (file_exists($path)) {
        $json = file_get_contents($path);
    }
    return view('notebook', ['notebookJson' => $json]);
})->name('notebook');

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
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('dashboard');
})->name('register.attempt');
