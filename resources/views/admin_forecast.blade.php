<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Weather Forecast - SmartHarvest DA Officer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item:hover { background: rgba(255, 255, 255, 0.1); }
        .stat-card { transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="bg-gray-50" x-data="weatherDashboard()" x-init="init()">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Uniform with admin_dacar -->
        <aside class="w-64 bg-gradient-to-b from-green-700 to-green-900 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-6 border-b border-green-600">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <span class="text-xl">🌾</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SmartHarvest</h1>
                        <p class="text-xs text-green-200">DA Officer</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Overview</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded transition-colors w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}#market-prices" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded transition-colors w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Market Prices</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}#announcements" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded transition-colors w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Announcements</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}#inbox" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded transition-colors w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Inbox</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Weather</p>
                    <a href="{{ route('admin.forecast') }}" class="bg-green-600 flex items-center space-x-3 px-4 py-3 rounded transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                        </svg>
                        <span>Forecast</span>
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

                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">System</p>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return handleLogout(event);">
                        @csrf
                        <button type="submit" class="sidebar-item w-full flex items-center space-x-3 px-4 py-3 rounded hover:bg-red-600 transition-colors text-left">
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
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                            </svg>
                            <span class="text-lg font-semibold text-gray-800">Weather Forecast</span>
                            <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">LIVE</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <select x-model="selectedMunicipality" @change="loadWeatherData()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <template x-for="m in municipalities" :key="m.name">
                                <option :value="m.name" x-text="m.name" :selected="m.name === selectedMunicipality"></option>
                            </template>
                        </select>
                        <button @click="loadWeatherData()" class="p-2 text-gray-600 hover:bg-gray-100 rounded-full" title="Refresh">
                            <svg class="w-5 h-5" :class="loading ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                        <span class="text-xs text-gray-500" x-text="lastUpdated ? 'Updated: ' + lastUpdated : ''"></span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Loading State -->
                <div x-show="loading" x-cloak class="flex items-center justify-center h-64">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-500">Fetching live weather data...</p>
                    </div>
                </div>

                <!-- Error State -->
                <div x-show="error && !loading" x-cloak class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-red-800 font-medium">Failed to load weather data</h3>
                            <p class="text-red-600 text-sm" x-text="error"></p>
                        </div>
                        <button @click="loadWeatherData()" class="ml-auto text-red-600 hover:text-red-800 text-sm font-medium underline">Retry</button>
                    </div>
                </div>

                <!-- Weather Content -->
                <div x-show="!loading" x-cloak>
                    <!-- Current Weather Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Temperature</p>
                                    <p class="text-3xl font-bold text-gray-800"><span x-text="currentWeather.temp"></span>°C</p>
                                    <p class="text-xs text-gray-500 mt-1">Feels like <span x-text="currentWeather.feelsLike"></span>°C</p>
                                </div>
                                <div class="w-14 h-14 rounded-full bg-yellow-50 flex items-center justify-center">
                                    <img x-show="currentWeather.icon" :src="'https://openweathermap.org/img/wn/' + currentWeather.icon + '@2x.png'" class="w-12 h-12" alt="weather icon">
                                </div>
                            </div>
                            <p class="text-sm text-blue-600 font-medium mt-2 capitalize" x-text="currentWeather.description"></p>
                        </div>

                        <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Humidity</p>
                                    <p class="text-3xl font-bold text-blue-600"><span x-text="currentWeather.humidity"></span>%</p>
                                    <p class="text-xs text-gray-500 mt-1">Pressure: <span x-text="currentWeather.pressure"></span> hPa</p>
                                </div>
                                <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full transition-all" :style="'width:' + currentWeather.humidity + '%'"></div>
                            </div>
                        </div>

                        <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Wind</p>
                                    <p class="text-3xl font-bold text-green-600"><span x-text="currentWeather.wind"></span> <span class="text-sm font-normal">m/s</span></p>
                                    <p class="text-xs text-gray-500 mt-1">Direction: <span x-text="currentWeather.windDir"></span> (<span x-text="currentWeather.windDeg"></span>°)</p>
                                </div>
                                <div class="w-14 h-14 rounded-full bg-green-50 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" :style="'transform: rotate(' + (currentWeather.windDeg || 0) + 'deg)'">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2" x-show="currentWeather.gust">Gusts: <span x-text="currentWeather.gust"></span> m/s</p>
                        </div>

                        <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Rain / Clouds</p>
                                    <p class="text-3xl font-bold text-indigo-600"><span x-text="currentWeather.rain"></span> <span class="text-sm font-normal">mm</span></p>
                                    <p class="text-xs text-gray-500 mt-1">Cloud cover: <span x-text="currentWeather.clouds"></span>%</p>
                                </div>
                                <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Visibility: <span x-text="currentWeather.visibility"></span> km</p>
                        </div>
                    </div>

                    <!-- Sun & Atmospheric -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-orange-50 to-yellow-50 rounded-xl shadow-sm border border-orange-100 p-6">
                            <h3 class="text-sm font-semibold text-orange-800 mb-3">Sunrise & Sunset</h3>
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-orange-400 mx-auto mb-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-lg font-bold text-orange-700" x-text="currentWeather.sunrise"></p>
                                    <p class="text-xs text-orange-600">Sunrise</p>
                                </div>
                                <div class="flex-1 mx-4 h-1 bg-gradient-to-r from-orange-300 via-yellow-300 to-orange-300 rounded-full"></div>
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-orange-500 mx-auto mb-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-lg font-bold text-orange-700" x-text="currentWeather.sunset"></p>
                                    <p class="text-xs text-orange-600">Sunset</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Temperature Range</h3>
                            <div class="flex items-end justify-between h-24">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-blue-600" x-text="currentWeather.tempMin + '°'"></p>
                                    <p class="text-xs text-gray-500">Min</p>
                                </div>
                                <div class="flex-1 mx-4 relative h-full flex items-center">
                                    <div class="w-full bg-gradient-to-r from-blue-200 via-green-200 to-red-200 rounded-full h-3"></div>
                                    <div class="absolute w-4 h-4 bg-white border-2 border-green-600 rounded-full shadow" :style="'left:' + ((currentWeather.temp - currentWeather.tempMin) / ((currentWeather.tempMax - currentWeather.tempMin) || 1) * 100) + '%'"></div>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-red-600" x-text="currentWeather.tempMax + '°'"></p>
                                    <p class="text-xs text-gray-500">Max</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Location Details</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between"><span class="text-gray-500">Municipality</span><span class="font-medium" x-text="selectedMunicipality"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Province</span><span class="font-medium">Benguet, CAR</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Coordinates</span><span class="font-medium" x-text="currentCoords.lat.toFixed(3) + '°N, ' + currentCoords.lon.toFixed(3) + '°E'"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Elevation</span><span class="font-medium" x-text="getElevation(selectedMunicipality)"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- 5-Day Forecast -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-800">5-Day Weather Forecast</h2>
                            <span class="text-xs text-gray-500">Data from OpenWeatherMap</span>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <template x-for="(day, index) in forecast" :key="index">
                                    <div class="bg-gradient-to-br from-blue-50 to-green-50 rounded-xl p-5 text-center hover:shadow-lg transition border border-gray-100">
                                        <p class="text-sm font-bold text-gray-700" x-text="day.dayName"></p>
                                        <p class="text-xs text-gray-500 mb-2" x-text="day.date"></p>
                                        <img :src="'https://openweathermap.org/img/wn/' + day.icon + '@2x.png'" class="w-16 h-16 mx-auto" alt="weather icon">
                                        <p class="text-xs text-gray-600 capitalize mb-2" x-text="day.description"></p>
                                        <div class="flex items-center justify-center gap-2 mb-2">
                                            <span class="text-lg font-bold text-red-600" x-text="day.tempMax + '°'"></span>
                                            <span class="text-gray-400">/</span>
                                            <span class="text-lg font-bold text-blue-600" x-text="day.tempMin + '°'"></span>
                                        </div>
                                        <div class="space-y-1 text-xs">
                                            <div class="flex items-center justify-center gap-1 text-blue-600">
                                                <span>💧</span> <span x-text="day.humidity + '%'"></span>
                                            </div>
                                            <div class="flex items-center justify-center gap-1 text-green-600">
                                                <span>💨</span> <span x-text="day.wind + ' m/s'"></span>
                                            </div>
                                            <div class="flex items-center justify-center gap-1 text-indigo-600" x-show="day.rain > 0">
                                                <span>🌧️</span> <span x-text="day.rain + ' mm'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Agricultural Advisory -->
                    <div class="rounded-xl shadow-sm border p-6 mb-6" :class="advisory.color">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 mr-3 flex-shrink-0 mt-1" :class="advisory.iconColor" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold mb-2" :class="advisory.titleColor" x-text="advisory.title">Weather Advisory</h3>
                                <p class="text-gray-700 mb-3" x-text="advisory.message">Loading advisory...</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium" :class="advisory.badgeColor" x-text="advisory.severity"></span>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                        <span x-text="selectedMunicipality"></span>, Benguet
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function weatherDashboard() {
            return {
                loading: true,
                error: null,
                lastUpdated: null,
                apiKey: '{{ env("OPENWEATHER_API_KEY", "") }}',
                selectedMunicipality: 'La Trinidad',

                municipalities: [
                    { name: 'La Trinidad', lat: 16.4561, lon: 120.5870 },
                    { name: 'Baguio City', lat: 16.4023, lon: 120.5960 },
                    { name: 'Atok', lat: 16.5970, lon: 120.7090 },
                    { name: 'Bakun', lat: 16.7880, lon: 120.6590 },
                    { name: 'Bokod', lat: 16.4860, lon: 120.8230 },
                    { name: 'Buguias', lat: 16.7310, lon: 120.8360 },
                    { name: 'Itogon', lat: 16.3690, lon: 120.6540 },
                    { name: 'Kabayan', lat: 16.6190, lon: 120.8370 },
                    { name: 'Kapangan', lat: 16.5692, lon: 120.5920 },
                    { name: 'Kibungan', lat: 16.6850, lon: 120.6540 },
                    { name: 'Mankayan', lat: 16.8590, lon: 120.7770 },
                    { name: 'Sablan', lat: 16.4880, lon: 120.5060 },
                    { name: 'Tuba', lat: 16.3600, lon: 120.5650 },
                    { name: 'Tublay', lat: 16.5110, lon: 120.6200 }
                ],

                currentCoords: { lat: 16.4561, lon: 120.5870 },

                currentWeather: {
                    temp: '--', feelsLike: '--', tempMin: '--', tempMax: '--',
                    description: 'Loading...', icon: '01d',
                    humidity: 0, pressure: '--', wind: '--', windDir: '--',
                    windDeg: 0, gust: null, rain: 0, clouds: 0,
                    visibility: '--', sunrise: '--', sunset: '--'
                },

                forecast: [],

                advisory: {
                    title: 'Loading Advisory...',
                    message: 'Fetching weather data to generate advisory...',
                    severity: 'Loading',
                    color: 'bg-gray-50 border-gray-200',
                    iconColor: 'text-gray-400',
                    titleColor: 'text-gray-700',
                    badgeColor: 'bg-gray-100 text-gray-700'
                },

                async init() {
                    await this.loadWeatherData();
                },

                getElevation(name) {
                    const elevations = {
                        'La Trinidad': '~1,300 m', 'Baguio City': '~1,469 m', 'Atok': '~1,800 m',
                        'Bakun': '~1,600 m', 'Bokod': '~900 m', 'Buguias': '~1,900 m',
                        'Itogon': '~600 m', 'Kabayan': '~1,500 m', 'Kapangan': '~1,200 m',
                        'Kibungan': '~1,400 m', 'Mankayan': '~1,500 m', 'Sablan': '~800 m',
                        'Tuba': '~500 m', 'Tublay': '~1,100 m'
                    };
                    return elevations[name] || 'N/A';
                },

                getWindDirection(deg) {
                    const dirs = ['N','NNE','NE','ENE','E','ESE','SE','SSE','S','SSW','SW','WSW','W','WNW','NW','NNW'];
                    return dirs[Math.round(deg / 22.5) % 16];
                },

                formatTime(timestamp, tz) {
                    const d = new Date((timestamp + tz) * 1000);
                    let h = d.getUTCHours(), m = d.getUTCMinutes();
                    const ampm = h >= 12 ? 'PM' : 'AM';
                    h = h % 12 || 12;
                    return h + ':' + (m < 10 ? '0' : '') + m + ' ' + ampm;
                },

                async loadWeatherData() {
                    this.loading = true;
                    this.error = null;

                    const muni = this.municipalities.find(m => m.name === this.selectedMunicipality);
                    if (!muni) { this.error = 'Municipality not found'; this.loading = false; return; }

                    this.currentCoords = { lat: muni.lat, lon: muni.lon };

                    if (!this.apiKey) {
                        this.error = 'OpenWeatherMap API key not configured in .env (OPENWEATHER_API_KEY)';
                        this.loading = false;
                        return;
                    }

                    try {
                        // Current Weather
                        const currentUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${muni.lat}&lon=${muni.lon}&appid=${this.apiKey}&units=metric`;
                        const currentRes = await fetch(currentUrl);
                        if (!currentRes.ok) {
                            const errBody = await currentRes.json().catch(() => ({}));
                            throw new Error(errBody.message || `API error: ${currentRes.status}`);
                        }
                        const current = await currentRes.json();

                        this.currentWeather = {
                            temp: Math.round(current.main.temp),
                            feelsLike: Math.round(current.main.feels_like),
                            tempMin: Math.round(current.main.temp_min),
                            tempMax: Math.round(current.main.temp_max),
                            description: current.weather[0].description,
                            icon: current.weather[0].icon,
                            humidity: current.main.humidity,
                            pressure: current.main.pressure,
                            wind: current.wind.speed,
                            windDir: this.getWindDirection(current.wind.deg || 0),
                            windDeg: current.wind.deg || 0,
                            gust: current.wind.gust || null,
                            rain: current.rain ? (current.rain['1h'] || current.rain['3h'] || 0) : 0,
                            clouds: current.clouds.all,
                            visibility: ((current.visibility || 10000) / 1000).toFixed(1),
                            sunrise: this.formatTime(current.sys.sunrise, current.timezone),
                            sunset: this.formatTime(current.sys.sunset, current.timezone)
                        };

                        // 5-Day Forecast
                        const forecastUrl = `https://api.openweathermap.org/data/2.5/forecast?lat=${muni.lat}&lon=${muni.lon}&appid=${this.apiKey}&units=metric`;
                        const forecastRes = await fetch(forecastUrl);
                        if (!forecastRes.ok) throw new Error('Forecast API error');
                        const forecastData = await forecastRes.json();

                        // Group by day and pick the midday reading (or closest)
                        const daily = {};
                        forecastData.list.forEach(item => {
                            const date = item.dt_txt.split(' ')[0];
                            if (!daily[date]) {
                                daily[date] = { items: [], tempMin: Infinity, tempMax: -Infinity, rain: 0 };
                            }
                            daily[date].items.push(item);
                            daily[date].tempMin = Math.min(daily[date].tempMin, item.main.temp_min);
                            daily[date].tempMax = Math.max(daily[date].tempMax, item.main.temp_max);
                            daily[date].rain += (item.rain ? item.rain['3h'] || 0 : 0);
                        });

                        const dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                        const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                        const today = new Date().toISOString().split('T')[0];

                        this.forecast = Object.entries(daily)
                            .filter(([date]) => date !== today)
                            .slice(0, 5)
                            .map(([date, data]) => {
                                // Pick midday entry or the middle one
                                const midday = data.items.find(i => i.dt_txt.includes('12:00:00')) || data.items[Math.floor(data.items.length / 2)];
                                const d = new Date(date);
                                return {
                                    dayName: dayNames[d.getDay()],
                                    date: monthNames[d.getMonth()] + ' ' + d.getDate(),
                                    tempMin: Math.round(data.tempMin),
                                    tempMax: Math.round(data.tempMax),
                                    description: midday.weather[0].description,
                                    icon: midday.weather[0].icon,
                                    humidity: midday.main.humidity,
                                    wind: midday.wind.speed,
                                    rain: Math.round(data.rain * 10) / 10
                                };
                            });

                        // Generate agricultural advisory
                        this.generateAdvisory();

                        this.lastUpdated = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                    } catch (err) {
                        console.error('Weather API error:', err);
                        this.error = err.message || 'Failed to fetch weather data';
                    } finally {
                        this.loading = false;
                    }
                },

                generateAdvisory() {
                    const temp = this.currentWeather.temp;
                    const humidity = this.currentWeather.humidity;
                    const rain = this.currentWeather.rain;
                    const wind = this.currentWeather.wind;
                    const upcomingRain = this.forecast.reduce((sum, d) => sum + d.rain, 0);

                    let title, message, severity, color, iconColor, titleColor, badgeColor;

                    if (rain > 10 || upcomingRain > 50) {
                        title = '🌧️ Heavy Rain Warning';
                        message = `Heavy rainfall detected (${rain}mm current, ${upcomingRain.toFixed(1)}mm expected over 5 days). ` +
                            'Advise farmers to secure harvest-ready crops, clear drainage canals, and delay planting of new seedlings. ' +
                            'Monitor landslide-prone areas in highland municipalities.';
                        severity = 'High Risk';
                        color = 'bg-red-50 border-red-200';
                        iconColor = 'text-red-500';
                        titleColor = 'text-red-800';
                        badgeColor = 'bg-red-100 text-red-700';
                    } else if (wind > 8) {
                        title = '💨 Strong Wind Advisory';
                        message = `Wind speeds of ${wind} m/s recorded. Secure greenhouses and protective structures. ` +
                            'Tall crops like corn may be vulnerable. Consider delaying pesticide spraying until winds subside.';
                        severity = 'Moderate Risk';
                        color = 'bg-orange-50 border-orange-200';
                        iconColor = 'text-orange-500';
                        titleColor = 'text-orange-800';
                        badgeColor = 'bg-orange-100 text-orange-700';
                    } else if (humidity > 85) {
                        title = '🍄 High Humidity Alert';
                        message = `Humidity at ${humidity}% — conditions favorable for fungal diseases. ` +
                            'Monitor vegetable crops for blight and mildew. Apply preventive fungicide treatment where applicable. ' +
                            'Ensure proper spacing and ventilation in nurseries.';
                        severity = 'Moderate Risk';
                        color = 'bg-yellow-50 border-yellow-200';
                        iconColor = 'text-yellow-600';
                        titleColor = 'text-yellow-800';
                        badgeColor = 'bg-yellow-100 text-yellow-700';
                    } else if (temp > 30) {
                        title = '🌡️ High Temperature Advisory';
                        message = `Temperature at ${temp}°C — above normal for Cordillera highlands. ` +
                            'Increase irrigation frequency, especially for newly transplanted seedlings. Consider mulching to retain soil moisture.';
                        severity = 'Low Risk';
                        color = 'bg-orange-50 border-orange-200';
                        iconColor = 'text-orange-500';
                        titleColor = 'text-orange-800';
                        badgeColor = 'bg-orange-100 text-orange-700';
                    } else {
                        title = '✅ Favorable Weather Conditions';
                        message = `Current conditions (${temp}°C, ${humidity}% humidity, ${wind} m/s wind) are favorable for agricultural activities. ` +
                            `Expected rainfall over the next 5 days: ${upcomingRain.toFixed(1)}mm. ` +
                            'Good conditions for planting, fertilizer application, and general field operations.';
                        severity = 'Low Risk';
                        color = 'bg-green-50 border-green-200';
                        iconColor = 'text-green-500';
                        titleColor = 'text-green-800';
                        badgeColor = 'bg-green-100 text-green-700';
                    }

                    this.advisory = { title, message, severity, color, iconColor, titleColor, badgeColor };
                }
            };
        }

        // Handle logout with CSRF token expiration fallback
        function handleLogout(event) {
            sessionStorage.setItem('isLoggedOut', 'true');
            event.preventDefault();

            fetch('{{ route("logout") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            }).then(response => {
                if (response.status === 419) {
                    window.location.href = '{{ route("logout.expired") }}';
                } else {
                    window.location.href = '{{ route("login") }}';
                }
            }).catch(error => {
                window.location.href = '{{ route("logout.expired") }}';
            });

            return false;
        }
    </script>
</body>
</html>
