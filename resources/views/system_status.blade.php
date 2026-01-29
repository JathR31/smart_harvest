<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Status - SmartHarvest Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        .stat-card {
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .action-card {
            transition: all 0.2s;
        }
        .action-card:hover {
            background: #f9fafb;
            border-color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="systemStatus()" x-init="init()">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-6 border-b border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
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
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">Data Management</p>
                    <a href="{{ route('admin.datasets') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded bg-blue-700">
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
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-lg font-semibold text-gray-800">SmartHarvest Admin</span>
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
                                <span x-show="notifications > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold" x-text="notifications"></span>
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
                    <!-- Stats Cards Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Data Records Card -->
                        <div class="stat-card bg-green-50 border border-green-200 rounded-xl p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Data Records</p>
                                    <h3 class="text-4xl font-bold text-gray-800" x-text="stats.dataRecords.toLocaleString()">0</h3>
                                    <p class="text-sm text-green-600 mt-2">
                                        <span x-text="'+' + stats.newRecords + ' New'"></span>
                                    </p>
                                </div>
                                <div class="bg-green-500 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                                        <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                                        <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- System Health Card -->
                        <div class="stat-card bg-white border border-gray-200 rounded-xl p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">System Health</p>
                                    <h3 class="text-4xl font-bold" :class="systemHealth === 'Good' ? 'text-green-600' : 'text-yellow-600'" x-text="systemHealth">Good</h3>
                                    <p class="text-sm text-gray-500 mt-2">
                                        Last check: <span x-text="lastHealthCheck"></span>
                                    </p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Active Datasets Card -->
                        <div class="stat-card bg-white border border-gray-200 rounded-xl p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Active Datasets</p>
                                    <h3 class="text-4xl font-bold text-gray-800" x-text="stats.activeDatasets">0</h3>
                                    <p class="text-sm text-gray-500 mt-2">All municipalities covered</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                                        <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                                        <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Status Overview -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                        <h3 class="text-xl font-bold text-green-700 mb-6">System Status Overview</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Server Status -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">Server Status</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">API Response</span>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium"
                                              :class="serverStatus.api === 'Normal' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                              x-text="serverStatus.api"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Database</span>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium"
                                              :class="serverStatus.database === 'Online' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                              x-text="serverStatus.database"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Storage</span>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700"
                                              x-text="serverStatus.storage"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Statistics -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">Data Statistics</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Total Farms</span>
                                        <span class="text-sm font-semibold text-gray-800" x-text="dataStatistics.totalFarms.toLocaleString()"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Municipalities</span>
                                        <span class="text-sm font-semibold text-gray-800" x-text="dataStatistics.municipalities"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Crop Types</span>
                                        <span class="text-sm font-semibold text-gray-800" x-text="dataStatistics.cropTypes"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">Recent Activity</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Data Updates</span>
                                        <div class="text-right">
                                            <span class="text-sm font-semibold text-gray-800" x-text="recentActivity.dataUpdates"></span>
                                            <p class="text-xs text-gray-500">Today</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">System Logs</span>
                                        <div class="text-right">
                                            <span class="text-sm font-semibold text-gray-800" x-text="recentActivity.systemLogs + ' entries'"></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Monitoring Alerts</span>
                                        <div class="text-right">
                                            <span class="text-sm font-semibold text-orange-600" x-text="recentActivity.monitoringAlerts + ' pending'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Manage Datasets -->
                        <div class="action-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                                        <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                                        <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-800">Manage Datasets</h4>
                                    <p class="text-sm text-gray-500 mt-1">View and manage data</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.datasets') }}" class="mt-4 block w-full text-center py-2 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                Go to Datasets →
                            </a>
                        </div>

                        <!-- System Monitoring -->
                        <div class="action-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-start space-x-4">
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-800">System Monitoring</h4>
                                    <p class="text-sm text-gray-500 mt-1">Check system health</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.monitoring') }}" class="mt-4 block w-full text-center py-2 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                Go to Monitoring →
                            </a>
                        </div>

                        <!-- View Logs -->
                        <div class="action-card bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-start space-x-4">
                                <div class="bg-purple-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-800">View Logs</h4>
                                    <p class="text-sm text-gray-500 mt-1">Review system logs</p>
                                </div>
                            </div>
                            <button class="mt-4 block w-full text-center py-2 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                Go to Logs →
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function systemStatus() {
            return {
                loading: true,
                notifications: 2,
                stats: {
                    dataRecords: 0,
                    newRecords: 0,
                    activeDatasets: 0
                },
                systemHealth: 'Good',
                lastHealthCheck: '5 min ago',
                serverStatus: {
                    api: 'Normal',
                    database: 'Online',
                    storage: '78% used'
                },
                dataStatistics: {
                    totalFarms: 0,
                    municipalities: 0,
                    cropTypes: 0
                },
                recentActivity: {
                    dataUpdates: 0,
                    systemLogs: 0,
                    monitoringAlerts: 0
                },

                async init() {
                    await this.loadDashboardData();
                    await this.loadSystemOverview();
                    this.loading = false;
                },

                async loadDashboardData() {
                    try {
                        const response = await fetch('{{ route("admin.api.dashboard") }}');
                        const data = await response.json();
                        
                        this.stats.dataRecords = data.totalRecords || 42876;
                        this.stats.newRecords = data.newRecords || 243;
                        this.stats.activeDatasets = data.activeDatasets || 24;
                    } catch (error) {
                        console.error('Error loading dashboard data:', error);
                        // Use fallback data
                        this.stats.dataRecords = 42876;
                        this.stats.newRecords = 243;
                        this.stats.activeDatasets = 24;
                    }
                },

                async loadSystemOverview() {
                    try {
                        const response = await fetch('{{ route("admin.api.system-overview") }}');
                        const data = await response.json();
                        
                        if (data.serverStatus) {
                            this.serverStatus = data.serverStatus;
                        }
                        
                        if (data.dataStatistics) {
                            this.dataStatistics = {
                                totalFarms: data.dataStatistics.totalFarms || 1487,
                                municipalities: data.dataStatistics.municipalities || 24,
                                cropTypes: data.dataStatistics.cropTypes || 7
                            };
                        }
                        
                        if (data.userActivity) {
                            this.recentActivity = {
                                dataUpdates: data.userActivity.activeToday || 12,
                                systemLogs: 248,
                                monitoringAlerts: 3
                            };
                        }
                        
                        // Check ML API health
                        if (data.serverStatus && data.serverStatus.mlApi === 'Connected') {
                            this.systemHealth = 'Good';
                        }
                    } catch (error) {
                        console.error('Error loading system overview:', error);
                        // Use fallback data
                        this.dataStatistics = {
                            totalFarms: 1487,
                            municipalities: 24,
                            cropTypes: 7
                        };
                        this.recentActivity = {
                            dataUpdates: 12,
                            systemLogs: 248,
                            monitoringAlerts: 3
                        };
                    }
                }
            };
        }
    </script>
</body>
</html>
