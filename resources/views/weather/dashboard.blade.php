<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PAGASA Weather Forecast - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar { background: linear-gradient(180deg, #15803d 0%, #166534 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
        .weather-card { transition: all 0.3s ease; }
        .weather-card:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-green-700 to-green-900 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-6 border-b border-green-600">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <span class="text-xl">🌾</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SmartHarvest</h1>
                        <p class="text-xs text-green-200">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Overview</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Weather</p>
                    <a href="{{ route('admin.forecast') }}" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                        </svg>
                        <span>Forecast</span>
                    </a>
                    <a href="{{ route('pagasa.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded sidebar-item active">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>PAGASA Data</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">User Management</p>
                    <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Users</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Data Management</p>
                    <a href="{{ route('admin.datasets') }}" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                        </svg>
                        <span>Datasets</span>
                    </a>
                    <a href="{{ route('admin.dataimport') }}" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span>Data Import</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Monitoring</p>
                    <a href="{{ route('admin.monitoring') }}" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Provincial Monitoring</span>
                    </a>
                </div>

                <div class="pt-4 border-t border-green-600">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">System</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors w-full text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm flex-shrink-0">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">PAGASA Weather Forecast</h2>
                            <p class="text-sm text-gray-600 mt-1">Live agricultural weather information from PAGASA</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-600">
                                Last Updated: <span class="font-semibold">{{ $last_update ? \Carbon\Carbon::parse($last_update)->diffForHumans() : 'Not available' }}</span>
                            </div>
                            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin', 'da_admin', 'Admin', 'DA Admin']))
                            <button onclick="updateWeather()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Update Now
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- ENSO Alert Status -->
                @if($enso)
                <div class="mb-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">ENSO Alert System</h3>
                                <p class="text-xl font-bold mt-1">{{ strtoupper(str_replace('_', ' ', $enso->status)) }}</p>
                                <p class="text-sm mt-1 opacity-90">{{ Str::limit(strip_tags($enso->description), 200) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-75">Updated</p>
                            <p class="font-semibold">{{ $enso->updated_date ? \Carbon\Carbon::parse($enso->updated_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    @if($enso->recommendations)
                    <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                        <p class="text-sm font-semibold mb-2">Recommendations:</p>
                        <p class="text-sm opacity-90">{{ Str::limit($enso->recommendations, 200) }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Soil Moisture Overview -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Soil Moisture Condition</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 weather-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-blue-600 font-semibold mb-1">WET</p>
                                    <p class="text-3xl font-bold text-blue-700">{{ $soil_moisture['wet'] ?? 0 }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Municipalities</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-6 weather-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-green-600 font-semibold mb-1">MOIST</p>
                                    <p class="text-3xl font-bold text-green-700">{{ $soil_moisture['moist'] ?? 0 }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Municipalities</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-6 weather-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-orange-600 font-semibold mb-1">DRY</p>
                                    <p class="text-3xl font-bold text-orange-700">{{ $soil_moisture['dry'] ?? 0 }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Municipalities</p>
                                </div>
                                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gale Warnings -->
                @if($warnings && $warnings->count() > 0)
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                        </svg>
                        <h3 class="text-lg font-bold text-red-800">Active Gale Warnings</h3>
                    </div>
                    @foreach($warnings as $warning)
                    <div class="bg-white rounded-lg p-4 mb-3 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $warning->area }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $warning->description }}</p>
                                @if($warning->affected_municipalities)
                                <p class="text-xs text-gray-500 mt-2">Affected: {{ Str::limit($warning->affected_municipalities, 150) }}</p>
                                @endif
                            </div>
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">{{ strtoupper($warning->severity) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Farming Advisories -->
                @if($advisories && $advisories->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Farming Advisories</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($advisories->take(6) as $advisory)
                        <div class="bg-white rounded-lg p-5 shadow-sm border-l-4 {{ $advisory->severity === 'critical' ? 'border-red-500' : ($advisory->severity === 'warning' ? 'border-yellow-500' : 'border-blue-500') }}">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-gray-800 flex-1">{{ $advisory->title }}</h4>
                                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded {{ $advisory->severity === 'critical' ? 'bg-red-100 text-red-800' : ($advisory->severity === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($advisory->severity) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ Str::limit($advisory->description, 150) }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $advisory->advisory_date->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Regional Weather Forecasts -->
                @if($forecasts && $forecasts->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Regional Weather Forecasts</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($forecasts as $forecast)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden weather-card">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4">
                                <h4 class="font-bold text-lg">{{ $forecast->region }}</h4>
                                <p class="text-sm opacity-90">{{ $forecast->forecast_date->format('F d, Y') }}</p>
                            </div>
                            <div class="p-5">
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-1">Weather Condition</p>
                                    <p class="text-gray-800">{{ $forecast->weather_condition }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-xs text-gray-500">Temperature (°C)</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $forecast->temp_low_range }} - {{ $forecast->temp_high_range }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Humidity (%)</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $forecast->humidity_range }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Rainfall (mm)</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $forecast->rainfall_range }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Wind</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ Str::limit($forecast->wind_condition, 20) }}</p>
                                    </div>
                                </div>
                                @if($forecast->synopsis)
                                <div class="pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Synopsis</p>
                                    <p class="text-sm text-gray-700">{{ $forecast->synopsis }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Source Attribution -->
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <p class="text-sm text-gray-600">
                        Data source: 
                        <a href="https://www.pagasa.dost.gov.ph/agri-weather" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold">
                            PAGASA - Philippine Atmospheric, Geophysical and Astronomical Services Administration
                        </a>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Data is automatically updated daily at 7:00 AM</p>
                </div>
            </main>
        </div>
    </div>

    <script>
        function updateWeather() {
            if (confirm('Update weather data from PAGASA? This may take a few moments.')) {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-4 w-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';
                
                fetch('{{ url('/api/pagasa/update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Weather data updated successfully!');
                        location.reload();
                    } else {
                        alert('Failed to update weather data: ' + (data.message || 'Unknown error'));
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    }
                })
                .catch(error => {
                    alert('Error updating weather data: ' + error.message);
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                });
            }
        }
    </script>
</body>
</html>
