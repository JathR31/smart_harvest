<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartHarvest Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="bg-gray-50" x-data="adminDashboard()">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex-shrink-0">
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
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
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
            </div>                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">System</p>
                    <a href="{{ route('admin.monitoring') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
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
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-800">SmartHarvest Admin</h2>
                    </div>
                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                        <svg class="w-4 h-4 animate-pulse text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Live • Auto-refresh every 30s</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <div x-data="{ open: false }" class="relative">
                        <button class="px-3 py-2 border border-gray-300 rounded-lg flex items-center space-x-2 hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            <span class="text-sm">English</span>
                        </button>
                    </div>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open; if(open) markAllAsRead()" class="relative p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="unreadCount > 0" class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" x-text="unreadCount"></span>
                        </button>

                        <!-- Notifications Dropdown -->
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="font-semibold text-gray-800">Notifications</h3>
                                <button @click="markAllAsRead()" class="text-sm text-green-600 hover:text-green-700">Mark all as read</button>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 relative">
                                        <button @click="removeNotification(notification.id)" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <div :class="{
                                                    'bg-blue-100 text-blue-600': notification.type === 'info',
                                                    'bg-green-100 text-green-600': notification.type === 'success',
                                                    'bg-yellow-100 text-yellow-600': notification.type === 'warning',
                                                    'bg-red-100 text-red-600': notification.type === 'alert'
                                                }" class="w-8 h-8 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="notification.type === 'info'">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="notification.type === 'success'">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="notification.type === 'warning'">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="notification.type === 'alert'">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-semibold text-gray-900" x-text="notification.title"></h4>
                                                <p class="text-sm text-gray-600 mt-1" x-text="notification.message"></p>
                                                <div class="flex justify-between items-center mt-2">
                                                    <span class="text-xs text-gray-400" x-text="notification.time"></span>
                                                    <button @click="markAsRead(notification.id)" class="text-xs text-green-600 hover:text-green-700">Mark as read</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" onsubmit="sessionStorage.setItem('isLoggedOut','true');">
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
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Overview Stats -->
                <div class="mb-6">
                    <h3 class="text-sm uppercase text-gray-500 mb-4">Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Total Users -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Total Users</p>
                                    <h3 class="text-3xl font-bold text-gray-800" x-text="stats.totalUsers"></h3>
                                    <p class="text-sm text-green-600 mt-2">
                                        <span x-text="'+' + stats.newUsersThisWeek"></span> this week
                                    </p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Data Records -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Crop Data Records</p>
                                    <h3 class="text-3xl font-bold text-gray-800" x-text="stats.dataRecords"></h3>
                                    <p class="text-sm text-green-600 mt-2">
                                        <span x-text="'+' + stats.newRecordsThisWeek"></span> this week
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <span x-text="stats.recordsToday"></span> today
                                    </p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                                        <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                                        <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Actions -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Pending Actions</p>
                                    <h3 class="text-3xl font-bold text-gray-800" x-text="stats.pendingActions"></h3>
                                    <p class="text-sm text-red-600 mt-2">
                                        <span x-text="stats.urgentActions"></span> urgent
                                    </p>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- System Health -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">System Health</p>
                                    <h3 class="text-3xl font-bold text-gray-800">Good</h3>
                                    <p class="text-sm text-gray-500 mt-2">Last check: 5 min ago</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Activity -->
                    <div class="lg:col-span-2 bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-green-700">Recent Activity</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <template x-for="activity in recentActivity" :key="activity.id">
                                <div class="flex items-start space-x-4 pb-4 border-b border-gray-100 last:border-0">
                                    <div :class="{
                                        'bg-blue-100 text-blue-600': activity.type === 'user',
                                        'bg-green-100 text-green-600': activity.type === 'data_upload',
                                        'bg-yellow-100 text-yellow-600': activity.type === 'warning',
                                        'bg-red-100 text-red-600': activity.type === 'security',
                                        'bg-purple-100 text-purple-600': activity.type === 'admin'
                                    }" class="p-2 rounded-lg flex-shrink-0">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="activity.type === 'user'">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        </svg>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="activity.type === 'data_upload'">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="activity.type === 'warning'">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="activity.type === 'security'">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-800" x-text="activity.description"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="activity.time"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Data Validation Alerts -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-green-700">Data Validation Alerts</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <template x-for="alert in dataAlerts" :key="alert.id">
                                    <div class="border-b border-gray-100 pb-4 last:border-0">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center space-x-2">
                                                <p class="text-xs font-semibold text-gray-500">RECORD #<span x-text="alert.recordId"></span></p>
                                                <span :class="getSeverityColor(alert.severity)" class="px-2 py-0.5 rounded text-xs font-semibold" x-text="alert.severity"></span>
                                            </div>
                                            <span :class="{
                                                'bg-yellow-100 text-yellow-800': alert.status === 'Pending',
                                                'bg-green-100 text-green-800': alert.status === 'Resolved',
                                                'bg-gray-100 text-gray-800': alert.status === 'Ignored'
                                            }" class="px-2 py-1 rounded text-xs font-semibold" x-text="alert.status"></span>
                                        </div>
                                        <p class="text-sm text-gray-800 mb-2" x-text="alert.issue"></p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500" x-text="alert.time"></span>
                                            <button class="text-xs text-blue-600 hover:text-blue-700 font-semibold">Review →</button>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="dataAlerts.length === 0">
                                    <div class="text-center py-8">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm text-gray-500">No validation alerts</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Overview -->
                <div class="mt-6 bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-green-700">System Overview</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Server Status -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">Server Status</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">API Response</span>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Normal</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Database</span>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Online</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Storage</span>
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">78% used</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Statistics -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">Data Statistics</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Crop Records</span>
                                        <span class="text-sm font-semibold text-gray-800" x-text="stats.dataRecords"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Pending Validation</span>
                                        <span class="text-sm font-semibold text-yellow-600" x-text="stats.pendingValidation"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Flagged Records</span>
                                        <span class="text-sm font-semibold text-red-600" x-text="stats.flaggedRecords"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- User Activity -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">User Activity</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Active Today</span>
                                        <span class="text-sm font-semibold text-gray-800" x-text="stats.activeToday"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">New This Week</span>
                                        <span class="text-sm font-semibold text-gray-800" x-text="stats.newUsersThisWeek"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Admin Actions</span>
                                        <span class="text-sm font-semibold text-gray-800">46</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Users Table -->
                <div class="mt-6 bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-green-700">Recent Users</h3>
                        <a href="{{ route('admin.users') }}" class="text-sm text-green-600 hover:text-green-700 font-semibold">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Registered</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Active</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
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
                                            <div class="text-sm text-gray-600" x-text="user.location || 'Not set'"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600" x-text="user.created_at"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="user.last_active || 'Recently'"></td>
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
        function adminDashboard() {
            return {
                stats: {
                    totalUsers: 0,
                    newUsersThisWeek: 0,
                    dataRecords: 0,
                    newRecordsThisWeek: 0,
                    recordsToday: 0,
                    pendingActions: 0,
                    urgentActions: 0,
                    activeToday: 0,
                    pendingValidation: 0,
                    flaggedRecords: 0
                },
                notifications: [],
                unreadCount: 0,
                recentActivity: [],
                dataAlerts: [],
                recentUsers: [],
                activeView: 'dashboard',
                refreshInterval: null,
                lastUpdate: null,

                async init() {
                    await this.fetchDashboardData();
                    
                    // Auto-refresh every 30 seconds for real-time updates
                    this.refreshInterval = setInterval(() => {
                        this.fetchDashboardData();
                    }, 30000);
                    
                    // Update last update time
                    this.updateLastUpdateTime();
                    setInterval(() => {
                        this.updateLastUpdateTime();
                    }, 1000);
                },

                async fetchDashboardData() {
                    try {
                        const response = await fetch('{{ route("admin.api.dashboard") }}');
                        const data = await response.json();
                        
                        // Update stats with real-time data
                        this.stats = data.stats;
                        this.recentActivity = data.recentActivity;
                        this.dataAlerts = data.dataAlerts;
                        this.recentUsers = data.recentUsers;
                        
                        // Update notifications from API
                        if (data.notifications) {
                            const existingIds = this.notifications.map(n => n.id);
                            const newNotifications = data.notifications.filter(n => !existingIds.includes(n.id));
                            
                            // Add new notifications at the beginning
                            this.notifications = [...newNotifications, ...this.notifications];
                            
                            // Keep only the last 20 notifications
                            this.notifications = this.notifications.slice(0, 20);
                            
                            this.unreadCount = data.unreadCount;
                        }
                        
                        this.lastUpdate = new Date();
                        
                        console.log('Dashboard data refreshed:', new Date().toLocaleTimeString());
                    } catch (error) {
                        console.error('Error fetching dashboard data:', error);
                    }
                },

                updateLastUpdateTime() {
                    if (this.lastUpdate) {
                        const seconds = Math.floor((new Date() - this.lastUpdate) / 1000);
                        if (seconds < 60) {
                            return seconds + 's ago';
                        } else {
                            return Math.floor(seconds / 60) + 'm ago';
                        }
                    }
                    return 'Never';
                },

                updateUnreadCount() {
                    this.unreadCount = this.notifications.filter(n => !n.read).length;
                },

                markAsRead(id) {
                    const notification = this.notifications.find(n => n.id === id);
                    if (notification) {
                        notification.read = true;
                        this.updateUnreadCount();
                    }
                },

                markAllAsRead() {
                    this.notifications.forEach(n => n.read = true);
                    this.updateUnreadCount();
                },

                removeNotification(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                    this.updateUnreadCount();
                },

                // Format time helper
                formatTime(timeString) {
                    return timeString || 'Just now';
                },

                // Get icon based on type
                getIcon(type) {
                    const icons = {
                        'alert': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                        'warning': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                        'info': 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                        'data_upload': 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'
                    };
                    return icons[type] || icons['info'];
                },

                // Severity badge color
                getSeverityColor(severity) {
                    const colors = {
                        'Critical': 'bg-red-100 text-red-800',
                        'High': 'bg-orange-100 text-orange-800',
                        'Medium': 'bg-yellow-100 text-yellow-800',
                        'Low': 'bg-blue-100 text-blue-800'
                    };
                    return colors[severity] || colors['Medium'];
                },

                // Manual refresh button
                async refreshNow() {
                    await this.fetchDashboardData();
                    // Show brief success message
                    console.log('Dashboard refreshed manually');
                },

                destroy() {
                    // Clean up interval on component destroy
                    if (this.refreshInterval) {
                        clearInterval(this.refreshInterval);
                    }
                }
            }
        }
    </script>
</body>
</html>
