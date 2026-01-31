<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DA Officer Dashboard - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item:hover { background: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background: rgba(255, 255, 255, 0.2); border-left: 3px solid white; }
        .stat-card { transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50" x-data="daOfficerDashboard()" x-init="init()">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Green theme for DA Officers -->
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

            <nav class="p-4 space-y-1">
                <!-- Overview Section -->
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Overview</p>
                    <a href="#" @click.prevent="currentTab = 'dashboard'" 
                       :class="currentTab === 'dashboard' ? 'active bg-green-600' : ''"
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Data Management Section -->
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Data Management</p>
                    <a href="#" @click.prevent="currentTab = 'datasets'" 
                       :class="currentTab === 'datasets' ? 'active bg-green-600' : ''"
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                        <span>Datasets</span>
                    </a>
                    <a href="#" @click.prevent="currentTab = 'dataimport'" 
                       :class="currentTab === 'dataimport' ? 'active bg-green-600' : ''"
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span>Data Import</span>
                    </a>
                    <a href="#" @click.prevent="currentTab = 'marketprices'" 
                       :class="currentTab === 'marketprices' ? 'active bg-green-600' : ''"
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Market Prices</span>
                    </a>
                </div>

                <!-- Communication Section -->
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Communication</p>
                    <a href="#" @click.prevent="currentTab = 'announcements'" 
                       :class="currentTab === 'announcements' ? 'active bg-green-600' : ''"
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Announcements</span>
                    </a>
                    <a href="#" @click.prevent="currentTab = 'inbox'" 
                       :class="currentTab === 'inbox' ? 'active bg-green-600' : ''"
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Inbox</span>
                        <span x-show="unreadMessages > 0" class="absolute right-4 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" x-text="unreadMessages"></span>
                    </a>
                </div>

                <!-- Monitoring Section -->
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Monitoring</p>
                    <a href="#" @click.prevent="currentTab = 'farmerview'" 
                       :class="currentTab === 'farmerview' ? 'active bg-green-600' : ''"
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Farmer's View</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-800">DA Officer Dashboard</h2>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">🌾 DA OFFICER</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- User Menu -->
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="text-sm text-gray-700">{{ Auth::user()->name ?? 'DA Officer' }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 px-3 py-2 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="text-sm">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Dashboard Tab -->
                <div x-show="currentTab === 'dashboard'">
                    <!-- Header with Municipality Filter -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>
                            <p class="text-sm text-gray-500">Real-time agricultural data for Benguet Province</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <label class="text-sm font-medium text-gray-600">Municipality:</label>
                            <select x-model="selectedMunicipality" @change="loadDashboardData()" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">All Municipalities</option>
                                <template x-for="muni in municipalities" :key="muni">
                                    <option :value="muni" x-text="muni"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">Registered Farmers</p>
                                    <p class="text-2xl font-bold text-gray-800" x-text="stats.totalFarmers.toLocaleString()">0</p>
                                    <p class="text-xs text-green-600" x-text="'+' + stats.newFarmersThisMonth + ' this month'"></p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-2xl">👨‍🌾</span>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">Data Records</p>
                                    <p class="text-2xl font-bold text-gray-800" x-text="stats.totalRecords.toLocaleString()">0</p>
                                    <p class="text-xs text-green-600" x-text="'+' + stats.newRecordsThisMonth + ' new'"></p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-2xl">📊</span>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">Total Farm Area</p>
                                    <p class="text-2xl font-bold text-gray-800" x-text="stats.totalFarmArea.toLocaleString() + ' ha'">0 ha</p>
                                    <p class="text-xs text-gray-500" x-text="selectedMunicipality || 'Across 13 municipalities'"></p>
                                </div>
                                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <span class="text-2xl">🌾</span>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">Pending Validation</p>
                                    <p class="text-2xl font-bold text-gray-800" x-text="stats.pendingValidation">0</p>
                                    <p class="text-xs text-red-600" x-text="stats.flaggedRecords + ' flagged'"></p>
                                </div>
                                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                    <span class="text-2xl">⚠️</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yield & Planting Schedule Analysis -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-green-700">Yield & Planting Schedule Analysis</h3>
                                <p class="text-sm text-gray-500">Combined analysis showing optimal planting periods (May-June) based on historical yield data, rainfall patterns, and temperature trends</p>
                            </div>
                        </div>
                        <div class="h-80 relative">
                            <canvas id="yieldAnalysisChart"></canvas>
                        </div>
                        <div class="flex items-center justify-center space-x-6 mt-4 text-sm">
                            <div class="flex items-center"><span class="w-4 h-4 bg-green-500 rounded mr-2"></span> Actual Yield</div>
                            <div class="flex items-center"><span class="w-4 h-4 bg-green-200 rounded mr-2"></span> Optimal Planting Period</div>
                            <div class="flex items-center"><span class="w-4 h-2 bg-green-600 mr-2"></span> Optimal Yield</div>
                            <div class="flex items-center"><span class="w-4 h-2 bg-blue-500 mr-2"></span> Rainfall</div>
                            <div class="flex items-center"><span class="w-4 h-2 bg-orange-500 mr-2"></span> Temperature</div>
                        </div>
                        <!-- Insights Row -->
                        <div class="grid grid-cols-3 gap-4 mt-6">
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                                <div class="flex items-center space-x-2 text-green-700 font-semibold mb-1">
                                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                                    <span>Peak Yield Period</span>
                                </div>
                                <p class="text-sm text-gray-600">Highest yields achieved during May-June planting window</p>
                            </div>
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                                <div class="flex items-center space-x-2 text-blue-700 font-semibold mb-1">
                                    <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                                    <span>Rainfall Pattern</span>
                                </div>
                                <p class="text-sm text-gray-600">Moderate rainfall (150-200mm) supports optimal growth</p>
                            </div>
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                                <div class="flex items-center space-x-2 text-yellow-700 font-semibold mb-1">
                                    <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                                    <span>Recommendation</span>
                                </div>
                                <p class="text-sm text-gray-600">Plant between May 15 - June 10 for best results</p>
                            </div>
                        </div>
                    </div>

                    <!-- Top 5 Recommended Crops -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-green-700">Top 5 Recommended Crops</h2>
                            <span class="text-sm text-gray-500">Based on ML analysis of yield data</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <template x-for="(crop, index) in topCrops.slice(0, 5)" :key="index">
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition" :class="index === 0 ? 'border-green-500 bg-green-50' : ''">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full" 
                                              :class="index === 0 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700'"
                                              x-text="'#' + (index + 1)">
                                        </span>
                                        <span class="text-xs text-gray-500" x-text="crop.variety || 'Mixed'"></span>
                                    </div>
                                    <h4 class="font-semibold text-gray-800 mb-2" x-text="crop.crop"></h4>
                                    <div class="space-y-1 text-xs text-gray-600">
                                        <div class="flex justify-between">
                                            <span>Yield:</span>
                                            <span class="font-semibold text-green-600" x-text="crop.yield_prediction || crop.historical_yield"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Plant:</span>
                                            <span class="font-semibold" x-text="crop.optimal_planting"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Harvest:</span>
                                            <span class="font-semibold" x-text="crop.expected_harvest"></span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <span class="text-xs px-2 py-1 rounded" 
                                              :class="crop.status === 'Recommended' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                              x-text="crop.status">
                                        </span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="topCrops.length === 0">
                                <div class="col-span-5 text-center py-8 text-gray-500">
                                    <p>Loading crop recommendations...</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 7-Day Weather Forecast -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h2 class="text-lg font-semibold text-green-700 mb-6">7-Day Weather Forecast</h2>
                        <div class="grid grid-cols-7 gap-3">
                            <template x-for="(day, index) in weatherForecast.slice(0, 7)" :key="index">
                                <div class="text-center p-3 rounded-lg hover:bg-gray-50 transition">
                                    <p class="text-xs text-gray-600 font-medium mb-2" x-text="day.dayName"></p>
                                    <div class="my-3 text-3xl" x-text="getWeatherEmoji(day.description)"></div>
                                    <p class="text-xl font-bold text-gray-800" x-text="Math.round(day.temp) + '°'"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Best Time to Plant & Weather Outlook -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-green-700 mb-4">Best Time to Plant</h3>
                            <p class="text-sm text-gray-600 font-medium mb-2">May 20 - June 10, 2026</p>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Based on 5-6 years of past yield data and local climate data, the best planting window is between 
                                <span class="font-semibold">May 20 and June 10</span>. 
                                Planting within this period helps farmers take advantage of ideal temperature and rainfall patterns.
                            </p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-green-700 mb-4">Weather Outlook</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Expect moderate rainfall and mild temperatures in the coming weeks. These conditions support healthy crop growth, 
                                but farmers should delay planting after heavy rain (4+ cm daily) to avoid waterlogging issues.
                            </p>
                        </div>
                    </div>

                    <!-- Data Validation Alerts -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Validation Alerts</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Record ID</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Issue</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Status</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4 text-sm">#REC-2024-0891</td>
                                        <td class="py-3 px-4 text-sm">Missing yield data for Q3 2025</td>
                                        <td class="py-3 px-4"><span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Pending</span></td>
                                        <td class="py-3 px-4">
                                            <button class="text-blue-600 hover:text-blue-800 text-sm mr-2">Review</button>
                                            <button class="text-green-600 hover:text-green-800 text-sm">Resolve</button>
                                        </td>
                                    </tr>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4 text-sm">#REC-2024-0892</td>
                                        <td class="py-3 px-4 text-sm">Duplicate entry detected - Farm ID: F-1234</td>
                                        <td class="py-3 px-4"><span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Urgent</span></td>
                                        <td class="py-3 px-4">
                                            <button class="text-blue-600 hover:text-blue-800 text-sm mr-2">Review</button>
                                            <button class="text-green-600 hover:text-green-800 text-sm">Resolve</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Market Prices Tab -->
                <div x-show="currentTab === 'marketprices'" x-cloak>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Market Prices Management</h2>
                            <p class="text-gray-500 text-sm">Set and update prices for Cordillera crops</p>
                        </div>
                        <button @click="showAddPriceModal = true" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Add New Price</span>
                        </button>
                    </div>

                    <!-- Price Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <template x-for="price in marketPrices" :key="price.id">
                            <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-3xl" x-text="getCropEmoji(price.crop_name)">🌱</span>
                                        <div>
                                            <h4 class="font-bold text-gray-800" x-text="price.crop_name"></h4>
                                            <p class="text-xs text-gray-500" x-text="price.variety || 'Standard'"></p>
                                        </div>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full" 
                                          :class="price.price_trend === 'up' ? 'bg-green-100 text-green-700' : price.price_trend === 'down' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700'"
                                          x-text="price.price_trend === 'up' ? '↑ Up' : price.price_trend === 'down' ? '↓ Down' : '→ Stable'">
                                    </span>
                                </div>
                                <template x-if="price.price_per_kg">
                                    <div>
                                        <p class="text-3xl font-bold text-green-600 mb-2">₱<span x-text="parseFloat(price.price_per_kg).toFixed(2)"></span><span class="text-sm text-gray-500 font-normal">/kg</span></p>
                                        <div class="flex items-center justify-between text-xs mb-3">
                                            <span class="px-2 py-1 rounded" 
                                                  :class="price.demand_level === 'high' || price.demand_level === 'very_high' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'">
                                                Demand: <span class="font-semibold capitalize" x-text="price.demand_level?.replace('_', ' ') || 'Moderate'"></span>
                                            </span>
                                            <span class="text-gray-400" x-text="'Updated: ' + (price.price_date || 'Today')"></span>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!price.price_per_kg">
                                    <div class="mb-3">
                                        <p class="text-xl font-semibold text-gray-400 mb-2">Price Not Set</p>
                                        <p class="text-xs text-gray-500">Click below to enter price</p>
                                    </div>
                                </template>
                                <div class="flex space-x-2">
                                    <button @click="editPrice(price)" class="flex-1 px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 flex items-center justify-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span x-text="price.price_per_kg ? 'Update Price' : 'Enter Price'"></span>
                                    </button>
                                    <button @click="deletePrice(price.id)" class="px-3 py-2 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Price Insights -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h3 class="text-base font-semibold text-blue-900 mb-2">💡 DA Officer Tips</h3>
                                <p class="text-sm text-blue-800 leading-relaxed">
                                    Update prices regularly based on La Trinidad Trading Post and Baguio City Public Market data. 
                                    Farmers rely on these prices for planning. Consider demand levels when adjusting prices.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Add Price Modal -->
                    <div x-show="showAddPriceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                        <div class="bg-white rounded-xl p-6 w-full max-w-lg">
                            <h3 class="text-lg font-semibold mb-4">Add Market Price</h3>
                            <form @submit.prevent="savePrice()">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Crop Name</label>
                                        <select x-model="newPrice.crop_name" class="w-full border rounded-lg px-3 py-2" required>
                                            <option value="">Select crop</option>
                                            <option value="CABBAGE">Cabbage</option>
                                            <option value="CHINESE CABBAGE">Chinese Cabbage</option>
                                            <option value="LETTUCE">Lettuce</option>
                                            <option value="CAULIFLOWER">Cauliflower</option>
                                            <option value="BROCCOLI">Broccoli</option>
                                            <option value="SNAP BEANS">Snap Beans</option>
                                            <option value="GARDEN PEAS">Garden Peas</option>
                                            <option value="SWEET PEPPER">Sweet Pepper</option>
                                            <option value="WHITE POTATO">White Potato</option>
                                            <option value="CARROTS">Carrots</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Variety</label>
                                        <input type="text" x-model="newPrice.variety" class="w-full border rounded-lg px-3 py-2" placeholder="Optional">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Price per kg (₱)</label>
                                        <input type="number" step="0.01" x-model="newPrice.price_per_kg" class="w-full border rounded-lg px-3 py-2" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Trend</label>
                                        <select x-model="newPrice.price_trend" class="w-full border rounded-lg px-3 py-2">
                                            <option value="stable">Stable</option>
                                            <option value="up">Up</option>
                                            <option value="down">Down</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Demand Level</label>
                                        <select x-model="newPrice.demand_level" class="w-full border rounded-lg px-3 py-2">
                                            <option value="low">Low</option>
                                            <option value="moderate">Moderate</option>
                                            <option value="high">High</option>
                                            <option value="very_high">Very High</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Date</label>
                                        <input type="date" x-model="newPrice.price_date" class="w-full border rounded-lg px-3 py-2" required>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea x-model="newPrice.notes" class="w-full border rounded-lg px-3 py-2" rows="2"></textarea>
                                </div>
                                <div class="flex justify-end space-x-3 mt-6">
                                    <button type="button" @click="showAddPriceModal = false" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save Price</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Announcements Tab -->
                <div x-show="currentTab === 'announcements'" x-cloak>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Announcements</h3>
                            <button @click="showAddAnnouncementModal = true" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>New Announcement</span>
                            </button>
                        </div>

                        <!-- Announcements List -->
                        <div class="space-y-4">
                            <template x-for="announcement in announcements" :key="announcement.id">
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-start space-x-3">
                                            <span class="text-2xl" x-text="announcement.type === 'urgent' ? '🚨' : (announcement.type === 'weather' ? '🌤️' : (announcement.type === 'market' ? '📈' : '📢'))"></span>
                                            <div>
                                                <h4 class="font-semibold text-gray-800" x-text="announcement.title"></h4>
                                                <p class="text-sm text-gray-600 mt-1" x-text="announcement.content"></p>
                                                <p class="text-xs text-gray-400 mt-2" x-text="'Posted: ' + announcement.published_at"></p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button @click="editAnnouncement(announcement)" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                                            <button @click="deleteAnnouncement(announcement.id)" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Add Announcement Modal -->
                    <div x-show="showAddAnnouncementModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                        <div class="bg-white rounded-xl p-6 w-full max-w-lg">
                            <h3 class="text-lg font-semibold mb-4">Create Announcement</h3>
                            <form @submit.prevent="saveAnnouncement()">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                        <input type="text" x-model="newAnnouncement.title" class="w-full border rounded-lg px-3 py-2" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                        <textarea x-model="newAnnouncement.content" class="w-full border rounded-lg px-3 py-2" rows="4" required></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                            <select x-model="newAnnouncement.type" class="w-full border rounded-lg px-3 py-2">
                                                <option value="general">General</option>
                                                <option value="weather">Weather Advisory</option>
                                                <option value="market">Market Update</option>
                                                <option value="advisory">Advisory</option>
                                                <option value="urgent">Urgent</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                            <select x-model="newAnnouncement.priority" class="w-full border rounded-lg px-3 py-2">
                                                <option value="low">Low</option>
                                                <option value="normal">Normal</option>
                                                <option value="high">High</option>
                                                <option value="urgent">Urgent</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-3 mt-6">
                                    <button type="button" @click="showAddAnnouncementModal = false" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Publish</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Inbox Tab -->
                <div x-show="currentTab === 'inbox'" x-cloak>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Inbox</h3>
                            <div class="flex space-x-2">
                                <button @click="inboxView = 'received'" :class="inboxView === 'received' ? 'bg-green-600 text-white' : 'bg-gray-100'" class="px-4 py-2 rounded-lg">Received</button>
                                <button @click="inboxView = 'sent'" :class="inboxView === 'sent' ? 'bg-green-600 text-white' : 'bg-gray-100'" class="px-4 py-2 rounded-lg">Sent</button>
                            </div>
                        </div>

                        <!-- Messages List -->
                        <div class="space-y-3">
                            <template x-for="message in (inboxView === 'received' ? receivedMessages : sentMessages)" :key="message.id">
                                <div @click="viewMessage(message)" class="border rounded-lg p-4 cursor-pointer hover:shadow-md transition" :class="!message.is_read && inboxView === 'received' ? 'bg-green-50 border-green-200' : ''">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <span class="font-semibold text-gray-800" x-text="inboxView === 'received' ? message.sender_name : 'To: ' + message.receiver_name"></span>
                                                <span x-show="!message.is_read && inboxView === 'received'" class="bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">New</span>
                                            </div>
                                            <p class="font-medium text-gray-700 mt-1" x-text="message.subject"></p>
                                            <p class="text-sm text-gray-500 mt-1 line-clamp-1" x-text="message.content"></p>
                                        </div>
                                        <span class="text-xs text-gray-400" x-text="message.created_at"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- View Message Modal -->
                    <div x-show="showMessageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                        <div class="bg-white rounded-xl p-6 w-full max-w-lg">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold" x-text="selectedMessage?.subject"></h3>
                                    <p class="text-sm text-gray-500" x-text="'From: ' + selectedMessage?.sender_name"></p>
                                </div>
                                <button @click="showMessageModal = false" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="border-t pt-4">
                                <p class="text-gray-700 whitespace-pre-wrap" x-text="selectedMessage?.content"></p>
                            </div>
                            <div class="flex justify-end space-x-3 mt-6">
                                <button @click="replyToMessage(selectedMessage)" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Reply</button>
                                <button @click="showMessageModal = false" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datasets Tab -->
                <div x-show="currentTab === 'datasets'" x-cloak>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Datasets Management</h3>
                            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Upload Dataset</span>
                            </button>
                        </div>
                        <div class="border rounded-lg p-8 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                            <p class="text-gray-500 mb-2">Manage your agricultural datasets here</p>
                            <p class="text-sm text-gray-400">Upload CSV files or view existing data records</p>
                        </div>
                    </div>
                </div>

                <!-- Data Import Tab -->
                <div x-show="currentTab === 'dataimport'" x-cloak>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Data Import</h3>
                        </div>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-green-500 transition cursor-pointer">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-gray-600 font-medium mb-2">Drop your CSV file here</p>
                            <p class="text-sm text-gray-400 mb-4">or click to browse</p>
                            <input type="file" accept=".csv,.xlsx" class="hidden" id="fileInput">
                            <button onclick="document.getElementById('fileInput').click()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Select File
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Farmer's View Tab -->
                <div x-show="currentTab === 'farmerview'" x-cloak x-init="$watch('currentTab', val => { if(val === 'farmerview') redirectToFarmerView() })">
                    <div class="flex items-center justify-center min-h-[60vh]">
                        <div class="text-center">
                            <div class="mb-6">
                                <svg class="animate-spin h-16 w-16 text-green-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Switching to Farmer View</h3>
                            <p class="text-gray-500">Please wait while we load the farmer dashboard...</p>
                            <div class="mt-4 flex items-center justify-center space-x-1">
                                <span class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                                <span class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                                <span class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function redirectToFarmerView() {
            setTimeout(() => {
                window.location.href = '{{ route("dashboard") }}?view=farmer';
            }, 1500);
        }
        
        function daOfficerDashboard() {
            return {
                currentTab: 'dashboard',
                loading: false,
                unreadMessages: 0,
                inboxView: 'received',
                showAddPriceModal: false,
                showAddAnnouncementModal: false,
                showMessageModal: false,
                selectedMessage: null,
                selectedMunicipality: '',
                
                municipalities: [
                    'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
                    'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 
                    'Mankayan', 'Sablan', 'Tuba', 'Tublay'
                ],
                
                stats: {
                    totalFarmers: 0,
                    totalRecords: 0,
                    activePrices: 0,
                    totalFarmArea: 0,
                    pendingValidation: 0,
                    flaggedRecords: 0,
                    newFarmersThisMonth: 0,
                    newRecordsThisMonth: 0
                },
                
                recentActivity: [],
                marketPrices: [],
                announcements: [],
                receivedMessages: [],
                sentMessages: [],
                topCrops: [],
                weatherForecast: [],
                
                newPrice: {
                    crop_name: '',
                    variety: '',
                    price_per_kg: '',
                    price_trend: 'stable',
                    demand_level: 'moderate',
                    price_date: new Date().toISOString().split('T')[0],
                    notes: ''
                },
                
                newAnnouncement: {
                    title: '',
                    content: '',
                    type: 'general',
                    priority: 'normal'
                },
                
                async init() {
                    this.loading = true;
                    try {
                        await Promise.all([
                            this.loadDashboardData(),
                            this.loadMarketPrices(),
                            this.loadAnnouncements(),
                            this.loadMessages(),
                            this.loadTopCrops(),
                            this.loadWeatherForecast()
                        ]);
                    } catch (error) {
                        console.error('Error initializing dashboard:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async loadDashboardData() {
                    try {
                        const url = this.selectedMunicipality 
                            ? `/api/da-officer/dashboard?municipality=${encodeURIComponent(this.selectedMunicipality)}`
                            : '/api/da-officer/dashboard';
                        const response = await fetch(url);
                        if (response.ok) {
                            const data = await response.json();
                            this.stats = {
                                totalFarmers: data.stats?.totalFarmers || 0,
                                totalRecords: data.stats?.totalRecords || 0,
                                activePrices: data.stats?.activePrices || 0,
                                totalFarmArea: data.stats?.totalFarmArea || 0,
                                pendingValidation: data.stats?.pendingValidation || 0,
                                flaggedRecords: data.stats?.flaggedRecords || 0,
                                newFarmersThisMonth: data.stats?.newFarmersThisMonth || 0,
                                newRecordsThisMonth: data.stats?.newRecordsThisMonth || 0
                            };
                            this.recentActivity = data.recentActivity || [];
                        }
                    } catch (error) {
                        console.error('Error loading dashboard:', error);
                        // Stats will remain at 0 if API fails
                    }
                },
                
                async loadMarketPrices() {
                    try {
                        const response = await fetch('/api/market-prices');
                        if (response.ok) {
                            const data = await response.json();
                            // Ensure we have the 10 ML crops
                            this.marketPrices = data.length > 0 ? data : this.getDefaultCrops();
                        } else {
                            this.marketPrices = this.getDefaultCrops();
                        }
                    } catch (error) {
                        console.error('Error loading prices:', error);
                        this.marketPrices = this.getDefaultCrops();
                    }
                },
                
                getDefaultCrops() {
                    return [
                        { id: 1, crop_name: 'CABBAGE', variety: 'Scorpio', price_per_kg: 45.00, price_trend: 'up', demand_level: 'high', price_date: 'Today' },
                        { id: 2, crop_name: 'CHINESE CABBAGE', variety: 'Vitara', price_per_kg: 38.00, price_trend: 'stable', demand_level: 'moderate', price_date: 'Today' },
                        { id: 3, crop_name: 'LETTUCE', variety: 'Iceberg', price_per_kg: 85.00, price_trend: 'up', demand_level: 'high', price_date: 'Today' },
                        { id: 4, crop_name: 'CAULIFLOWER', variety: 'Snow Crown', price_per_kg: 95.00, price_trend: 'stable', demand_level: 'moderate', price_date: 'Today' },
                        { id: 5, crop_name: 'BROCCOLI', variety: 'Marathon', price_per_kg: 120.00, price_trend: 'up', demand_level: 'very_high', price_date: 'Today' },
                        { id: 6, crop_name: 'SNAP BEANS', variety: 'Blue Lake', price_per_kg: 75.00, price_trend: 'down', demand_level: 'moderate', price_date: 'Today' },
                        { id: 7, crop_name: 'GARDEN PEAS', variety: 'Sugar Snap', price_per_kg: 150.00, price_trend: 'up', demand_level: 'high', price_date: 'Today' },
                        { id: 8, crop_name: 'SWEET PEPPER', variety: 'California Wonder', price_per_kg: 180.00, price_trend: 'stable', demand_level: 'high', price_date: 'Today' },
                        { id: 9, crop_name: 'WHITE POTATO', variety: 'Granola', price_per_kg: 55.00, price_trend: 'down', demand_level: 'high', price_date: 'Today' },
                        { id: 10, crop_name: 'CARROTS', variety: 'New Kuroda', price_per_kg: 65.00, price_trend: 'stable', demand_level: 'moderate', price_date: 'Today' }
                    ];
                },
                
                async loadTopCrops() {
                    try {
                        const response = await fetch('/api/yield-analysis');
                        if (response.ok) {
                            const data = await response.json();
                            this.topCrops = data.crops || this.getDefaultTopCrops();
                        } else {
                            this.topCrops = this.getDefaultTopCrops();
                        }
                    } catch (error) {
                        console.error('Error loading top crops:', error);
                        this.topCrops = this.getDefaultTopCrops();
                    }
                },
                
                getDefaultTopCrops() {
                    return [
                        { crop: 'Cabbage', variety: 'Scorpio', yield_prediction: '5.8 tons/ha', optimal_planting: 'May 15-30', expected_harvest: 'Aug 15-30', status: 'Recommended' },
                        { crop: 'Carrots', variety: 'New Kuroda', yield_prediction: '4.5 tons/ha', optimal_planting: 'May 20-Jun 5', expected_harvest: 'Sep 1-15', status: 'Recommended' },
                        { crop: 'White Potato', variety: 'Granola', yield_prediction: '6.2 tons/ha', optimal_planting: 'Jun 1-15', expected_harvest: 'Sep 15-30', status: 'Recommended' },
                        { crop: 'Lettuce', variety: 'Iceberg', yield_prediction: '3.8 tons/ha', optimal_planting: 'May 25-Jun 10', expected_harvest: 'Jul 20-Aug 5', status: 'Good' },
                        { crop: 'Broccoli', variety: 'Marathon', yield_prediction: '4.2 tons/ha', optimal_planting: 'Jun 1-20', expected_harvest: 'Aug 25-Sep 10', status: 'Good' }
                    ];
                },
                
                async loadWeatherForecast() {
                    try {
                        const response = await fetch('/api/weather-forecast');
                        if (response.ok) {
                            const data = await response.json();
                            this.weatherForecast = data.forecast || this.getDefaultWeatherForecast();
                        } else {
                            this.weatherForecast = this.getDefaultWeatherForecast();
                        }
                    } catch (error) {
                        console.error('Error loading weather:', error);
                        this.weatherForecast = this.getDefaultWeatherForecast();
                    }
                },
                
                getDefaultWeatherForecast() {
                    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    const today = new Date();
                    return Array.from({ length: 7 }, (_, i) => {
                        const date = new Date(today);
                        date.setDate(today.getDate() + i);
                        return {
                            dayName: days[date.getDay()],
                            temp: 18 + Math.floor(Math.random() * 8),
                            description: ['Sunny', 'Partly Cloudy', 'Cloudy', 'Light Rain', 'Sunny'][Math.floor(Math.random() * 5)],
                            icon: ['01d', '02d', '03d', '10d', '01d'][Math.floor(Math.random() * 5)]
                        };
                    });
                },
                
                getWeatherEmoji(description) {
                    const desc = (description || '').toLowerCase();
                    if (desc.includes('rain') || desc.includes('shower')) return '🌧️';
                    if (desc.includes('cloud')) return '☁️';
                    if (desc.includes('partly')) return '⛅';
                    if (desc.includes('thunder') || desc.includes('storm')) return '⛈️';
                    return '☀️';
                },
                
                async loadAnnouncements() {
                    try {
                        const response = await fetch('/api/announcements');
                        if (response.ok) {
                            this.announcements = await response.json();
                        }
                    } catch (error) {
                        console.error('Error loading announcements:', error);
                        // Fallback mock data
                        this.announcements = [
                            { id: 1, title: 'Planting Season Advisory', content: 'Optimal planting window for highland vegetables is now open.', type: 'advisory', priority: 'high', created_at: 'Jan 30, 2026' }
                        ];
                    }
                },
                
                async loadMessages() {
                    try {
                        const response = await fetch('/api/messages');
                        if (response.ok) {
                            const data = await response.json();
                            this.receivedMessages = data.received || [];
                            this.sentMessages = data.sent || [];
                            this.unreadMessages = this.receivedMessages.filter(m => !m.is_read).length;
                        }
                    } catch (error) {
                        console.error('Error loading messages:', error);
                        // Mock data
                        this.receivedMessages = [
                            { id: 1, sender_name: 'Juan Dela Cruz', subject: 'Question about planting schedule', content: 'Good day! I would like to ask about the recommended planting schedule for cabbage this season.', is_read: false, created_at: '2 hours ago' },
                            { id: 2, sender_name: 'Maria Santos', subject: 'Market price inquiry', content: 'Hello, I noticed the potato prices changed. Can you confirm the current price?', is_read: true, created_at: '1 day ago' }
                        ];
                        this.sentMessages = [
                            { id: 3, receiver_name: 'Juan Dela Cruz', subject: 'Re: Question about planting schedule', content: 'Good day! The recommended planting window for cabbage is...', is_read: true, created_at: '1 hour ago' }
                        ];
                        this.unreadMessages = 1;
                    }
                },
                
                async savePrice() {
                    try {
                        const response = await fetch('/api/market-prices', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.newPrice)
                        });
                        
                        if (response.ok) {
                            await this.loadMarketPrices();
                            this.showAddPriceModal = false;
                            this.resetNewPrice();
                            alert('Price saved successfully!');
                        }
                    } catch (error) {
                        console.error('Error saving price:', error);
                        // For demo, just add to local array
                        this.marketPrices.unshift({
                            id: Date.now(),
                            ...this.newPrice
                        });
                        this.showAddPriceModal = false;
                        this.resetNewPrice();
                    }
                },
                
                resetNewPrice() {
                    this.newPrice = {
                        crop_name: '',
                        variety: '',
                        price_per_kg: '',
                        price_trend: 'stable',
                        demand_level: 'moderate',
                        price_date: new Date().toISOString().split('T')[0],
                        notes: ''
                    };
                },
                
                editPrice(price) {
                    this.newPrice = { ...price };
                    this.showAddPriceModal = true;
                },
                
                deletePrice(id) {
                    if (confirm('Are you sure you want to delete this price entry?')) {
                        this.marketPrices = this.marketPrices.filter(p => p.id !== id);
                    }
                },
                
                async saveAnnouncement() {
                    try {
                        const response = await fetch('/api/announcements', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.newAnnouncement)
                        });
                        
                        if (response.ok) {
                            await this.loadAnnouncements();
                            this.showAddAnnouncementModal = false;
                            this.resetNewAnnouncement();
                        }
                    } catch (error) {
                        // For demo
                        this.announcements.unshift({
                            id: Date.now(),
                            ...this.newAnnouncement,
                            published_at: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                        });
                        this.showAddAnnouncementModal = false;
                        this.resetNewAnnouncement();
                    }
                },
                
                resetNewAnnouncement() {
                    this.newAnnouncement = {
                        title: '',
                        content: '',
                        type: 'general',
                        priority: 'normal'
                    };
                },
                
                editAnnouncement(announcement) {
                    this.newAnnouncement = { ...announcement };
                    this.showAddAnnouncementModal = true;
                },
                
                deleteAnnouncement(id) {
                    if (confirm('Are you sure you want to delete this announcement?')) {
                        this.announcements = this.announcements.filter(a => a.id !== id);
                    }
                },
                
                viewMessage(message) {
                    this.selectedMessage = message;
                    this.showMessageModal = true;
                    if (!message.is_read) {
                        message.is_read = true;
                        this.unreadMessages = Math.max(0, this.unreadMessages - 1);
                    }
                },
                
                replyToMessage(message) {
                    alert('Reply feature coming soon!');
                },
                
                getCropEmoji(cropName) {
                    const emojis = {
                        'cabbage': '🥬',
                        'chinese cabbage': '🥬',
                        'lettuce': '🥗',
                        'cauliflower': '🥦',
                        'broccoli': '🥦',
                        'snap beans': '🫛',
                        'garden peas': '🫛',
                        'sweet pepper': '🫑',
                        'white potato': '🥔',
                        'carrots': '🥕',
                        'tomato': '🍅',
                        'rice': '🌾'
                    };
                    return emojis[(cropName || '').toLowerCase()] || '🌱';
                },
                
                initYieldChart() {
                    const ctx = document.getElementById('yieldAnalysisChart');
                    if (!ctx) return;
                    
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const yieldData = [2.8, 3.1, 3.5, 4.2, 5.8, 5.6, 4.8, 4.2, 3.8, 3.5, 3.2, 2.9];
                    const optimalYield = [3.2, 3.4, 3.8, 4.5, 6.0, 5.9, 5.0, 4.5, 4.0, 3.7, 3.4, 3.1];
                    const rainfall = [80, 65, 90, 150, 200, 180, 160, 140, 120, 100, 85, 75];
                    const temperature = [18, 19, 21, 24, 26, 27, 26, 25, 24, 22, 20, 18];
                    
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [
                                {
                                    label: 'Actual Yield (tons/ha)',
                                    data: yieldData,
                                    backgroundColor: months.map((_, i) => i >= 4 && i <= 5 ? 'rgba(34, 197, 94, 0.8)' : 'rgba(34, 197, 94, 0.4)'),
                                    borderColor: 'rgb(34, 197, 94)',
                                    borderWidth: 1,
                                    order: 3
                                },
                                {
                                    label: 'Optimal Yield',
                                    data: optimalYield,
                                    type: 'line',
                                    borderColor: 'rgb(22, 101, 52)',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    fill: false,
                                    tension: 0.4,
                                    pointRadius: 2,
                                    order: 1
                                },
                                {
                                    label: 'Rainfall (mm)',
                                    data: rainfall.map(r => r / 40),
                                    type: 'line',
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 2,
                                    yAxisID: 'y1',
                                    order: 2
                                },
                                {
                                    label: 'Temperature (°C)',
                                    data: temperature.map(t => t / 5),
                                    type: 'line',
                                    borderColor: 'rgb(249, 115, 22)',
                                    borderWidth: 2,
                                    fill: false,
                                    tension: 0.4,
                                    pointRadius: 2,
                                    yAxisID: 'y1',
                                    order: 2
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (context.dataset.label.includes('Rainfall')) {
                                                return 'Rainfall: ' + (context.raw * 40).toFixed(0) + 'mm';
                                            }
                                            if (context.dataset.label.includes('Temperature')) {
                                                return 'Temperature: ' + (context.raw * 5).toFixed(0) + '°C';
                                            }
                                            return context.dataset.label + ': ' + context.raw.toFixed(1) + ' tons/ha';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 8,
                                    title: {
                                        display: true,
                                        text: 'Yield (tons/ha)'
                                    }
                                },
                                y1: {
                                    position: 'right',
                                    beginAtZero: true,
                                    max: 8,
                                    grid: {
                                        drawOnChartArea: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Climate Factors (scaled)'
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
        
        // Initialize chart after Alpine loads
        document.addEventListener('alpine:init', () => {
            Alpine.data('daOfficerDashboard', daOfficerDashboard);
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const ctx = document.getElementById('yieldAnalysisChart');
                if (ctx && typeof Chart !== 'undefined') {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const yieldData = [2.8, 3.1, 3.5, 4.2, 5.8, 5.6, 4.8, 4.2, 3.8, 3.5, 3.2, 2.9];
                    const optimalYield = [3.2, 3.4, 3.8, 4.5, 6.0, 5.9, 5.0, 4.5, 4.0, 3.7, 3.4, 3.1];
                    const rainfall = [80, 65, 90, 150, 200, 180, 160, 140, 120, 100, 85, 75];
                    const temperature = [18, 19, 21, 24, 26, 27, 26, 25, 24, 22, 20, 18];
                    
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [
                                {
                                    label: 'Actual Yield (tons/ha)',
                                    data: yieldData,
                                    backgroundColor: months.map((_, i) => i >= 4 && i <= 5 ? 'rgba(34, 197, 94, 0.8)' : 'rgba(34, 197, 94, 0.4)'),
                                    borderColor: 'rgb(34, 197, 94)',
                                    borderWidth: 1,
                                    order: 3
                                },
                                {
                                    label: 'Optimal Yield',
                                    data: optimalYield,
                                    type: 'line',
                                    borderColor: 'rgb(22, 101, 52)',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    fill: false,
                                    tension: 0.4,
                                    pointRadius: 2,
                                    order: 1
                                },
                                {
                                    label: 'Rainfall (mm)',
                                    data: rainfall.map(r => r / 40),
                                    type: 'line',
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 2,
                                    yAxisID: 'y1',
                                    order: 2
                                },
                                {
                                    label: 'Temperature (°C)',
                                    data: temperature.map(t => t / 5),
                                    type: 'line',
                                    borderColor: 'rgb(249, 115, 22)',
                                    borderWidth: 2,
                                    fill: false,
                                    tension: 0.4,
                                    pointRadius: 2,
                                    yAxisID: 'y1',
                                    order: 2
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (context.dataset.label.includes('Rainfall')) {
                                                return 'Rainfall: ' + (context.raw * 40).toFixed(0) + 'mm';
                                            }
                                            if (context.dataset.label.includes('Temperature')) {
                                                return 'Temperature: ' + (context.raw * 5).toFixed(0) + '°C';
                                            }
                                            return context.dataset.label + ': ' + context.raw.toFixed(1) + ' tons/ha';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 8,
                                    title: {
                                        display: true,
                                        text: 'Yield (tons/ha)'
                                    }
                                },
                                y1: {
                                    position: 'right',
                                    beginAtZero: true,
                                    max: 8,
                                    grid: {
                                        drawOnChartArea: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Climate Factors (scaled)'
                                    }
                                }
                            }
                        }
                    });
                }
            }, 500);
        });
    </script>
</body>
</html>
