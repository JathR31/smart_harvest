<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CropData;
use App\Models\ClimatePattern;

class FarmerController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'Farmer') {
            return redirect()->route('login');
        }

        $userMunicipality = Auth::user()->municipality ?? 'La Trinidad';
        
        return view('dashboard', compact('userMunicipality'));
    }

    public function yieldAnalysis()
    {
        if (!Auth::check() || Auth::user()->role !== 'Farmer') {
            return redirect()->route('login');
        }

        $userMunicipality = Auth::user()->municipality ?? 'La Trinidad';
        
        return view('yield_analysis', compact('userMunicipality'));
    }

    public function forecast()
    {
        if (!Auth::check() || Auth::user()->role !== 'Farmer') {
            return redirect()->route('login');
        }

        $userMunicipality = Auth::user()->municipality ?? 'La Trinidad';
        
        return view('forecast', compact('userMunicipality'));
    }

    public function plantingSchedule()
    {
        if (!Auth::check() || Auth::user()->role !== 'Farmer') {
            return redirect()->route('login');
        }

        $userMunicipality = Auth::user()->municipality ?? 'La Trinidad';
        
        return view('planting_schedule', compact('userMunicipality'));
    }

    public function settings()
    {
        if (!Auth::check() || Auth::user()->role !== 'Farmer') {
            return redirect()->route('login');
        }

        return view('settings');
    }
}
