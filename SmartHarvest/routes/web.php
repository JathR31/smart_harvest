<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('homepage'); 
});

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

// Admin login POST handler (simple demo implementation)
Route::post('/admin/login', function (Request $request) {
    // Simple demo credentials check. Replace with real auth in production.
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $email = $request->input('email');
    $password = $request->input('password');

    if ($email === 'admin@smartharvest.com' && $password === 'admin') {
        // Mark admin as logged in for demo purposes (use auth in real app)
        session(['is_admin' => true]);
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors(['email' => 'Invalid admin credentials'])->withInput();
})->name('admin.login.attempt');

// Simple admin dashboard (demo)
Route::get('/admin/dashboard', function () {
    // In a real app, protect this with auth middleware
    return view('admin_dashboard');
})->name('admin.dashboard');

// User dashboard (protected)
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('dashboard');
})->name('dashboard');

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