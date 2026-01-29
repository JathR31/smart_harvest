<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartHarvest Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/translation.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .insight-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 4px solid #22c55e;
        }
        .warning-card {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
        }
        .info-card {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="superadminDashboard()">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <span class="text-xl">🌱</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SmartHarvest</h1>
                        <p class="text-xs text-blue-200">Superadmin</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4" data-translate data-translate-id="sidebar-overview">OVERVIEW</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span data-translate data-translate-id="menu-admin-dashboard">Admin Dashboard</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4" data-translate data-translate-id="sidebar-user-mgmt">USER MANAGEMENT</p>
                    <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span data-translate data-translate-id="menu-users">Users</span>
                    </a>
                    <a href="{{ route('admin.roles') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <span data-translate data-translate-id="menu-roles">Roles & Permissions</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4" data-translate data-translate-id="sidebar-data-mgmt">DATA MANAGEMENT</p>
                    <a href="{{ route('admin.datasets') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                        </svg>
                        <span data-translate data-translate-id="menu-datasets">Datasets</span>
                    </a>
                    <a href="{{ route('admin.dataimport') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span data-translate data-translate-id="menu-dataimport">Data Import</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4" data-translate data-translate-id="sidebar-system">SYSTEM</p>
                    <a href="{{ route('admin.monitoring') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span data-translate data-translate-id="menu-monitoring">Monitoring</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center border-b">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-800" data-translate data-translate-id="header-title">SmartHarvest Admin</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Language Selector -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="px-3 py-2 border border-gray-300 rounded-lg flex items-center space-x-2 hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            <span class="text-sm">English</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 py-2 w-40 bg-white rounded-md shadow-xl border border-gray-200 z-50">
                            <button onclick="SmartHarvestTranslation.changeLanguage('en', 'English')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm">English</button>
                            <button onclick="SmartHarvestTranslation.changeLanguage('tl', 'Tagalog')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm">Tagalog</button>
                            <button onclick="SmartHarvestTranslation.changeLanguage('ilo', 'Ilocano')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm">Ilocano</button>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="relative p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="unreadCount > 0" class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" x-text="unreadCount"></span>
                        </button>
                        <!-- Notification dropdown content here -->
                    </div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <!-- Overview Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Total Users Card -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Users</p>
                                <h3 class="text-3xl font-bold text-gray-800" x-text="stats.totalUsers.toLocaleString()">0</h3>
                                <p class="text-sm text-green-600 mt-2">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        +<span x-text="stats.newUsersThisWeek"></span> this week
                                    </span>
                                </p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Data Records Card -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Data Records</p>
                                <h3 class="text-3xl font-bold text-gray-800" x-text="stats.dataRecords.toLocaleString()">0</h3>
                                <p class="text-sm text-green-600 mt-2">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        +<span x-text="stats.newRecordsThisWeek"></span> new
                                    </span>
                                </p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                                    <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                                    <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Actions Card -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Pending Actions</p>
                                <h3 class="text-3xl font-bold text-gray-800" x-text="stats.pendingActions">0</h3>
                                <p class="text-sm text-red-600 mt-2">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span x-text="stats.urgentActions"></span> urgent
                                    </span>
                                </p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yield & Planting Schedule Analysis Section -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-green-700">Yield & Planting Schedule Analysis</h3>
                        <p class="text-sm text-gray-500">Combined analysis showing optimal planting periods (May-June) based on historical yield data, rainfall patterns, and temperature trends</p>
                    </div>
                    
                    <!-- Chart Container -->
                    <div class="relative h-80 mb-6">
                        <canvas id="yieldAnalysisChart"></canvas>
                    </div>
                    
                    <!-- Chart Legend -->
                    <div class="flex flex-wrap justify-center gap-6 mb-6 text-sm">
                        <div class="flex items-center">
                            <span class="w-4 h-4 bg-green-500 rounded mr-2"></span>
                            <span class="text-gray-600">Actual Yield</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 bg-green-200 border border-green-400 rounded mr-2"></span>
                            <span class="text-gray-600">Optimal Planting Period</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 bg-orange-400 rounded mr-2"></span>
                            <span class="text-gray-600">Optimal Yield</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 bg-blue-400 rounded mr-2"></span>
                            <span class="text-gray-600">Rainfall</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 rounded mr-2" style="background: linear-gradient(90deg, #ef4444, #f97316);"></span>
                            <span class="text-gray-600">Temperature</span>
                        </div>
                    </div>
                    
                    <!-- Insights Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="insight-card p-4 rounded-lg">
                            <div class="flex items-start space-x-3">
                                <div class="bg-green-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-green-700">Peak Yield Period</h4>
                                    <p class="text-sm text-gray-600" x-text="yieldInsights.peakYieldPeriod">Loading...</p>
                                </div>
                            </div>
                        </div>
                        <div class="warning-card p-4 rounded-lg">
                            <div class="flex items-start space-x-3">
                                <div class="bg-yellow-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 3.636a1 1 0 010 1.414 7 7 0 000 9.9 1 1 0 11-1.414 1.414 9 9 0 010-12.728 1 1 0 011.414 0zm9.9 0a1 1 0 011.414 0 9 9 0 010 12.728 1 1 0 11-1.414-1.414 7 7 0 000-9.9 1 1 0 010-1.414zM7.879 6.464a1 1 0 010 1.414 3 3 0 000 4.243 1 1 0 11-1.415 1.414 5 5 0 010-7.07 1 1 0 011.415 0zm4.242 0a1 1 0 011.415 0 5 5 0 010 7.072 1 1 0 01-1.415-1.415 3 3 0 000-4.242 1 1 0 010-1.415z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-yellow-700">Rainfall Pattern</h4>
                                    <p class="text-sm text-gray-600" x-text="yieldInsights.rainfallPattern">Loading...</p>
                                </div>
                            </div>
                        </div>
                        <div class="info-card p-4 rounded-lg">
                            <div class="flex items-start space-x-3">
                                <div class="bg-blue-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-blue-700">Recommendation</h4>
                                    <p class="text-sm text-gray-600" x-text="yieldInsights.recommendation">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity, Data Validation, Crop Performance Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-green-700">Recent Activity</h3>
                        </div>
                        <div class="p-4 space-y-4 max-h-80 overflow-y-auto">
                            <template x-for="activity in recentActivity" :key="activity.id">
                                <div class="flex items-start space-x-3 pb-3 border-b border-gray-50 last:border-0">
                                    <div :class="{
                                        'bg-blue-100 text-blue-600': activity.type === 'user',
                                        'bg-green-100 text-green-600': activity.type === 'data_upload',
                                        'bg-yellow-100 text-yellow-600': activity.type === 'warning',
                                        'bg-red-100 text-red-600': activity.type === 'security'
                                    }" class="p-2 rounded-lg flex-shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-show="activity.type === 'user'">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        </svg>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-show="activity.type === 'data_upload'">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-show="activity.type === 'warning'">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-show="activity.type === 'security'">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800 truncate" x-text="activity.description"></p>
                                        <p class="text-xs text-gray-400" x-text="activity.time"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Data Validation Alerts -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-green-700">Data Validation Alerts</h3>
                        </div>
                        <div class="p-4">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-500 text-xs uppercase">
                                        <th class="pb-2">Record ID</th>
                                        <th class="pb-2">Issue</th>
                                        <th class="pb-2">Status</th>
                                        <th class="pb-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="alert in dataAlerts.slice(0, 5)" :key="alert.id">
                                        <tr class="border-b border-gray-50">
                                            <td class="py-2">
                                                <span class="text-xs text-gray-600" x-text="alert.recordId"></span>
                                            </td>
                                            <td class="py-2">
                                                <span class="text-xs text-gray-800" x-text="alert.issue.substring(0, 30) + '...'"></span>
                                            </td>
                                            <td class="py-2">
                                                <span :class="{
                                                    'bg-yellow-100 text-yellow-800': alert.status === 'Pending',
                                                    'bg-green-100 text-green-800': alert.status === 'Resolved'
                                                }" class="px-2 py-0.5 rounded-full text-xs font-medium" x-text="alert.status"></span>
                                            </td>
                                            <td class="py-2">
                                                <button class="text-blue-600 hover:text-blue-800 text-xs font-medium">Review</button>
                                                <button class="text-green-600 hover:text-green-800 text-xs font-medium ml-2">Resolve</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Crop Performance by Variety -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-green-700">Crop Performance by Variety (TOP 5)</h3>
                            <p class="text-xs text-gray-500">Average yield per hectare in <span x-text="currentYear"></span></p>
                        </div>
                        <div class="p-4 space-y-3">
                            <template x-for="(crop, index) in topCropVarieties" :key="index">
                                <div class="flex items-center">
                                    <div class="w-24 text-sm text-gray-700" x-text="crop.crop"></div>
                                    <div class="flex-1 mx-3">
                                        <div class="h-6 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500"
                                                 :class="getBarColor(index)"
                                                 :style="'width: ' + (crop.yieldPerHectare / maxYield * 100) + '%'">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-12 text-right text-sm font-semibold text-gray-800" x-text="crop.yieldPerHectare"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- System Overview Section -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-green-700 mb-6">System Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Server Status -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Server Status</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">API Response</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold" x-text="systemOverview.serverStatus.api">Normal</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Database</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold" x-text="systemOverview.serverStatus.database">Online</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Storage</span>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold" x-text="systemOverview.serverStatus.storage">78% used</span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Statistics -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Data Statistics</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Total Farms</span>
                                    <span class="text-sm font-semibold text-gray-800" x-text="systemOverview.dataStatistics.totalFarms.toLocaleString()">0</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Municipalities</span>
                                    <span class="text-sm font-semibold text-gray-800" x-text="systemOverview.dataStatistics.municipalities">0</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Crop Types</span>
                                    <span class="text-sm font-semibold text-gray-800" x-text="systemOverview.dataStatistics.cropTypes">0</span>
                                </div>
                            </div>
                        </div>

                        <!-- User Activity -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">User Activity</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Active Today</span>
                                    <span class="text-sm font-semibold text-gray-800" x-text="systemOverview.userActivity.activeToday">0</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">New This Week</span>
                                    <span class="text-sm font-semibold text-gray-800" x-text="systemOverview.userActivity.newThisWeek">0</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Admin Actions</span>
                                    <span class="text-sm font-semibold text-gray-800" x-text="systemOverview.userActivity.adminActions">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Users Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-green-700">Recent Users</h3>
                        <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Active</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <template x-for="user in recentUsers" :key="user.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <span class="text-green-700 font-semibold" x-text="user.name.charAt(0)"></span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900" x-text="user.name"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600" x-text="user.email"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getRoleBadgeClass(user.role)" class="px-2 py-1 rounded-full text-xs font-medium" x-text="user.role"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="user.last_active"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function superadminDashboard() {
            return {
                stats: {
                    totalUsers: 0,
                    newUsersThisWeek: 0,
                    dataRecords: 0,
                    newRecordsThisWeek: 0,
                    pendingActions: 0,
                    urgentActions: 0,
                    activeToday: 0
                },
                yieldInsights: {
                    peakYieldPeriod: 'Loading...',
                    rainfallPattern: 'Loading...',
                    recommendation: 'Loading...'
                },
                recentActivity: [],
                dataAlerts: [],
                topCropVarieties: [],
                maxYield: 15,
                currentYear: new Date().getFullYear(),
                systemOverview: {
                    serverStatus: { api: 'Normal', database: 'Online', storage: '78% used' },
                    dataStatistics: { totalFarms: 0, municipalities: 0, cropTypes: 0 },
                    userActivity: { activeToday: 0, newThisWeek: 0, adminActions: 0 }
                },
                recentUsers: [],
                unreadCount: 0,
                yieldChart: null,

                async init() {
                    await this.fetchDashboardData();
                    await this.fetchYieldAnalysis();
                    await this.fetchCropPerformance();
                    await this.fetchSystemOverview();
                    
                    // Auto-refresh every 30 seconds
                    setInterval(() => {
                        this.fetchDashboardData();
                    }, 30000);
                },

                async fetchDashboardData() {
                    try {
                        const response = await fetch('{{ route("admin.api.dashboard") }}');
                        const data = await response.json();
                        
                        this.stats = data.stats;
                        this.recentActivity = data.recentActivity || [];
                        this.dataAlerts = data.dataAlerts || [];
                        this.recentUsers = data.recentUsers || [];
                        this.unreadCount = data.unreadCount || 0;
                    } catch (error) {
                        console.error('Error fetching dashboard data:', error);
                    }
                },

                async fetchYieldAnalysis() {
                    try {
                        const response = await fetch('{{ route("admin.api.yield-analysis") }}');
                        const data = await response.json();
                        
                        if (data.chartData) {
                            this.renderYieldChart(data.chartData);
                        }
                        if (data.insights) {
                            this.yieldInsights = data.insights;
                        }
                    } catch (error) {
                        console.error('Error fetching yield analysis:', error);
                        this.generateFallbackChartData();
                    }
                },

                async fetchCropPerformance() {
                    try {
                        const response = await fetch('{{ route("admin.api.crop-performance") }}');
                        const data = await response.json();
                        
                        if (data.topVarieties) {
                            this.topCropVarieties = data.topVarieties;
                            this.maxYield = Math.max(...this.topCropVarieties.map(c => c.yieldPerHectare)) * 1.1;
                        }
                    } catch (error) {
                        console.error('Error fetching crop performance:', error);
                        this.topCropVarieties = [
                            { crop: 'Lettuce', variety: 'Romaine', yieldPerHectare: 4.2 },
                            { crop: 'Cabbage', variety: 'Scorpio', yieldPerHectare: 8.5 },
                            { crop: 'Carrots', variety: 'New Kuroda', yieldPerHectare: 5.8 },
                            { crop: 'White Potato', variety: 'Granola', yieldPerHectare: 12.3 },
                            { crop: 'Snap Beans', variety: 'Contender', yieldPerHectare: 6.1 }
                        ];
                        this.maxYield = 15;
                    }
                },

                async fetchSystemOverview() {
                    try {
                        const response = await fetch('{{ route("admin.api.system-overview") }}');
                        const data = await response.json();
                        
                        if (data.serverStatus) {
                            this.systemOverview.serverStatus = data.serverStatus;
                        }
                        if (data.dataStatistics) {
                            this.systemOverview.dataStatistics = data.dataStatistics;
                        }
                        if (data.userActivity) {
                            this.systemOverview.userActivity = data.userActivity;
                        }
                    } catch (error) {
                        console.error('Error fetching system overview:', error);
                    }
                },

                generateFallbackChartData() {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const chartData = months.map((month, i) => ({
                        month,
                        actualYield: [3, 4, 5, 6, 7, 8, 7.5, 7, 6, 5, 4, 3][i],
                        optimalYield: [4, 5, 6, 7, 8, 9, 8.5, 8, 7, 6, 5, 4][i],
                        rainfall: [100, 120, 150, 200, 250, 220, 180, 160, 140, 120, 110, 100][i],
                        temperature: [18, 19, 20, 21, 22, 23, 22, 21, 20, 19, 18, 17][i],
                        isOptimalPlanting: i >= 4 && i <= 6
                    }));
                    this.renderYieldChart(chartData);
                    this.yieldInsights = {
                        peakYieldPeriod: 'May-June shows highest yields (5-8 MT/ha). Warm rainfall and temperatures are optimal.',
                        rainfallPattern: 'Moderate rainfall (120-250mm) during May-June supports optimal crop growth and development.',
                        recommendation: 'Plant between May 15 - June 15 to achieve yields matching or exceeding optimal benchmarks.'
                    };
                },

                renderYieldChart(chartData) {
                    const ctx = document.getElementById('yieldAnalysisChart').getContext('2d');
                    
                    if (this.yieldChart) {
                        this.yieldChart.destroy();
                    }
                    
                    const labels = chartData.map(d => d.month);
                    const actualYield = chartData.map(d => d.actualYield);
                    const optimalYield = chartData.map(d => d.optimalYield);
                    const rainfall = chartData.map(d => d.rainfall);
                    const temperature = chartData.map(d => d.temperature);
                    
                    // Highlight optimal planting months with background
                    const optimalBackground = chartData.map(d => d.isOptimalPlanting ? 'rgba(34, 197, 94, 0.2)' : 'rgba(34, 197, 94, 0.05)');
                    
                    this.yieldChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Actual Yield (MT/ha)',
                                    data: actualYield,
                                    backgroundColor: optimalBackground,
                                    borderColor: '#22c55e',
                                    borderWidth: 2,
                                    type: 'bar',
                                    yAxisID: 'y'
                                },
                                {
                                    label: 'Optimal Yield (MT/ha)',
                                    data: optimalYield,
                                    borderColor: '#f97316',
                                    backgroundColor: 'transparent',
                                    borderWidth: 2,
                                    type: 'line',
                                    tension: 0.4,
                                    yAxisID: 'y'
                                },
                                {
                                    label: 'Rainfall (mm)',
                                    data: rainfall,
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    borderWidth: 2,
                                    type: 'line',
                                    tension: 0.4,
                                    yAxisID: 'y1',
                                    hidden: true
                                },
                                {
                                    label: 'Temperature (°C)',
                                    data: temperature,
                                    borderColor: '#ef4444',
                                    backgroundColor: 'transparent',
                                    borderWidth: 2,
                                    type: 'line',
                                    tension: 0.4,
                                    yAxisID: 'y2'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed.y !== null) {
                                                label += context.parsed.y.toFixed(1);
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    title: {
                                        display: true,
                                        text: 'Yield (MT/ha)'
                                    },
                                    min: 0,
                                    max: 10
                                },
                                y1: {
                                    type: 'linear',
                                    display: false,
                                    position: 'right',
                                    min: 0,
                                    max: 300,
                                    grid: {
                                        drawOnChartArea: false
                                    }
                                },
                                y2: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: 'Temp (°C)'
                                    },
                                    min: 10,
                                    max: 30,
                                    grid: {
                                        drawOnChartArea: false
                                    }
                                }
                            }
                        }
                    });
                },

                getBarColor(index) {
                    const colors = ['bg-green-300', 'bg-green-400', 'bg-green-500', 'bg-green-600', 'bg-green-700'];
                    return colors[index] || 'bg-green-500';
                },

                getRoleBadgeClass(role) {
                    const classes = {
                        'Farmer': 'bg-green-100 text-green-800',
                        'Admin': 'bg-purple-100 text-purple-800',
                        'Field Agent': 'bg-blue-100 text-blue-800',
                        'Researcher': 'bg-indigo-100 text-indigo-800',
                        'Regional Manager': 'bg-orange-100 text-orange-800',
                        'Extension Officer': 'bg-teal-100 text-teal-800'
                    };
                    return classes[role] || 'bg-gray-100 text-gray-800';
                }
            }
        }
    </script>
</body>
</html>
