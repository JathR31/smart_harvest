<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ClimatePattern;
use App\Models\CropData;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return redirect()->route('login');
        }

        $totalUsers = User::count();
        $totalFarmers = User::where('role', 'Farmer')->count();
        $totalAdmins = User::where('role', 'Admin')->count();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin_dash', compact('totalUsers', 'totalFarmers', 'totalAdmins', 'recentUsers'));
    }

    public function monitoring()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return redirect()->route('login');
        }

        return view('monitoring');
    }

    public function users()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return redirect()->route('login');
        }

        return view('users');
    }

    public function roles()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return redirect()->route('login');
        }

        return view('roles_permissions');
    }

    public function datasets()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return redirect()->route('login');
        }

        return view('datasets');
    }

    public function dataimport()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return redirect()->route('login');
        }

        return view('dataimport');
    }
}
