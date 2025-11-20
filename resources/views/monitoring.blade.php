<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provincial Monitoring Dashboard - SmartHarvest Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sidebar-item {
            transition: all 0.2s;
        }
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.15);
        }
        .alert-card {
            transition: all 0.3s;
        }
        .alert-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50" x-data="monitoringApp()" x-init="init()">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-6 border-b border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-800" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SmartHarvest</h1>
                        <p class="text-xs text-blue-200">Admin</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">Overview</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Admin Dashboard</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">User Management</p>
                    <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.roles') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                        </svg>
                        <span>Roles & Permissions</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">Data Management</p>
                    <a href="{{ route('admin.datasets') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                        </svg>
                        <span>Datasets</span>
                    </a>
                    <a href="{{ route('admin.dataimport') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span>Data Import</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">System</p>
                    <a href="{{ route('admin.monitoring') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Monitoring</span>
                    </a>
                    <a href="#" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Logs</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="sidebar-item">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded w-full text-left">
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
                    <div class="flex items-center">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-sm text-gray-600">SmartHarvest Admin</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search..." 
                                   x-model="searchQuery"
                                   class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option>English</option>
                        </select>
                        <div class="relative">
                            <button class="p-2 bg-red-500 text-white rounded-full relative">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                </svg>
                                <span class="absolute -top-1 -right-1 bg-white text-red-500 text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold" x-text="activeAlerts.length"></span>
                            </button>
                        </div>
                        <button class="text-sm text-gray-700 hover:text-gray-900">Logout</button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Page Header -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Provincial Monitoring Dashboard</h2>
                            <p class="text-gray-600 mt-1">Real-time monitoring of climate, markets, and agricultural operations</p>
                        </div>
                        <button @click="refreshData()" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" :class="{'animate-spin': loading}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span x-text="loading ? 'Loading...' : 'Live'"></span>
                        </button>
                    </div>
                </div>

                <!-- Active Alerts Card -->
                <div class="mb-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Active Alerts</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                </div>
                                <span class="text-2xl font-bold text-gray-800" x-text="activeAlerts.length"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Climate & Weather Alerts -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Climate & Weather</h3>
                        </div>
                        <div class="p-6">
                            <!-- Climate Hazard Alerts -->
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Climate Hazard Alerts</h4>
                                <p class="text-xs text-gray-500 mb-4">Active weather warnings and advisories from PAGASA</p>
                                
                                <div class="space-y-3">
                                    <template x-for="alert in climateAlerts" :key="alert.id">
                                        <div class="alert-card border-l-4 rounded-lg p-4" 
                                             :class="{
                                                 'bg-red-50 border-red-500': alert.severity === 'high',
                                                 'bg-yellow-50 border-yellow-500': alert.severity === 'medium',
                                                 'bg-blue-50 border-blue-500': alert.severity === 'low'
                                             }">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-start space-x-3 flex-1">
                                                    <div class="p-2 rounded-lg"
                                                         :class="{
                                                             'bg-red-100': alert.severity === 'high',
                                                             'bg-yellow-100': alert.severity === 'medium',
                                                             'bg-blue-100': alert.severity === 'low'
                                                         }">
                                                        <svg class="w-5 h-5"
                                                             :class="{
                                                                 'text-red-600': alert.severity === 'high',
                                                                 'text-yellow-600': alert.severity === 'medium',
                                                                 'text-blue-600': alert.severity === 'low'
                                                             }"
                                                             fill="currentColor" viewBox="0 0 20 20">
                                                            <path x-show="alert.type === 'tropical_depression'" d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                                            <path x-show="alert.type === 'heavy_rainfall'" d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z"/>
                                                            <path x-show="alert.type === 'drought'" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h5 class="font-semibold text-gray-800" x-text="alert.title"></h5>
                                                        <p class="text-sm text-gray-600 mt-1" x-text="alert.time"></p>
                                                        <p class="text-sm text-gray-700 mt-2" x-text="alert.description"></p>
                                                        <div class="mt-3 flex flex-wrap gap-2">
                                                            <template x-for="location in alert.locations" :key="location">
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                                                                    </svg>
                                                                    <span x-text="location"></span>
                                                                </span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="ml-3 px-2 py-1 text-xs font-semibold rounded-full whitespace-nowrap"
                                                      :class="{
                                                          'bg-red-100 text-red-800': alert.severity === 'high',
                                                          'bg-yellow-100 text-yellow-800': alert.severity === 'medium',
                                                          'bg-blue-100 text-blue-800': alert.severity === 'low'
                                                      }"
                                                      x-text="alert.riskLabel"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- 7-Day Rainfall Forecast -->
                            <div class="mt-8">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">7-Day Rainfall Forecast</h4>
                                <div class="space-y-2">
                                    <template x-for="day in rainfallForecast" :key="day.day">
                                        <div class="flex items-center justify-between py-2">
                                            <span class="text-sm text-gray-700 w-24" x-text="day.day"></span>
                                            <div class="flex-1 mx-4">
                                                <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                                                    <div class="bg-blue-500 h-full rounded-full transition-all duration-500" 
                                                         :style="`width: ${(day.rainfall / 50) * 100}%`"></div>
                                                </div>
                                            </div>
                                            <div class="text-right w-20">
                                                <div class="text-sm font-semibold text-gray-800" x-text="day.rainfall + ' mm'"></div>
                                                <div class="text-xs text-gray-500" x-text="day.percentage"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Provincial Climate Status -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">Provincial Climate Status</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <template x-for="municipality in municipalities" :key="municipality.name">
                                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                                            </svg>
                                            <span class="font-medium text-gray-800" x-text="municipality.name"></span>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full"
                                              :class="{
                                                  'bg-green-100 text-green-800': municipality.status === 'Normal',
                                                  'bg-yellow-100 text-yellow-800': municipality.status === 'Watch',
                                                  'bg-blue-100 text-blue-800': municipality.status === 'Favorable'
                                              }"
                                              x-text="municipality.status"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function monitoringApp() {
            return {
                loading: false,
                searchQuery: '',
                activeAlerts: [],
                climateAlerts: [],
                rainfallForecast: [],
                municipalities: [],

                async init() {
                    await this.loadData();
                    // Auto-refresh every 5 minutes
                    setInterval(() => this.loadData(), 300000);
                },

                async loadData() {
                    this.loading = true;
                    try {
                        await Promise.all([
                            this.loadClimateAlerts(),
                            this.loadRainfallForecast(),
                            this.loadMunicipalityStatus()
                        ]);
                    } catch (error) {
                        console.error('Error loading monitoring data:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async loadClimateAlerts() {
                    try {
                        const response = await fetch('{{ route("admin.api.monitoring.alerts") }}', {
                            credentials: 'same-origin',
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        this.climateAlerts = data.alerts || [];
                        this.activeAlerts = this.climateAlerts.filter(a => a.severity === 'high' || a.severity === 'medium');
                    } catch (error) {
                        console.error('Error loading alerts:', error);
                    }
                },

                async loadRainfallForecast() {
                    try {
                        const response = await fetch('{{ route("admin.api.monitoring.rainfall") }}', {
                            credentials: 'same-origin',
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        this.rainfallForecast = data.forecast || [];
                    } catch (error) {
                        console.error('Error loading rainfall forecast:', error);
                    }
                },

                async loadMunicipalityStatus() {
                    try {
                        const response = await fetch('{{ route("admin.api.monitoring.municipalities") }}', {
                            credentials: 'same-origin',
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        this.municipalities = data.municipalities || [];
                    } catch (error) {
                        console.error('Error loading municipality status:', error);
                    }
                },

                async refreshData() {
                    await this.loadData();
                }
            }
        }
    </script>
</body>
</html>
