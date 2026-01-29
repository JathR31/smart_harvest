<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DA-CAR Officer Dashboard - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .stat-card {
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .price-card {
            transition: all 0.2s;
        }
        .price-card:hover {
            background: #f9fafb;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="adminDashboard()" x-init="init()">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Green theme for DA-CAR Officers -->
        <aside class="w-64 bg-gradient-to-b from-green-700 to-green-900 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-6 border-b border-green-600">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <span class="text-xl">🌾</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SmartHarvest</h1>
                        <p class="text-xs text-green-200">DA-CAR Officer</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Overview</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded bg-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Admin Dashboard</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Data Management</p>
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
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">System</p>
                    <a href="{{ route('admin.monitoring') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Monitoring</span>
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
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-lg font-semibold text-gray-800">DA-CAR Officer Dashboard</span>
                            <span class="ml-3 px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">DA-CAR OFFICER</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>English</option>
                                <option>Filipino</option>
                                <option>Ilocano</option>
                            </select>
                        </div>
                        <div class="relative">
                            <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-full relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span x-show="pendingActions > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold" x-text="pendingActions"></span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline" onsubmit="sessionStorage.setItem('isLoggedOut','true');">
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
                <!-- Loading State -->
                <div x-show="loading" class="flex items-center justify-center h-64">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>

                <div x-show="!loading">
                    <!-- Stats Cards Row - Agriculture Focus -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <!-- Registered Farmers -->
                        <div class="stat-card bg-green-50 border border-green-200 rounded-xl p-5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Registered Farmers</p>
                                    <h3 class="text-2xl font-bold text-gray-800" x-text="stats.registeredFarmers?.toLocaleString() || '1,248'">1,248</h3>
                                    <p class="text-xs text-green-600 mt-1">+24 this month</p>
                                </div>
                                <div class="bg-green-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Data Records -->
                        <div class="stat-card bg-blue-50 border border-blue-200 rounded-xl p-5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Data Records</p>
                                    <h3 class="text-2xl font-bold text-gray-800" x-text="stats.dataRecords?.toLocaleString() || '42,876'">42,876</h3>
                                    <p class="text-xs text-blue-600 mt-1">+243 new</p>
                                </div>
                                <div class="bg-blue-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                                        <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                                        <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Farms -->
                        <div class="stat-card bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Total Farms</p>
                                    <h3 class="text-2xl font-bold text-gray-800" x-text="stats.totalFarms?.toLocaleString() || '856'">856</h3>
                                    <p class="text-xs text-yellow-600 mt-1">Across 13 municipalities</p>
                                </div>
                                <div class="bg-yellow-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Actions -->
                        <div class="stat-card bg-red-50 border border-red-200 rounded-xl p-5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Pending Actions</p>
                                    <h3 class="text-2xl font-bold text-gray-800" x-text="pendingActions || '12'">12</h3>
                                    <p class="text-xs text-red-600 mt-1" x-text="(stats.urgentActions || 3) + ' urgent'">3 urgent</p>
                                </div>
                                <div class="bg-red-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yield & Planting Schedule Analysis -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-green-700">Yield & Planting Schedule Analysis</h3>
                            <p class="text-sm text-gray-500">Combined analysis showing optimal planting periods (May-June) based on historical yield data, rainfall patterns, and temperature trends</p>
                        </div>
                        
                        <div class="h-80 mb-6">
                            <canvas id="yieldChart"></canvas>
                        </div>

                        <!-- Chart Legend -->
                        <div class="flex flex-wrap gap-6 justify-center mb-6">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                <span class="text-sm text-gray-600">Actual Yield</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-200 rounded mr-2"></div>
                                <span class="text-sm text-gray-600">Optimal Planting Period</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-0.5 bg-green-600 mr-2"></div>
                                <span class="text-sm text-gray-600">Optimal Yield</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-0.5 bg-blue-500 mr-2"></div>
                                <span class="text-sm text-gray-600">Rainfall</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-0.5 bg-orange-500 mr-2"></div>
                                <span class="text-sm text-gray-600">Temperature</span>
                            </div>
                        </div>

                        <!-- Insights Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                                <div class="flex items-center mb-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-semibold text-green-700">Peak Yield Period</span>
                                </div>
                                <p class="text-xs text-gray-600" x-text="insights.peakYieldPeriod"></p>
                            </div>
                            <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                                <div class="flex items-center mb-2">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-semibold text-blue-700">Rainfall Pattern</span>
                                </div>
                                <p class="text-xs text-gray-600" x-text="insights.rainfallPattern"></p>
                            </div>
                            <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                                <div class="flex items-center mb-2">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-semibold text-yellow-700">Recommendation</span>
                                </div>
                                <p class="text-xs text-gray-600" x-text="insights.recommendation"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Validation Alerts -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Data Validation Alerts</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-sm text-gray-500 border-b">
                                        <th class="pb-3 font-medium">RECORD ID</th>
                                        <th class="pb-3 font-medium">ISSUE</th>
                                        <th class="pb-3 font-medium">STATUS</th>
                                        <th class="pb-3 font-medium">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="alert in validationAlerts" :key="alert.id">
                                        <tr class="border-b border-gray-100">
                                            <td class="py-4 text-sm font-medium text-gray-800" x-text="alert.recordId"></td>
                                            <td class="py-4 text-sm text-gray-600" x-text="alert.issue"></td>
                                            <td class="py-4">
                                                <span :class="alert.status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'" 
                                                      class="px-3 py-1 rounded-full text-xs font-medium" x-text="alert.status"></span>
                                            </td>
                                            <td class="py-4">
                                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Review</button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="validationAlerts.length === 0">
                                        <td colspan="4" class="py-8 text-center text-gray-500">No validation alerts</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Current Market Prices & Crop Performance -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Current Market Prices -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-green-700">Current Market Prices</h3>
                                    <p class="text-sm text-gray-500">Latest prices for major Cordillera crops (Updated: <span x-text="marketPrices.lastUpdated"></span>)</p>
                                </div>
                                <div class="bg-green-100 p-2 rounded-full">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <template x-for="crop in marketPrices.crops" :key="crop.name">
                                    <div class="price-card border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-semibold text-gray-800" x-text="crop.name"></span>
                                            <span :class="crop.change >= 0 ? 'text-green-600' : 'text-red-600'" class="text-xs font-medium flex items-center">
                                                <svg x-show="crop.change >= 0" class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                <svg x-show="crop.change < 0" class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                <span x-text="(crop.change >= 0 ? '+' : '') + crop.change + '%'"></span>
                                            </span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="text-2xl font-bold text-green-700">₱<span x-text="crop.price"></span></span>
                                            <span class="text-sm text-gray-500" x-text="'/' + crop.unit"></span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Market Demand:</span>
                                            <span :class="{
                                                'bg-red-100 text-red-700': crop.demand === 'High',
                                                'bg-yellow-100 text-yellow-700': crop.demand === 'Medium',
                                                'bg-gray-100 text-gray-700': crop.demand === 'Low'
                                            }" class="px-2 py-1 rounded text-xs font-medium" x-text="crop.demand"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Crop Performance by Variety (TOP 5) -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Crop Performance by Variety (TOP 5)</h3>
                                <p class="text-sm text-gray-500">Average yield per hectare in <span x-text="new Date().getFullYear()"></span></p>
                            </div>
                            
                            <div class="space-y-4">
                                <template x-for="(crop, index) in cropPerformance" :key="crop.variety">
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700" x-text="crop.variety"></span>
                                            <span class="text-sm text-gray-600" x-text="crop.yield + ' t/ha'"></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-6">
                                            <div class="bg-green-500 h-6 rounded-full flex items-center justify-end pr-2 transition-all duration-500"
                                                 :style="'width: ' + (crop.yield / maxYield * 100) + '%'">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>0</span>
                                    <span x-text="(maxYield / 2).toFixed(1)"></span>
                                    <span x-text="maxYield.toFixed(1)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function adminDashboard() {
            return {
                loading: true,
                stats: {
                    dataRecords: 0,
                    newRecords: 0,
                    urgentActions: 0
                },
                pendingActions: 0,
                insights: {
                    peakYieldPeriod: '',
                    rainfallPattern: '',
                    recommendation: ''
                },
                validationAlerts: [],
                marketPrices: {
                    lastUpdated: 'January 20, 2026',
                    crops: []
                },
                cropPerformance: [],
                maxYield: 15,
                yieldChart: null,

                async init() {
                    await this.loadDashboardData();
                    await this.loadYieldAnalysis();
                    await this.loadMarketPrices();
                    await this.loadValidationAlerts();
                    await this.loadCropPerformance();
                    this.loading = false;
                },

                async loadDashboardData() {
                    try {
                        const response = await fetch('{{ route("admin.api.dashboard") }}');
                        const data = await response.json();
                        
                        this.stats.dataRecords = data.totalRecords || 42876;
                        this.stats.newRecords = data.newRecords || 243;
                        this.pendingActions = data.pendingAlerts || 12;
                        this.stats.urgentActions = data.urgentAlerts || 3;
                    } catch (error) {
                        console.error('Error loading dashboard data:', error);
                        // Use fallback data
                        this.stats.dataRecords = 42876;
                        this.stats.newRecords = 243;
                        this.pendingActions = 12;
                        this.stats.urgentActions = 3;
                    }
                },

                async loadYieldAnalysis() {
                    try {
                        const response = await fetch('{{ route("admin.api.yield-analysis") }}');
                        const data = await response.json();
                        
                        if (data.chartData) {
                            this.renderYieldChart(data.chartData);
                        }
                        
                        if (data.insights) {
                            this.insights = data.insights;
                        }
                    } catch (error) {
                        console.error('Error loading yield analysis:', error);
                        // Use fallback data
                        this.insights = {
                            peakYieldPeriod: 'May-June shows highest yields (5-8 MT/ha) when rainfall and temperatures are optimal.',
                            rainfallPattern: 'Moderate rainfall (120-250mm) during May-June supports optimal crop growth and development.',
                            recommendation: 'Plant between May 15 - June 15 to achieve yields matching or exceeding optimal benchmarks.'
                        };
                        this.renderFallbackChart();
                    }
                },

                async loadMarketPrices() {
                    try {
                        const response = await fetch('{{ route("admin.api.market-prices") }}');
                        const data = await response.json();
                        
                        if (data.crops) {
                            this.marketPrices.crops = data.crops;
                            this.marketPrices.lastUpdated = data.lastUpdated || 'January 20, 2026';
                        }
                    } catch (error) {
                        console.error('Error loading market prices:', error);
                        // Use fallback data
                        this.marketPrices.crops = [
                            { name: 'Highland Cabbage', price: 25, unit: 'per kg', change: 8, demand: 'High' },
                            { name: 'Tinawon Rice', price: 45, unit: 'per kg', change: 0, demand: 'High' },
                            { name: 'Lettuce', price: 35, unit: 'per kg', change: -2, demand: 'Medium' },
                            { name: 'Potatoes', price: 28, unit: 'per kg', change: 12, demand: 'High' },
                            { name: 'Carrots', price: 30, unit: 'per kg', change: 8, demand: 'Medium' },
                            { name: 'Strawberries', price: 250, unit: 'per kg', change: 0, demand: 'High' }
                        ];
                    }
                },

                async loadValidationAlerts() {
                    try {
                        const response = await fetch('{{ route("admin.api.validation-alerts") }}');
                        const data = await response.json();
                        
                        if (data.alerts) {
                            this.validationAlerts = data.alerts;
                        }
                    } catch (error) {
                        console.error('Error loading validation alerts:', error);
                        // Use fallback data
                        this.validationAlerts = [
                            { id: 1, recordId: 'FARM-2025-487', issue: 'Unusually high yield value (12.2 mt/ha)', status: 'Pending' },
                            { id: 2, recordId: 'FARM-2025-892', issue: 'Duplicate entry detected', status: 'Resolved' }
                        ];
                    }
                },

                async loadCropPerformance() {
                    try {
                        const response = await fetch('{{ route("admin.api.crop-performance") }}');
                        const data = await response.json();
                        
                        if (data.topVarieties && data.topVarieties.length > 0) {
                            this.cropPerformance = data.topVarieties.map(v => ({
                                variety: v.variety || v.crop,
                                yield: v.yieldPerHectare
                            }));
                            this.maxYield = Math.max(...this.cropPerformance.map(c => c.yield)) * 1.1;
                        }
                    } catch (error) {
                        console.error('Error loading crop performance:', error);
                        // Use fallback data
                        this.cropPerformance = [
                            { variety: 'Lettuce', yield: 8.2 },
                            { variety: 'Cabbage', yield: 9.5 },
                            { variety: 'Carrots', yield: 5.8 },
                            { variety: 'White Potato', yield: 12.3 },
                            { variety: 'Snap Beans', yield: 6.1 }
                        ];
                        this.maxYield = 15;
                    }
                },

                renderYieldChart(chartData) {
                    const ctx = document.getElementById('yieldChart').getContext('2d');
                    
                    if (this.yieldChart) {
                        this.yieldChart.destroy();
                    }
                    
                    const labels = chartData.map(d => d.month);
                    const actualYield = chartData.map(d => d.actualYield);
                    const optimalYield = chartData.map(d => d.optimalYield);
                    const rainfall = chartData.map(d => d.rainfall);
                    const temperature = chartData.map(d => d.temperature);
                    const isOptimal = chartData.map(d => d.isOptimalPlanting);
                    
                    this.yieldChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Actual Yield (t/ha)',
                                    data: actualYield,
                                    backgroundColor: chartData.map(d => d.isOptimalPlanting ? 'rgba(34, 197, 94, 0.8)' : 'rgba(34, 197, 94, 0.5)'),
                                    borderColor: 'rgba(34, 197, 94, 1)',
                                    borderWidth: 1,
                                    yAxisID: 'y',
                                    order: 2
                                },
                                {
                                    label: 'Optimal Yield (t/ha)',
                                    data: optimalYield,
                                    type: 'line',
                                    borderColor: 'rgba(22, 163, 74, 1)',
                                    borderWidth: 2,
                                    pointRadius: 4,
                                    pointBackgroundColor: 'rgba(22, 163, 74, 1)',
                                    fill: false,
                                    yAxisID: 'y',
                                    order: 1
                                },
                                {
                                    label: 'Rainfall (mm)',
                                    data: rainfall,
                                    type: 'line',
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    pointRadius: 3,
                                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                                    fill: false,
                                    yAxisID: 'y1',
                                    order: 0
                                },
                                {
                                    label: 'Temperature (°C)',
                                    data: temperature,
                                    type: 'line',
                                    borderColor: 'rgba(249, 115, 22, 1)',
                                    borderWidth: 2,
                                    borderDash: [2, 2],
                                    pointRadius: 3,
                                    pointBackgroundColor: 'rgba(249, 115, 22, 1)',
                                    fill: false,
                                    yAxisID: 'y2',
                                    order: 0
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
                                        afterBody: function(context) {
                                            const idx = context[0].dataIndex;
                                            if (isOptimal[idx]) {
                                                return ['', '✓ Optimal Planting Period'];
                                            }
                                            return [];
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    position: 'left',
                                    title: {
                                        display: true,
                                        text: 'Yield (t/ha)'
                                    },
                                    min: 0,
                                    max: 8
                                },
                                y1: {
                                    type: 'linear',
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: 'Rainfall (mm)'
                                    },
                                    min: 0,
                                    max: 300,
                                    grid: {
                                        drawOnChartArea: false
                                    }
                                },
                                y2: {
                                    type: 'linear',
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: 'Temp (°C)'
                                    },
                                    min: 0,
                                    max: 40,
                                    grid: {
                                        drawOnChartArea: false
                                    },
                                    display: false
                                }
                            }
                        }
                    });
                },

                renderFallbackChart() {
                    const fallbackData = [
                        { month: 'Jan', actualYield: 3.5, optimalYield: 4.2, rainfall: 120, temperature: 18, isOptimalPlanting: false },
                        { month: 'Feb', actualYield: 3.8, optimalYield: 4.5, rainfall: 100, temperature: 19, isOptimalPlanting: false },
                        { month: 'Mar', actualYield: 4.2, optimalYield: 5.0, rainfall: 80, temperature: 20, isOptimalPlanting: false },
                        { month: 'Apr', actualYield: 4.8, optimalYield: 5.5, rainfall: 120, temperature: 21, isOptimalPlanting: false },
                        { month: 'May', actualYield: 6.2, optimalYield: 6.8, rainfall: 180, temperature: 22, isOptimalPlanting: true },
                        { month: 'Jun', actualYield: 7.0, optimalYield: 7.2, rainfall: 200, temperature: 23, isOptimalPlanting: true },
                        { month: 'Jul', actualYield: 5.5, optimalYield: 6.5, rainfall: 220, temperature: 23, isOptimalPlanting: true },
                        { month: 'Aug', actualYield: 4.8, optimalYield: 5.8, rainfall: 200, temperature: 22, isOptimalPlanting: false },
                        { month: 'Sep', actualYield: 4.2, optimalYield: 5.2, rainfall: 180, temperature: 21, isOptimalPlanting: false },
                        { month: 'Oct', actualYield: 3.8, optimalYield: 4.8, rainfall: 150, temperature: 20, isOptimalPlanting: false },
                        { month: 'Nov', actualYield: 3.5, optimalYield: 4.5, rainfall: 130, temperature: 19, isOptimalPlanting: false },
                        { month: 'Dec', actualYield: 3.2, optimalYield: 4.0, rainfall: 120, temperature: 18, isOptimalPlanting: false }
                    ];
                    this.renderYieldChart(fallbackData);
                }
            };
        }
    </script>
</body>
</html>
