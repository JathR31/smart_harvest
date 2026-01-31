<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartHarvest - Superadmin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/translation.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item:hover { background: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background: rgba(255, 255, 255, 0.2); border-left: 3px solid #c084fc; }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
        .table-row:hover { background-color: #f9fafb; }
    </style>
</head>
<body class="bg-gray-50" x-data="superadminDashboard()">
    <div class="flex h-screen">
        <!-- Sidebar - Purple Theme for Superadmin -->
        <aside class="w-64 bg-gradient-to-b from-purple-800 to-purple-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-purple-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <span class="text-xl">🌱</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SmartHarvest</h1>
                        <p class="text-xs text-purple-200">Superadmin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-1">
                <button @click="activeTab = 'dashboard'" :class="{'active': activeTab === 'dashboard'}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded w-full text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </button>

                <p class="text-xs uppercase text-purple-300 mt-4 mb-2 px-4">USER MANAGEMENT</p>
                
                <button @click="activeTab = 'users'" :class="{'active': activeTab === 'users'}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded w-full text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Users</span>
                </button>
                
                <button @click="activeTab = 'roles'" :class="{'active': activeTab === 'roles'}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded w-full text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span>Roles & Permissions</span>
                </button>

                <p class="text-xs uppercase text-purple-300 mt-4 mb-2 px-4">MONITORING</p>
                
                <button @click="activeTab = 'farmers'" :class="{'active': activeTab === 'farmers'}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded w-full text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Farmer View</span>
                </button>
                
                <button @click="activeTab = 'admins'" :class="{'active': activeTab === 'admins'}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded w-full text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>DA Officer View</span>
                </button>

                <p class="text-xs uppercase text-purple-300 mt-4 mb-2 px-4">SYSTEM</p>
                
                <button @click="activeTab = 'logs'" :class="{'active': activeTab === 'logs'}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded w-full text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>System Logs</span>
                </button>
                

            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center border-b">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Superadmin Panel</h2>
                        <p class="text-xs text-gray-500">Full system control</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- User Profile -->
                    <div class="flex items-center space-x-2 mr-4">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-purple-700">{{ substr(Auth::user()->name ?? 'SA', 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Superadmin' }}</p>
                            <p class="text-xs text-gray-500">Superadmin</p>
                        </div>
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
                
                <!-- Dashboard Tab -->
                <div x-show="activeTab === 'dashboard'">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">System Overview</h3>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Total Users</p>
                                    <h3 class="text-3xl font-bold text-gray-800" x-text="stats.totalUsers">0</h3>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Farmers</p>
                                    <h3 class="text-3xl font-bold text-gray-800" x-text="stats.totalFarmers">0</h3>
                                </div>
                                <div class="bg-green-100 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">DA Officers</p>
                                    <h3 class="text-3xl font-bold text-gray-800" x-text="stats.totalAdmins">0</h3>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Active Today</p>
                                    <h3 class="text-3xl font-bold text-gray-800" x-text="stats.activeToday">0</h3>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent System Activity</h4>
                        <div class="space-y-4">
                            <template x-for="(activity, index) in recentActivity" :key="index">
                                <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50">
                                    <div :class="activity.color" class="w-10 h-10 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path x-show="activity.type === 'user'" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                            <path x-show="activity.type === 'login'" fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            <path x-show="activity.type === 'data'" fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800" x-text="activity.message"></p>
                                        <p class="text-xs text-gray-500" x-text="activity.time"></p>
                                    </div>
                                </div>
                            </template>
                            <div x-show="recentActivity.length === 0" class="text-center py-8 text-gray-500">
                                <p>No recent activity</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Tab -->
                <div x-show="activeTab === 'users'" x-cloak>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">User Management</h3>
                        <button @click="showAddUserModal = true" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Add User</span>
                        </button>
                    </div>

                    <!-- Users Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="user in users" :key="user.id">
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-purple-700" x-text="user.name.substring(0, 2).toUpperCase()"></span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800" x-text="user.name"></p>
                                                    <p class="text-xs text-gray-500" x-text="user.email"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span :class="{
                                                'bg-green-100 text-green-800': user.role === 'Farmer',
                                                'bg-blue-100 text-blue-800': user.role === 'Admin' || user.role === 'DA Admin',
                                                'bg-purple-100 text-purple-800': user.role === 'Superadmin'
                                            }" class="px-2 py-1 text-xs font-medium rounded-full" x-text="user.role"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span :class="{
                                                'bg-green-100 text-green-800': user.status === 'active',
                                                'bg-red-100 text-red-800': user.status === 'inactive'
                                            }" class="px-2 py-1 text-xs font-medium rounded-full" x-text="user.status || 'active'"></span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="user.created_at"></td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <button @click="editUser(user)" class="text-blue-600 hover:text-blue-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button @click="changeUserRole(user)" class="text-purple-600 hover:text-purple-800" title="Change Role">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Roles & Permissions Tab -->
                <div x-show="activeTab === 'roles'" x-cloak>
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Roles & Permissions</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Farmer Role -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Farmer</h4>
                                    <p class="text-xs text-gray-500" x-text="stats.totalFarmers + ' users'"></p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    View Market Prices
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    View Announcements
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Send/Receive Messages
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    View Planting Schedules
                                </div>
                            </div>
                        </div>

                        <!-- DA Officer Role -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">DA Officer</h4>
                                    <p class="text-xs text-gray-500" x-text="stats.totalAdmins + ' users'"></p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Manage Datasets
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Import Data
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Set Market Prices
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Create Announcements
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Monitor Farmers
                                </div>
                            </div>
                        </div>

                        <!-- Superadmin Role -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-purple-200 border-2">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Superadmin</h4>
                                    <p class="text-xs text-purple-600">Full Access</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    All DA Officer Permissions
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Manage Users
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Change Roles/Permissions
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    View System Logs
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    2FA Authentication
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Farmer View Tab -->
                <div x-show="activeTab === 'farmers'" x-cloak>
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Farmer Monitoring</h3>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farmer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Crops</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Active</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="farmer in farmers" :key="farmer.id">
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-green-700" x-text="farmer.name.substring(0, 2).toUpperCase()"></span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800" x-text="farmer.name"></p>
                                                    <p class="text-xs text-gray-500" x-text="farmer.email"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="farmer.location || 'Not specified'"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="farmer.crops || 'N/A'"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="farmer.lastActive || 'Never'"></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- DA Officer View Tab -->
                <div x-show="activeTab === 'admins'" x-cloak>
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">DA Officer Monitoring</h3>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Officer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Area</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions Taken</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Active</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="admin in admins" :key="admin.id">
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-700" x-text="admin.name.substring(0, 2).toUpperCase()"></span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800" x-text="admin.name"></p>
                                                    <p class="text-xs text-gray-500" x-text="admin.email"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">Department of Agriculture</td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="admin.area || 'Region 1'"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="admin.actions || '0'"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="admin.lastActive || 'Today'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- System Logs Tab -->
                <div x-show="activeTab === 'logs'" x-cloak>
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">System Logs</h3>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex space-x-4 mb-6">
                            <button @click="logFilter = 'all'" :class="{'bg-purple-600 text-white': logFilter === 'all', 'bg-gray-100 text-gray-600': logFilter !== 'all'}" class="px-4 py-2 rounded-lg text-sm font-medium">All Logs</button>
                            <button @click="logFilter = 'login'" :class="{'bg-purple-600 text-white': logFilter === 'login', 'bg-gray-100 text-gray-600': logFilter !== 'login'}" class="px-4 py-2 rounded-lg text-sm font-medium">Logins</button>
                            <button @click="logFilter = 'data'" :class="{'bg-purple-600 text-white': logFilter === 'data', 'bg-gray-100 text-gray-600': logFilter !== 'data'}" class="px-4 py-2 rounded-lg text-sm font-medium">Data Changes</button>
                            <button @click="logFilter = 'security'" :class="{'bg-purple-600 text-white': logFilter === 'security', 'bg-gray-100 text-gray-600': logFilter !== 'security'}" class="px-4 py-2 rounded-lg text-sm font-medium">Security</button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(log, index) in filteredLogs" :key="index">
                                <div class="flex items-center space-x-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <div :class="{
                                        'bg-blue-100 text-blue-600': log.type === 'login',
                                        'bg-green-100 text-green-600': log.type === 'data',
                                        'bg-red-100 text-red-600': log.type === 'security'
                                    }" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path x-show="log.type === 'login'" fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            <path x-show="log.type === 'data'" fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            <path x-show="log.type === 'security'" fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800" x-text="log.message"></p>
                                        <p class="text-xs text-gray-500" x-text="log.user + ' • ' + log.time"></p>
                                    </div>
                                    <span :class="{
                                        'bg-blue-100 text-blue-700': log.type === 'login',
                                        'bg-green-100 text-green-700': log.type === 'data',
                                        'bg-red-100 text-red-700': log.type === 'security'
                                    }" class="px-2 py-1 text-xs font-medium rounded" x-text="log.type"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Add User Modal -->
    <div x-show="showAddUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4" @click.away="showAddUserModal = false">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Add New User</h3>
                <button @click="showAddUserModal = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="addUser">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" x-model="newUser.name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" x-model="newUser.email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" x-model="newUser.password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select x-model="newUser.role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="Farmer">Farmer</option>
                            <option value="Admin">DA Officer (Admin)</option>
                            <option value="DA Admin">DA Admin</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex space-x-3 mt-6">
                    <button type="button" @click="showAddUserModal = false" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Add User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function superadminDashboard() {
            return {
                activeTab: 'dashboard',
                showAddUserModal: false,
                logFilter: 'all',
                
                stats: {
                    totalUsers: 0,
                    totalFarmers: 0,
                    totalAdmins: 0,
                    activeToday: 0
                },
                
                users: [],
                farmers: [],
                admins: [],
                recentActivity: [],
                systemLogs: [],
                
                newUser: {
                    name: '',
                    email: '',
                    password: '',
                    role: 'Farmer'
                },

                async init() {
                    await this.fetchDashboardData();
                    await this.fetchUsers();
                    await this.fetchSystemLogs();
                },

                async fetchDashboardData() {
                    try {
                        const response = await fetch('/api/superadmin/dashboard');
                        if (response.ok) {
                            const data = await response.json();
                            this.stats = data.stats || this.stats;
                            this.recentActivity = data.recentActivity || [];
                        }
                    } catch (error) {
                        console.error('Error fetching dashboard:', error);
                    }
                },

                async fetchUsers() {
                    try {
                        const response = await fetch('/api/superadmin/users');
                        if (response.ok) {
                            const data = await response.json();
                            this.users = data;
                            this.farmers = data.filter(u => u.role === 'Farmer');
                            this.admins = data.filter(u => u.role === 'Admin' || u.role === 'DA Admin');
                        }
                    } catch (error) {
                        console.error('Error fetching users:', error);
                    }
                },

                async fetchSystemLogs() {
                    try {
                        const response = await fetch('/api/superadmin/logs');
                        if (response.ok) {
                            this.systemLogs = await response.json();
                        }
                    } catch (error) {
                        console.error('Error fetching logs:', error);
                    }
                },

                async addUser() {
                    try {
                        const response = await fetch('/api/superadmin/users', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.newUser)
                        });
                        
                        if (response.ok) {
                            await this.fetchUsers();
                            this.showAddUserModal = false;
                            this.newUser = { name: '', email: '', password: '', role: 'Farmer' };
                            alert('User added successfully!');
                        } else {
                            const error = await response.json();
                            alert('Error: ' + (error.message || 'Failed to add user'));
                        }
                    } catch (error) {
                        console.error('Error adding user:', error);
                        alert('Error adding user');
                    }
                },

                editUser(user) {
                    alert('Edit user: ' + user.name);
                },

                async changeUserRole(user) {
                    const newRole = prompt('Enter new role (Farmer, Admin, DA Admin):', user.role);
                    if (newRole && ['Farmer', 'Admin', 'DA Admin'].includes(newRole)) {
                        try {
                            const response = await fetch(`/api/superadmin/users/${user.id}/role`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({ role: newRole })
                            });
                            
                            if (response.ok) {
                                await this.fetchUsers();
                                alert('Role updated successfully!');
                            }
                        } catch (error) {
                            console.error('Error updating role:', error);
                        }
                    }
                },

                get filteredLogs() {
                    if (this.logFilter === 'all') return this.systemLogs;
                    return this.systemLogs.filter(log => log.type === this.logFilter);
                }
            }
        }
    </script>
</body>
</html>
