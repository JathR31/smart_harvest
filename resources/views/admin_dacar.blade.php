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
                        <p class="text-xs text-green-200">DA Officer</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Overview</p>
                    <button @click="currentSection = 'dashboard'" :class="currentSection === 'dashboard' ? 'bg-green-600' : 'hover:bg-green-800'" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded transition-colors w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </button>
                    <button @click="currentSection = 'market-prices'" :class="currentSection === 'market-prices' ? 'bg-green-600' : 'hover:bg-green-800'" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded transition-colors w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Market Prices</span>
                    </button>
                    <button @click="currentSection = 'announcements'" :class="currentSection === 'announcements' ? 'bg-green-600' : 'hover:bg-green-800'" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded transition-colors w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Announcements</span>
                    </button>
                    <button @click="currentSection = 'inbox'" :class="currentSection === 'inbox' ? 'bg-green-600' : 'hover:bg-green-800'" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded transition-colors w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Inbox</span>
                        <span x-show="unreadMessages > 0" class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5 font-bold" x-text="unreadMessages"></span>
                    </button>
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
                    <form method="POST" action="{{ route('logout') }}">
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
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-lg font-semibold text-gray-800">DA Officer Dashboard</span>
                            <span class="ml-3 px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">DA OFFICER</span>
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

                <!-- DASHBOARD SECTION -->
                <div x-show="!loading && currentSection === 'dashboard'">
                    <!-- Stats Cards Row - Agriculture Focus -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <!-- Registered Farmers -->
                        <div class="stat-card bg-green-50 border border-green-200 rounded-xl p-5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Registered Farmers</p>
                                    <h3 class="text-2xl font-bold text-gray-800" x-text="(stats.registeredFarmers || 0).toLocaleString()">0</h3>
                                    <p class="text-xs text-green-600 mt-1" x-text="'+' + (stats.newFarmersThisMonth || 0) + ' this month'">+0 this month</p>
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
                                    <h3 class="text-2xl font-bold text-gray-800" x-text="(stats.dataRecords || 0).toLocaleString()">0</h3>
                                    <p class="text-xs text-blue-600 mt-1" x-text="'+' + (stats.newRecords || 0) + ' new'">+0 new</p>
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
                                    <h3 class="text-2xl font-bold text-gray-800" x-text="(stats.totalFarms || 0).toLocaleString()">0</h3>
                                    <p class="text-xs text-yellow-600 mt-1" x-text="'Across ' + (stats.municipalitiesCount || 0) + ' municipalities'">Across 0 municipalities</p>
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
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-bold text-green-700">Yield & Planting Schedule Analysis</h3>
                                    <span x-show="mlStatus.yieldAnalysis" class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 7H7v6h6V7z"/>
                                            <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                                        </svg>
                                        ML POWERED
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">Combined analysis showing optimal planting periods (May-June) based on historical yield data, rainfall patterns, and temperature trends</p>
                            </div>
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
                                            <span class="text-2xl font-bold text-green-700">₱<span x-text="parseFloat(crop.price).toFixed(2)"></span></span>
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
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-bold text-gray-800">Crop Performance by Variety (TOP 5)</h3>
                                        <span x-show="mlStatus.cropPerformance" class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 7H7v6h6V7z"/>
                                                <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                                            </svg>
                                            ML
                                        </span>
                                    </div>
                                    <div>
                                        <select x-model="selectedMunicipality" @change="onMunicipalityChange()" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <option value="">All Municipalities</option>
                                            <option value="La Trinidad">La Trinidad</option>
                                            <option value="Baguio City">Baguio City</option>
                                            <option value="Tublay">Tublay</option>
                                            <option value="Sablan">Sablan</option>
                                            <option value="Tuba">Tuba</option>
                                            <option value="Itogon">Itogon</option>
                                            <option value="Atok">Atok</option>
                                            <option value="Bokod">Bokod</option>
                                            <option value="Kabayan">Kabayan</option>
                                            <option value="Kapangan">Kapangan</option>
                                            <option value="Kibungan">Kibungan</option>
                                            <option value="Mankayan">Mankayan</option>
                                            <option value="Buguias">Buguias</option>
                                        </select>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500">Average yield per hectare in <span x-text="new Date().getFullYear()"></span> <span x-show="selectedMunicipality" class="font-semibold" x-text="'- ' + selectedMunicipality"></span></p>
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

                <!-- MARKET PRICES SECTION -->
                <div x-show="!loading && currentSection === 'market-prices'" x-data="{
                    pricesCurrentPage: 1,
                    pricesPerPage: 10,
                    pricesSearchQuery: '',
                    pricesSortColumn: 'crop_name',
                    pricesSortDirection: 'asc',
                    allPrices: [],
                    editingPrice: null,
                    showEditModal: false,
                    showAddModal: false,
                    editForm: { id: null, crop_name: '', variety: '', price_per_kg: '', previous_price: '', demand_level: 'moderate', market_location: '', is_active: true, price_date: new Date().toISOString().split('T')[0] },
                    addForm: { crop_name: '', variety: '', price_per_kg: '', demand_level: 'moderate', market_location: 'La Trinidad Trading Post', is_active: true, price_date: new Date().toISOString().split('T')[0] },
                    
                    init() {
                        this.loadAllPrices();
                    },
                    
                    async loadAllPrices() {
                        try {
                            const response = await fetch('{{ url("/api/market-prices") }}');
                            if (response.ok) {
                                this.allPrices = await response.json();
                            } else {
                                // Use fallback data if API fails
                                this.allPrices = [
                                    { id: 1, crop_name: 'Cabbage', variety: 'Highland', price_per_kg: 25.00, previous_price: 23.00, demand_level: 'high', market_location: 'La Trinidad Trading Post', is_active: true },
                                    { id: 2, crop_name: 'Chinese Cabbage', variety: 'Wombok', price_per_kg: 30.00, previous_price: 28.00, demand_level: 'high', market_location: 'Baguio City Market', is_active: true },
                                    { id: 3, crop_name: 'Lettuce', variety: 'Iceberg', price_per_kg: 35.00, previous_price: 37.00, demand_level: 'moderate', market_location: 'La Trinidad Trading Post', is_active: true },
                                    { id: 4, crop_name: 'Cauliflower', variety: 'White Crown', price_per_kg: 45.00, previous_price: 45.00, demand_level: 'moderate', market_location: 'Baguio City Market', is_active: true },
                                    { id: 5, crop_name: 'Broccoli', variety: 'Green Sprouting', price_per_kg: 60.00, previous_price: 63.00, demand_level: 'moderate', market_location: 'La Trinidad Trading Post', is_active: true },
                                    { id: 6, crop_name: 'Snap Beans', variety: 'Baguio Beans', price_per_kg: 55.00, previous_price: 53.00, demand_level: 'high', market_location: 'Baguio City Market', is_active: true },
                                    { id: 7, crop_name: 'Garden Peas', variety: 'Sweet Peas', price_per_kg: 70.00, previous_price: 70.00, demand_level: 'moderate', market_location: 'La Trinidad Trading Post', is_active: true },
                                    { id: 8, crop_name: 'Sweet Pepper', variety: 'Bell Pepper', price_per_kg: 80.00, previous_price: 72.00, demand_level: 'very_high', market_location: 'Baguio City Market', is_active: true },
                                    { id: 9, crop_name: 'White Potato', variety: 'Benguet White', price_per_kg: 28.00, previous_price: 25.00, demand_level: 'high', market_location: 'La Trinidad Trading Post', is_active: true },
                                    { id: 10, crop_name: 'Carrots', variety: 'Cordillera', price_per_kg: 30.00, previous_price: 28.00, demand_level: 'moderate', market_location: 'Baguio City Market', is_active: true }
                                ];
                            }
                        } catch (error) {
                            console.error('Error loading prices:', error);
                            // Use fallback data on error
                            this.allPrices = [
                                { id: 1, crop_name: 'Cabbage', variety: 'Highland', price_per_kg: 25.00, previous_price: 23.00, demand_level: 'high', market_location: 'La Trinidad Trading Post', is_active: true },
                                { id: 2, crop_name: 'Chinese Cabbage', variety: 'Wombok', price_per_kg: 30.00, previous_price: 28.00, demand_level: 'high', market_location: 'Baguio City Market', is_active: true },
                                { id: 3, crop_name: 'Lettuce', variety: 'Iceberg', price_per_kg: 35.00, previous_price: 37.00, demand_level: 'moderate', market_location: 'La Trinidad Trading Post', is_active: true },
                                { id: 4, crop_name: 'Cauliflower', variety: 'White Crown', price_per_kg: 45.00, previous_price: 45.00, demand_level: 'moderate', market_location: 'Baguio City Market', is_active: true },
                                { id: 5, crop_name: 'Broccoli', variety: 'Green Sprouting', price_per_kg: 60.00, previous_price: 63.00, demand_level: 'moderate', market_location: 'La Trinidad Trading Post', is_active: true },
                                { id: 6, crop_name: 'Snap Beans', variety: 'Baguio Beans', price_per_kg: 55.00, previous_price: 53.00, demand_level: 'high', market_location: 'Baguio City Market', is_active: true },
                                { id: 7, crop_name: 'Garden Peas', variety: 'Sweet Peas', price_per_kg: 70.00, previous_price: 70.00, demand_level: 'moderate', market_location: 'La Trinidad Trading Post', is_active: true },
                                { id: 8, crop_name: 'Sweet Pepper', variety: 'Bell Pepper', price_per_kg: 80.00, previous_price: 72.00, demand_level: 'very_high', market_location: 'Baguio City Market', is_active: true },
                                { id: 9, crop_name: 'White Potato', variety: 'Benguet White', price_per_kg: 28.00, previous_price: 25.00, demand_level: 'high', market_location: 'La Trinidad Trading Post', is_active: true },
                                { id: 10, crop_name: 'Carrots', variety: 'Cordillera', price_per_kg: 30.00, previous_price: 28.00, demand_level: 'moderate', market_location: 'Baguio City Market', is_active: true }
                            ];
                        }
                    },
                    
                    get filteredPrices() {
                        let filtered = this.allPrices.filter(p => 
                            p.crop_name.toLowerCase().includes(this.pricesSearchQuery.toLowerCase()) ||
                            (p.variety && p.variety.toLowerCase().includes(this.pricesSearchQuery.toLowerCase()))
                        );
                        
                        filtered.sort((a, b) => {
                            let aVal = a[this.pricesSortColumn] ?? '';
                            let bVal = b[this.pricesSortColumn] ?? '';
                            if (this.pricesSortColumn === 'price_per_kg') {
                                aVal = parseFloat(aVal) || 0;
                                bVal = parseFloat(bVal) || 0;
                            } else {
                                aVal = String(aVal).toLowerCase();
                                bVal = String(bVal).toLowerCase();
                            }
                            return this.pricesSortDirection === 'asc' ? (aVal > bVal ? 1 : -1) : (aVal < bVal ? 1 : -1);
                        });
                        
                        return filtered;
                    },
                    
                    get paginatedPrices() {
                        const start = (this.pricesCurrentPage - 1) * this.pricesPerPage;
                        return this.filteredPrices.slice(start, start + this.pricesPerPage);
                    },
                    
                    get totalPages() {
                        return Math.ceil(this.filteredPrices.length / this.pricesPerPage);
                    },
                    
                    sortBy(column) {
                        if (this.pricesSortColumn === column) {
                            this.pricesSortDirection = this.pricesSortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.pricesSortColumn = column;
                            this.pricesSortDirection = 'asc';
                        }
                    },
                    
                    editPrice(price) {
                        this.editForm = { ...price };
                        // Format price_date for date input (YYYY-MM-DD)
                        if (this.editForm.price_date) {
                            this.editForm.price_date = new Date(this.editForm.price_date).toISOString().split('T')[0];
                        } else {
                            this.editForm.price_date = new Date().toISOString().split('T')[0];
                        }
                        this.showEditModal = true;
                    },
                    
                    async savePrice() {
                        try {
                            const payload = {
                                crop_name: this.editForm.crop_name,
                                variety: this.editForm.variety || '',
                                price_per_kg: parseFloat(this.editForm.price_per_kg),
                                demand_level: this.editForm.demand_level || 'moderate',
                                market_location: this.editForm.market_location || 'La Trinidad Trading Post',
                                is_active: this.editForm.is_active !== false,
                                price_date: this.editForm.price_date || new Date().toISOString().split('T')[0],
                                price_trend: this.editForm.price_trend || 'stable'
                            };
                            const response = await fetch(`{{ url('/api/market-prices') }}/${this.editForm.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin',
                                body: JSON.stringify(payload)
                            });
                            
                            if (response.ok) {
                                await this.loadAllPrices();
                                this.showEditModal = false;
                                alert('Price updated successfully!');
                            } else {
                                const err = await response.json().catch(() => ({}));
                                alert(err.message || err.error || 'Error updating price. Make sure you are logged in.');
                            }
                        } catch (error) {
                            console.error('Error updating price:', error);
                            alert('Error updating price');
                        }
                    },
                    
                    async addNewPrice() {
                        if (!this.addForm.crop_name || !this.addForm.price_per_kg) {
                            alert('Please fill in the required fields: Crop Name and Price per kg');
                            return;
                        }
                        try {
                            const payload = {
                                crop_name: this.addForm.crop_name,
                                variety: this.addForm.variety || '',
                                price_per_kg: parseFloat(this.addForm.price_per_kg),
                                demand_level: this.addForm.demand_level || 'moderate',
                                market_location: this.addForm.market_location || 'La Trinidad Trading Post',
                                is_active: this.addForm.is_active !== false,
                                price_date: this.addForm.price_date || new Date().toISOString().split('T')[0],
                                price_trend: 'stable'
                            };
                            const response = await fetch('{{ url("/api/market-prices") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin',
                                body: JSON.stringify(payload)
                            });
                            
                            if (response.ok) {
                                await this.loadAllPrices();
                                this.showAddModal = false;
                                this.addForm = { crop_name: '', variety: '', price_per_kg: '', demand_level: 'moderate', market_location: 'La Trinidad Trading Post', is_active: true, price_date: new Date().toISOString().split('T')[0] };
                                alert('Price added successfully!');
                            } else {
                                const err = await response.json().catch(() => ({}));
                                console.error('Server error:', err);
                                alert(err.message || err.error || 'Error adding price. Make sure you are logged in.');
                            }
                        } catch (error) {
                            console.error('Error adding price:', error);
                            alert('Network error adding price. Please try again.');
                        }
                    }
                }">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Market Prices Management</h2>
                            <p class="text-gray-600 text-sm">Update and manage crop prices that will be visible to farmers</p>
                        </div>
                        <button @click="showAddModal = true" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add New Price
                        </button>
                    </div>

                    <!-- Search and Controls -->
                    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="relative flex-1 max-w-md">
                                <input type="text" x-model="pricesSearchQuery" placeholder="Search crops..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <select x-model="pricesPerPage" class="border border-gray-300 rounded-lg px-3 py-2">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                            </select>
                        </div>
                    </div>

                    <!-- Prices Table -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th @click="sortBy('crop_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                                        Crop Name
                                        <svg x-show="pricesSortColumn === 'crop_name'" class="w-4 h-4 inline" :class="pricesSortDirection === 'desc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variety</th>
                                    <th @click="sortBy('price_per_kg')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                                        Price (₱/kg)
                                        <svg x-show="pricesSortColumn === 'price_per_kg'" class="w-4 h-4 inline" :class="pricesSortDirection === 'desc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Demand</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Market Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="price in paginatedPrices" :key="price.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" x-text="price.crop_name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="price.variety || '-'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-lg font-bold text-green-600">₱<span x-text="parseFloat(price.price_per_kg || 0).toFixed(2)"></span></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                                  :class="{
                                                      'bg-green-100 text-green-700': price.demand_level === 'high' || price.demand_level === 'very_high',
                                                      'bg-yellow-100 text-yellow-700': price.demand_level === 'moderate',
                                                      'bg-gray-100 text-gray-600': price.demand_level === 'low'
                                                  }"
                                                  x-text="price.demand_level"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="price.market_location || '-'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button @click="editPrice(price)" class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Showing <span x-text="((pricesCurrentPage-1)*pricesPerPage)+1"></span> to <span x-text="Math.min(pricesCurrentPage*pricesPerPage, filteredPrices.length)"></span> of <span x-text="filteredPrices.length"></span>
                            </div>
                            <div class="flex gap-2">
                                <button @click="pricesCurrentPage = Math.max(1, pricesCurrentPage-1)" :disabled="pricesCurrentPage === 1" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Prev</button>
                                <button @click="pricesCurrentPage = Math.min(totalPages, pricesCurrentPage+1)" :disabled="pricesCurrentPage === totalPages" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Next</button>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div x-show="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="showEditModal = false">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                            <h3 class="text-lg font-bold mb-4">Edit Market Price</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Crop Name</label>
                                    <input type="text" x-model="editForm.crop_name" class="w-full border border-gray-300 rounded-lg px-3 py-2" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Variety</label>
                                    <input type="text" x-model="editForm.variety" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="e.g., Highland">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price per kg (₱) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" x-model="editForm.price_per_kg" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Demand Level</label>
                                    <select x-model="editForm.demand_level" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                        <option value="low">Low</option>
                                        <option value="moderate">Moderate</option>
                                        <option value="high">High</option>
                                        <option value="very_high">Very High</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Market Location</label>
                                    <input type="text" x-model="editForm.market_location" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Date <span class="text-red-500">*</span></label>
                                    <input type="date" x-model="editForm.price_date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                </div>
                                <div class="flex gap-2">
                                    <button @click="savePrice()" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Save</button>
                                    <button @click="showEditModal = false" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Price Modal -->
                    <div x-show="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="showAddModal = false">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                            <h3 class="text-lg font-bold mb-4">Add New Market Price</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Crop Name <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="addForm.crop_name" placeholder="e.g., Cabbage" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Variety</label>
                                    <input type="text" x-model="addForm.variety" placeholder="e.g., Highland" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price per kg (₱) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" x-model="addForm.price_per_kg" placeholder="0.00" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Demand Level</label>
                                    <select x-model="addForm.demand_level" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                        <option value="low">Low</option>
                                        <option value="moderate">Moderate</option>
                                        <option value="high">High</option>
                                        <option value="very_high">Very High</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Market Location</label>
                                    <input type="text" x-model="addForm.market_location" placeholder="e.g., La Trinidad Trading Post" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Date <span class="text-red-500">*</span></label>
                                    <input type="date" x-model="addForm.price_date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                </div>
                                <div class="flex gap-2">
                                    <button @click="addNewPrice()" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Add Price</button>
                                    <button @click="showAddModal = false" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ANNOUNCEMENTS SECTION -->
                <div x-show="!loading && currentSection === 'announcements'" x-data="{
                    announcementsList: [],
                    showCreateModal: false,
                    newAnnouncement: { title: '', message: '', priority: 'normal', target_group: 'all', municipality: 'all' },
                    
                    init() {
                        this.loadAnnouncementsList();
                    },
                    
                    async loadAnnouncementsList() {
                        try {
                            const response = await fetch('{{ url("/api/announcements") }}');
                            if (response.ok) {
                                this.announcementsList = await response.json();
                            }
                        } catch (error) {
                            console.error('Error loading announcements:', error);
                        }
                    },
                    
                    async createAnnouncement() {
                        try {
                            const response = await fetch('{{ url("/api/announcements") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                },
                                body: JSON.stringify(this.newAnnouncement)
                            });
                            
                            if (response.ok) {
                                await this.loadAnnouncementsList();
                                this.showCreateModal = false;
                                this.newAnnouncement = { title: '', message: '', priority: 'normal', target_group: 'all', municipality: 'all' };
                                alert('Announcement sent successfully!');
                            }
                        } catch (error) {
                            console.error('Error creating announcement:', error);
                            alert('Error sending announcement');
                        }
                    }
                }">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Announcements</h2>
                            <p class="text-gray-600 text-sm">Create and manage announcements for farmers</p>
                        </div>
                        <button @click="showCreateModal = true" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            New Announcement
                        </button>
                    </div>

                    <!-- Announcements List -->
                    <div class="space-y-4">
                        <template x-for="announcement in announcementsList" :key="announcement.id">
                            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div :class="{
                                            'bg-red-100': announcement.priority === 'urgent',
                                            'bg-yellow-100': announcement.priority === 'high',
                                            'bg-blue-100': announcement.priority === 'normal'
                                        }" class="w-10 h-10 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5" :class="{
                                                'text-red-600': announcement.priority === 'urgent',
                                                'text-yellow-600': announcement.priority === 'high',
                                                'text-blue-600': announcement.priority === 'normal'
                                            }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-800" x-text="announcement.title"></h3>
                                            <p class="text-xs text-gray-500" x-text="announcement.created_at"></p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase" 
                                          :class="{
                                              'bg-red-100 text-red-700': announcement.priority === 'urgent',
                                              'bg-yellow-100 text-yellow-700': announcement.priority === 'high',
                                              'bg-blue-100 text-blue-700': announcement.priority === 'normal'
                                          }"
                                          x-text="announcement.priority"></span>
                                </div>
                                <p class="text-gray-700 text-sm" x-text="announcement.message"></p>
                            </div>
                        </template>
                    </div>

                    <!-- Create Modal -->
                    <div x-show="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="showCreateModal = false">
                        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
                            <h3 class="text-lg font-bold mb-4">Create New Announcement</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                    <input type="text" x-model="newAnnouncement.title" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                    <textarea x-model="newAnnouncement.message" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                        <select x-model="newAnnouncement.priority" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="normal">Normal</option>
                                            <option value="high">High</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Send To</label>
                                        <select x-model="newAnnouncement.target_group" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                            <option value="all">All Farmers</option>
                                            <option value="municipality">Specific Municipality</option>
                                            <option value="crop_type">Specific Crop Farmers</option>
                                        </select>
                                    </div>
                                </div>
                                <div x-show="newAnnouncement.target_group === 'municipality'">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                                    <select x-model="newAnnouncement.municipality" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                        <option value="all">All</option>
                                        <option value="La Trinidad">La Trinidad</option>
                                        <option value="Benguet">Benguet</option>
                                        <option value="Baguio">Baguio</option>
                                    </select>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="createAnnouncement()" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Send Announcement</button>
                                    <button @click="showCreateModal = false" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INBOX SECTION -->
                <div x-show="!loading && currentSection === 'inbox'" x-data="{
                    inboxTab: 'received',
                    messagesList: { received: [], sent: [] },
                    showComposeModal: false,
                    showMessageModal: false,
                    selectedMessage: null,
                    newMessage: { recipient_id: '', recipient_name: '', subject: '', content: '' },
                    farmers: [],
                    
                    init() {
                        this.loadMessages();
                        this.loadFarmers();
                    },
                    
                    async loadMessages() {
                        try {
                            const response = await fetch('{{ url("/api/messages") }}');
                            if (response.ok) {
                                const data = await response.json();
                                this.messagesList = { received: data.received || [], sent: data.sent || [] };
                            }
                        } catch (error) {
                            console.error('Error loading messages:', error);
                        }
                    },
                    
                    async loadFarmers() {
                        try {
                            const response = await fetch('{{ url("/api/farmers") }}');
                            if (response.ok) {
                                this.farmers = await response.json();
                            }
                        } catch (error) {
                            console.error('Error loading farmers:', error);
                        }
                    },
                    
                    async sendMessage() {
                        try {
                            const response = await fetch('{{ url("/api/messages") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                },
                                body: JSON.stringify(this.newMessage)
                            });
                            
                            if (response.ok) {
                                await this.loadMessages();
                                this.showComposeModal = false;
                                this.newMessage = { recipient_id: '', recipient_name: '', subject: '', content: '' };
                                alert('Message sent successfully!');
                            }
                        } catch (error) {
                            console.error('Error sending message:', error);
                            alert('Error sending message');
                        }
                    },
                    
                    viewMessage(message) {
                        this.selectedMessage = message;
                        this.showMessageModal = true;
                    }
                }">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Inbox</h2>
                            <p class="text-gray-600 text-sm">Communicate with farmers</p>
                        </div>
                        <button @click="showComposeModal = true" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            New Message
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="flex gap-2 mb-4">
                        <button @click="inboxTab = 'received'" :class="inboxTab === 'received' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-lg font-medium">
                            Received (<span x-text="messagesList.received.length"></span>)
                        </button>
                        <button @click="inboxTab = 'sent'" :class="inboxTab === 'sent' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded-lg font-medium">
                            Sent (<span x-text="messagesList.sent.length"></span>)
                        </button>
                    </div>

                    <!-- Messages List -->
                    <div class="space-y-3">
                        <template x-if="inboxTab === 'received'">
                            <div>
                                <template x-for="message in messagesList.received" :key="message.id">
                                    <div @click="viewMessage(message)" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center gap-3 flex-1">
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <h4 class="font-semibold text-gray-800" x-text="message.sender_name || 'Farmer'"></h4>
                                                        <span x-show="!message.is_read" class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 font-medium" x-text="message.subject"></p>
                                                    <p class="text-xs text-gray-500 mt-1" x-text="(message.content || '').substring(0, 100) + '...'"></p>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-400" x-text="message.created_at"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="inboxTab === 'sent'">
                            <div>
                                <template x-for="message in messagesList.sent" :key="message.id">
                                    <div @click="viewMessage(message)" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center gap-3 flex-1">
                                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-800">To: <span x-text="message.recipient_name || 'Farmer'"></span></h4>
                                                    <p class="text-sm text-gray-600 font-medium" x-text="message.subject"></p>
                                                    <p class="text-xs text-gray-500 mt-1" x-text="(message.content || '').substring(0, 100) + '...'"></p>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-400" x-text="message.created_at"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Compose Modal -->
                    <div x-show="showComposeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="showComposeModal = false">
                        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
                            <h3 class="text-lg font-bold mb-4">Compose Message</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">To (Farmer)</label>
                                    <select x-model="newMessage.recipient_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                        <option value="">Select farmer...</option>
                                        <template x-for="farmer in farmers" :key="farmer.id">
                                            <option :value="farmer.id" x-text="farmer.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                    <input type="text" x-model="newMessage.subject" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                    <textarea x-model="newMessage.content" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="sendMessage()" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Send</button>
                                    <button @click="showComposeModal = false" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- View Message Modal -->
                    <div x-show="showMessageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="showMessageModal = false">
                        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold" x-text="selectedMessage?.subject"></h3>
                                <button @click="showMessageModal = false" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">From:</span> <span x-text="selectedMessage?.sender_name || 'Farmer'"></span>
                                </p>
                                <p class="text-xs text-gray-500" x-text="selectedMessage?.created_at"></p>
                            </div>
                            <div class="border-t pt-4">
                                <p class="text-gray-700" x-text="selectedMessage?.content"></p>
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
                currentSection: 'dashboard',
                unreadMessages: 5,
                selectedMunicipality: '',
                baseUrl: '{{ url("/") }}',  // Laravel base URL
                stats: {
                    registeredFarmers: 0,
                    newFarmersThisMonth: 0,
                    dataRecords: 0,
                    newRecords: 0,
                    totalFarms: 0,
                    municipalitiesCount: 0,
                    urgentActions: 0
                },
                pendingActions: 0,
                mlStatus: {
                    yieldAnalysis: false,
                    cropPerformance: false
                },
                insights: {
                    peakYieldPeriod: 'Loading analysis...',
                    rainfallPattern: 'Loading analysis...',
                    recommendation: 'Loading analysis...'
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
                    console.log('===== DA OFFICER DASHBOARD INITIALIZING =====');
                    console.log('Current URL:', window.location.href);
                    console.log('Loading dynamic data from ML-powered APIs...');
                    
                    // Handle URL hash for navigation
                    const hash = window.location.hash.substring(1); // Remove the # symbol
                    if (hash && ['dashboard', 'market-prices', 'announcements', 'inbox'].includes(hash)) {
                        this.currentSection = hash;
                        console.log('Navigating to section from URL hash:', hash);
                    }
                    
                    await this.loadDashboardData();
                    await this.loadYieldAnalysis();
                    await this.loadMarketPrices();
                    await this.loadValidationAlerts();
                    await this.loadCropPerformance();
                    this.loading = false;
                    
                    console.log('===== DASHBOARD INITIALIZATION COMPLETE =====');
                },

                async loadDashboardData() {
                    try {
                        console.log('Fetching dashboard stats from', this.baseUrl + '/api/admin/dashboard');
                        const response = await fetch(this.baseUrl + '/api/admin/dashboard');
                        
                        console.log('Response status:', response.status, response.statusText);
                        
                        if (!response.ok) {
                            throw new Error(`API returned ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        console.log('Dashboard stats loaded:', data);
                        this.stats.registeredFarmers = data.registeredFarmers || 0;
                        this.stats.newFarmersThisMonth = data.newFarmersThisMonth || 0;
                        this.stats.dataRecords = data.totalRecords || 0;
                        this.stats.newRecords = data.newRecords || 0;
                        this.stats.totalFarms = data.totalFarms || 0;
                        this.stats.municipalitiesCount = data.municipalitiesCount || 0;
                        this.pendingActions = data.pendingAlerts || 0;
                        this.stats.urgentActions = data.urgentAlerts || 0;
                    } catch (error) {
                        console.error('Error loading dashboard data:', error);
                        // Use zero values on error - will trigger data collection
                        this.stats.registeredFarmers = 0;
                        this.stats.newFarmersThisMonth = 0;
                        this.stats.dataRecords = 0;
                        this.stats.newRecords = 0;
                        this.stats.totalFarms = 0;
                        this.stats.municipalitiesCount = 0;
                        this.pendingActions = 0;
                        this.stats.urgentActions = 0;
                    }
                },

                async loadYieldAnalysis() {
                    try {
                        console.log('Fetching yield analysis from', this.baseUrl + '/api/admin/yield-analysis');
                        const response = await fetch(this.baseUrl + '/api/admin/yield-analysis');
                        
                        console.log('Response status:', response.status, response.statusText);
                        
                        if (!response.ok) {
                            const errorText = await response.text();
                            console.error('API Error Response:', errorText);
                            throw new Error(`API returned ${response.status}: ${errorText}`);
                        }
                        
                        const data = await response.json();
                        
                        console.log('Yield analysis loaded (ML Connected: ' + data.ml_connected + '):', data);
                        
                        // Update ML status
                        this.mlStatus.yieldAnalysis = data.ml_connected || false;
                        
                        // Set insights FIRST before rendering chart
                        if (data.insights) {
                            this.insights = data.insights;
                            console.log('Insights updated:', this.insights);
                        }
                        
                        if (data.chartData && data.chartData.length > 0) {
                            this.renderYieldChart(data.chartData);
                        } else {
                            this.renderFallbackChart();
                        }
                    } catch (error) {
                        console.error('Error loading yield analysis:', error);
                        alert('Failed to load yield analysis: ' + error.message);
                        this.mlStatus.yieldAnalysis = false;
                        // Only use fallback if API completely fails
                        this.insights = {
                            peakYieldPeriod: 'May-June typically shows highest yields (6-8 MT/ha) when rainfall (180-220mm) and temperatures (22-23°C) are optimal for vegetable production.',
                            rainfallPattern: 'Moderate rainfall (120-250mm) during May-June provides sufficient moisture without waterlogging, supporting optimal crop growth and development.',
                            recommendation: 'Plant between May 15 - June 15 to capitalize on favorable conditions. Monitor local weather forecasts and adjust planting dates within this window for best results.'
                        };
                        this.renderFallbackChart();
                    }
                },

                async loadMarketPrices() {
                    try {
                        console.log('Fetching market prices from', this.baseUrl + '/api/admin/market-prices');
                        const response = await fetch(this.baseUrl + '/api/admin/market-prices');
                        
                        if (!response.ok) {
                            throw new Error(`API returned ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        console.log('Market prices loaded:', data);
                        if (data.crops && data.crops.length > 0) {
                            this.marketPrices.crops = data.crops;
                            this.marketPrices.lastUpdated = data.lastUpdated || 'January 20, 2026';
                        } else {
                            this.useFallbackMarketPrices();
                        }
                    } catch (error) {
                        console.error('Error loading market prices:', error);
                        this.useFallbackMarketPrices();
                    }
                },

                useFallbackMarketPrices() {
                    // Use fallback data - only ML-supported crops
                    this.marketPrices.crops = [
                        { name: 'Cabbage', price: 25, unit: 'per kg', change: 8, demand: 'High' },
                        { name: 'Bell Pepper', price: 80, unit: 'per kg', change: 10, demand: 'High' },
                        { name: 'Broccoli', price: 60, unit: 'per kg', change: -3, demand: 'Medium' },
                        { name: 'Snap Beans', price: 55, unit: 'per kg', change: 2, demand: 'High' },
                        { name: 'Tomato', price: 40, unit: 'per kg', change: 5, demand: 'High' },
                        { name: 'Lettuce', price: 35, unit: 'per kg', change: -2, demand: 'Medium' },
                        { name: 'Carrot', price: 30, unit: 'per kg', change: 8, demand: 'Medium' },
                        { name: 'White Potato', price: 28, unit: 'per kg', change: 12, demand: 'High' },
                        { name: 'Cauliflower', price: 45, unit: 'per kg', change: 3, demand: 'Medium' },
                        { name: 'Chinese Cabbage', price: 30, unit: 'per kg', change: 5, demand: 'High' }
                    ];
                },

                async loadValidationAlerts() {
                    try {
                        const response = await fetch(this.baseUrl + '/api/admin/validation-alerts');
                        const data = await response.json();
                        
                        console.log('Validation alerts loaded:', data);
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
                        const url = this.baseUrl + '/api/admin/crop-performance' + (this.selectedMunicipality ? '?municipality=' + encodeURIComponent(this.selectedMunicipality) : '');
                        const response = await fetch(url);
                        const data = await response.json();
                        
                        console.log('Crop performance loaded (ML Connected: ' + data.ml_connected + ', Municipality: ' + (this.selectedMunicipality || 'All') + '):', data);
                        
                        // Update ML status
                        this.mlStatus.cropPerformance = data.ml_connected || false;
                        
                        if (data.topVarieties && data.topVarieties.length > 0) {
                            this.cropPerformance = data.topVarieties.map(v => ({
                                variety: v.variety || v.crop,
                                yield: v.yieldPerHectare
                            }));
                            this.maxYield = Math.max(...this.cropPerformance.map(c => c.yield)) * 1.1;
                        } else {
                            this.useFallbackCropPerformance();
                        }
                    } catch (error) {
                        console.error('Error loading crop performance:', error);
                        this.mlStatus.cropPerformance = false;
                        this.useFallbackCropPerformance();
                    }
                },

                useFallbackCropPerformance() {
                    // Use fallback data
                    this.cropPerformance = [
                        { variety: 'Lettuce', yield: 8.2 },
                        { variety: 'Cabbage', yield: 9.5 },
                        { variety: 'Carrots', yield: 5.8 },
                        { variety: 'White Potato', yield: 12.3 },
                        { variety: 'Snap Beans', yield: 6.1 }
                    ];
                    this.maxYield = 15;
                },

                onMunicipalityChange() {
                    console.log('Municipality changed to:', this.selectedMunicipality || 'All Municipalities');
                    this.loadCropPerformance();
                },

                renderYieldChart(chartData) {
                    const ctx = document.getElementById('yieldChart');
                    
                    if (!ctx) {
                        console.error('Canvas element yieldChart not found!');
                        return;
                    }
                    
                    if (this.yieldChart) {
                        this.yieldChart.destroy();
                    }
                    
                    console.log('Rendering chart with data:', chartData);
                    
                    const labels = chartData.map(d => d.month);
                    const actualYield = chartData.map(d => parseFloat(d.actualYield) || 0);
                    const optimalYield = chartData.map(d => parseFloat(d.optimalYield) || 0);
                    const rainfall = chartData.map(d => parseFloat(d.rainfall) || 0);
                    const temperature = chartData.map(d => parseFloat(d.temperature) || 0);
                    const isOptimal = chartData.map(d => d.isOptimalPlanting);
                    
                    // Calculate max values for dynamic scaling
                    const maxActualYield = Math.max(...actualYield, ...optimalYield);
                    const maxRainfall = Math.max(...rainfall);
                    const yieldMax = Math.ceil(maxActualYield * 1.2); // 20% above max for headroom
                    const rainfallMax = Math.ceil(maxRainfall * 1.2);
                    
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
                                    max: Math.max(yieldMax, 8),
                                    ticks: {
                                        stepSize: 1
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: 'Rainfall (mm)'
                                    },
                                    min: 0,
                                    max: Math.max(rainfallMax, 300),
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
                    
                    console.log('Chart rendered successfully');
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
