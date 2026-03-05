<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Weather Forecast - DA Officer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar { background: linear-gradient(180deg, #047857 0%, #065f46 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
    </style>
</head>
<body class="bg-gray-50 flex" x-data="weatherDashboard()">
    <!-- Sidebar -->
    <aside class="sidebar w-64 min-h-screen text-white flex-shrink-0">
        <div class="p-6">
            <div class="flex items-center space-x-2 mb-8">
                <span class="text-2xl">🌱</span>
                <span class="text-xl font-semibold">SmartHarvest</span>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Overview</p>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.dashboard') }}#market-prices" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Market Prices</span>
                </a>
                <a href="{{ route('admin.dashboard') }}#announcements" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    <span>Announcements</span>
                </a>
                <a href="{{ route('admin.dashboard') }}#inbox" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Inbox</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Weather</p>
                <a href="{{ route('admin.forecast') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    <span>Forecast</span>
                </a>
                <a href="{{ route('pagasa.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>PAGASA Data</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">User Management</p>
                <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Users</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Data Management</p>
                <a href="{{ route('admin.datasets') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    <span>Datasets</span>
                </a>
                <a href="{{ route('admin.dataimport') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <span>Data Import</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Monitoring</p>
                <a href="{{ route('admin.monitoring') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Provincial Monitoring</span>
                </a>
            </div>

            <div>
                <p class="text-xs uppercase text-green-300 mb-3">System</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Top Header -->
        <header class="bg-white border-b px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-green-700">Provincial Weather Forecast</h1>
                    <p class="text-sm text-gray-600 mt-1">Administrative weather monitoring and forecasting dashboard</p>
                </div>
                <div class="flex items-center space-x-4">
                    <select x-model="selectedMunicipality" @change="loadWeatherData()" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="All Municipalities">All Municipalities</option>
                        <template x-for="municipality in municipalities" :key="municipality">
                            <option :value="municipality" x-text="municipality"></option>
                        </template>
                    </select>
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-8 overflow-y-auto bg-gray-50">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Temperature Now</p>
                            <p class="text-3xl font-bold text-gray-800" x-text="currentWeather.temp + '°C'">--°C</p>
                            <p class="text-xs text-gray-500 mt-1" x-text="currentWeather.description">Loading...</p>
                        </div>
                        <svg class="w-12 h-12 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Humidity</p>
                            <p class="text-3xl font-bold text-blue-600" x-text="currentWeather.humidity + '%'">--</p>
                            <p class="text-xs text-gray-500 mt-1">Relative</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Wind Speed</p>
                            <p class="text-3xl font-bold text-green-600" x-text="currentWeather.wind + ' m/s'">--</p>
                            <p class="text-xs text-gray-500 mt-1" x-text="currentWeather.windDir">--</p>
                        </div>
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Precipitation</p>
                            <p class="text-3xl font-bold text-indigo-600" x-text="currentWeather.rain + ' mm'">--</p>
                            <p class="text-xs text-gray-500 mt-1">Expected Today</p>
                        </div>
                        <svg class="w-12 h-12 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 7-Day Forecast -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">7-Day Weather Forecast</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                        <template x-for="(day, index) in forecast" :key="index">
                            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg p-4 text-center hover:shadow-lg transition">
                                <p class="text-sm font-semibold text-gray-700" x-text="day.day"></p>
                                <p class="text-xs text-gray-500" x-text="day.date"></p>
                                <div class="my-3">
                                    <svg class="w-12 h-12 mx-auto" :class="day.icon_color" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-gray-800" x-text="day.temp + '°C'"></p>
                                <p class="text-xs text-gray-600 mt-1" x-text="day.condition"></p>
                                <div class="mt-3 space-y-1">
                                    <p class="text-xs text-blue-600">💧 <span x-text="day.humidity + '%'"></span></p>
                                    <p class="text-xs text-green-600">🌊 <span x-text="day.rain + 'mm'"></span></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Advisory Panel -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-orange-500 rounded-lg shadow p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-orange-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-orange-800 mb-2">Administrative Weather Advisory</h3>
                        <p class="text-gray-700 mb-3" x-text="advisory.message">Loading advisory...</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">DA Officer Only</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm" x-text="advisory.severity"></span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function weatherDashboard() {
            return {
                municipalities: [
                    'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 'Kabayan', 
                    'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 'Sablan', 
                    'Tuba', 'Tublay'
                ],
                selectedMunicipality: 'La Trinidad',
                currentWeather: {
                    temp: 18,
                    description: 'Partly Cloudy',
                    humidity: 75,
                    wind: 3.5,
                    windDir: 'NE',
                    rain: 2.5
                },
                forecast: [
                    { day: 'Mon', date: 'Feb 28', temp: 18, condition: 'Cloudy', humidity: 75, rain: 5, icon_color: 'text-gray-400' },
                    { day: 'Tue', date: 'Mar 01', temp: 19, condition: 'Partly Cloudy', humidity: 70, rain: 2, icon_color: 'text-blue-400' },
                    { day: 'Wed', date: 'Mar 02', temp: 20, condition: 'Sunny', humidity: 65, rain: 0, icon_color: 'text-yellow-400' },
                    { day: 'Thu', date: 'Mar 03', temp: 18, condition: 'Rainy', humidity: 85, rain: 15, icon_color: 'text-blue-600' },
                    { day: 'Fri', date: 'Mar 04', temp: 17, condition: 'Rainy', humidity: 80, rain: 12, icon_color: 'text-blue-600' },
                    { day: 'Sat', date: 'Mar 05', temp: 19, condition: 'Cloudy', humidity: 72, rain: 3, icon_color: 'text-gray-400' },
                    { day: 'Sun', date: 'Mar 06', temp: 20, condition: 'Sunny', humidity: 68, rain: 0, icon_color: 'text-yellow-400' }
                ],
                advisory: {
                    message: 'Heavy rainfall expected Thursday-Friday. Monitor crop conditions and prepare drainage systems. Advise farmers in low-lying areas.',
                    severity: 'Moderate Risk'
                },
                loadWeatherData() {
                    console.log('Loading weather for:', this.selectedMunicipality);
                    // API integration here
                }
            }
        }
    </script>
</body>
</html>
