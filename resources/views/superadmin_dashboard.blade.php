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
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Roles & Permissions</h3>
                            <p class="text-gray-500 text-sm mt-1">Manage user roles and permissions across the system</p>
                        </div>
                        <button @click="fetchRolesUsers()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Refresh</span>
                        </button>
                    </div>

                    <!-- Role Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-800" x-text="roleStats.farmers">0</p>
                                    <p class="text-xs text-gray-500">Farmers</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-800" x-text="roleStats.daAdmins">0</p>
                                    <p class="text-xs text-gray-500">DA Officers</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-800" x-text="roleStats.admins">0</p>
                                    <p class="text-xs text-gray-500">Admins</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-4 border border-purple-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-800" x-text="roleStats.superadmins">0</p>
                                    <p class="text-xs text-gray-500">Superadmins</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                        <div class="flex flex-wrap gap-4 items-center">
                            <!-- Search -->
                            <div class="flex-1 min-w-[200px]">
                                <div class="relative">
                                    <input type="text" x-model="roleSearch" @input="filterRolesUsers()" 
                                           placeholder="Search users by name or email..." 
                                           class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Role Filter -->
                            <div class="min-w-[150px]">
                                <select x-model="roleFilter" @change="filterRolesUsers()" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">All Roles</option>
                                    <option value="Farmer">Farmer</option>
                                    <option value="DA Admin">DA Admin</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                            
                            <!-- Municipality Filter -->
                            <div class="min-w-[180px]">
                                <select x-model="municipalityFilter" @change="filterRolesUsers()" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">All Municipalities</option>
                                    <template x-for="mun in municipalities" :key="mun">
                                        <option :value="mun" x-text="mun"></option>
                                    </template>
                                </select>
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="min-w-[130px]">
                                <select x-model="statusFilter" @change="filterRolesUsers()" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">All Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                            
                            <!-- Clear Filters -->
                            <button @click="clearRoleFilters()" class="px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Active Filters Display -->
                        <div x-show="roleSearch || roleFilter || municipalityFilter || statusFilter" class="mt-3 flex flex-wrap gap-2">
                            <span class="text-xs text-gray-500">Active filters:</span>
                            <template x-if="roleSearch">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full flex items-center">
                                    Search: <span x-text="roleSearch" class="ml-1 font-medium"></span>
                                    <button @click="roleSearch = ''; filterRolesUsers()" class="ml-1 hover:text-purple-900">&times;</button>
                                </span>
                            </template>
                            <template x-if="roleFilter">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full flex items-center">
                                    Role: <span x-text="roleFilter" class="ml-1 font-medium"></span>
                                    <button @click="roleFilter = ''; filterRolesUsers()" class="ml-1 hover:text-blue-900">&times;</button>
                                </span>
                            </template>
                            <template x-if="municipalityFilter">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full flex items-center">
                                    Municipality: <span x-text="municipalityFilter" class="ml-1 font-medium"></span>
                                    <button @click="municipalityFilter = ''; filterRolesUsers()" class="ml-1 hover:text-green-900">&times;</button>
                                </span>
                            </template>
                            <template x-if="statusFilter">
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full flex items-center">
                                    Status: <span x-text="statusFilter" class="ml-1 font-medium"></span>
                                    <button @click="statusFilter = ''; filterRolesUsers()" class="ml-1 hover:text-yellow-900">&times;</button>
                                </span>
                            </template>
                        </div>
                    </div>

                    <!-- Users Table with Role Management -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h4 class="font-semibold text-gray-800">User Role Management</h4>
                                <p class="text-sm text-gray-500">
                                    <span x-show="rolesLoading">Loading users...</span>
                                    <span x-show="!rolesLoading">Showing <span x-text="filteredRolesUsersList.length"></span> of <span x-text="rolesUsersList.length"></span> users</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Error Message -->
                        <div x-show="rolesError" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                            <p class="font-semibold">Error loading users:</p>
                            <p x-text="rolesError" class="text-sm"></p>
                        </div>
                        
                        <!-- Loading Indicator -->
                        <div x-show="rolesLoading" class="p-8 text-center">
                            <svg class="animate-spin h-8 w-8 mx-auto text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500">Loading users...</p>
                        </div>
                        
                        <div x-show="!rolesLoading" class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Municipality</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Current Role</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Change Role</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="user in paginatedRolesUsers" :key="user.id">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-semibold" 
                                                         :class="{
                                                            'bg-green-500': user.role === 'Farmer',
                                                            'bg-blue-500': user.role === 'DA Admin',
                                                            'bg-orange-500': user.role === 'Admin',
                                                            'bg-purple-500': user.is_superadmin
                                                         }">
                                                        <span x-text="user.name ? user.name.charAt(0).toUpperCase() : 'U'"></span>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900" x-text="user.name"></p>
                                                        <p class="text-xs text-gray-500" x-text="user.email"></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm text-gray-600" x-text="user.location || 'Not specified'"></span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                                                      :class="{
                                                        'bg-green-100 text-green-800': user.role === 'Farmer',
                                                        'bg-blue-100 text-blue-800': user.role === 'DA Admin',
                                                        'bg-orange-100 text-orange-800': user.role === 'Admin',
                                                        'bg-purple-100 text-purple-800': user.is_superadmin
                                                      }">
                                                    <span x-text="user.is_superadmin ? 'Superadmin' : user.role"></span>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <template x-if="!user.is_superadmin">
                                                    <select x-model="user.newRole" 
                                                            class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                        <option value="Farmer">Farmer</option>
                                                        <option value="DA Admin">DA Admin</option>
                                                        <option value="Admin">Admin</option>
                                                    </select>
                                                </template>
                                                <template x-if="user.is_superadmin">
                                                    <span class="text-xs text-gray-400 italic">Cannot change</span>
                                                </template>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                                      :class="{
                                                        'bg-green-100 text-green-800': user.status === 'Active',
                                                        'bg-red-100 text-red-800': user.status === 'Inactive',
                                                        'bg-yellow-100 text-yellow-800': user.status === 'Pending'
                                                      }" 
                                                      x-text="user.status || 'Active'"></span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex space-x-2">
                                                    <template x-if="!user.is_superadmin">
                                                        <button @click="updateRoleForUser(user)" 
                                                                :disabled="user.role === user.newRole || user.saving"
                                                                class="px-3 py-1.5 text-xs bg-purple-600 text-white rounded-lg hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center">
                                                            <svg x-show="user.saving" class="animate-spin -ml-1 mr-1 h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            <span x-text="user.saving ? 'Saving...' : 'Update Role'"></span>
                                                        </button>
                                                    </template>
                                                    <button @click="openPermissionsModal(user)" 
                                                            class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center"
                                                            :class="{'opacity-50 cursor-not-allowed': user.is_superadmin}"
                                                            :disabled="user.is_superadmin">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                        </svg>
                                                        Permissions
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="filteredRolesUsersList.length === 0">
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                            <p class="font-medium">No users found</p>
                                            <p class="text-sm">Try adjusting your filters</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                Page <span x-text="rolesCurrentPage"></span> of <span x-text="rolesTotalPages"></span>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="rolesCurrentPage = Math.max(1, rolesCurrentPage - 1); paginateRolesUsers()" 
                                        :disabled="rolesCurrentPage === 1"
                                        class="px-3 py-1 border border-gray-300 rounded-lg text-sm disabled:opacity-50 hover:bg-gray-50">
                                    Previous
                                </button>
                                <button @click="rolesCurrentPage = Math.min(rolesTotalPages, rolesCurrentPage + 1); paginateRolesUsers()" 
                                        :disabled="rolesCurrentPage === rolesTotalPages"
                                        class="px-3 py-1 border border-gray-300 rounded-lg text-sm disabled:opacity-50 hover:bg-gray-50">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Role Definitions Section -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Farmer Role Card -->
                        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Farmer</h4>
                                    <p class="text-xs text-gray-500">Basic access level</p>
                                </div>
                            </div>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>View Market Prices</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>View Announcements</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>Send/Receive Messages</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>View Planting Schedules</li>
                            </ul>
                        </div>

                        <!-- DA Admin Role Card -->
                        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">DA Officer</h4>
                                    <p class="text-xs text-gray-500">Administrative access</p>
                                </div>
                            </div>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>Manage Datasets</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>Import Data</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>Set Market Prices</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>Create Announcements</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>Monitor Farmers</li>
                            </ul>
                        </div>

                        <!-- Superadmin Role Card -->
                        <div class="bg-white rounded-xl shadow-sm p-4 border border-purple-200">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Superadmin</h4>
                                    <p class="text-xs text-purple-600">Full system access</p>
                                </div>
                            </div>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-2"></span>All DA Officer Permissions</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-2"></span>Manage All Users</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-2"></span>Change Roles/Permissions</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-2"></span>View System Logs</li>
                                <li class="flex items-center"><span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-2"></span>2FA Management</li>
                            </ul>
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

    <!-- User Permissions Modal -->
    <div x-show="showPermissionsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-hidden" @click.away="closePermissionsModal()">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-white">Edit User Permissions</h3>
                    <p class="text-purple-200 text-sm mt-1" x-text="selectedUserForPermissions ? selectedUserForPermissions.name + ' (' + selectedUserForPermissions.role + ')' : ''"></p>
                </div>
                <button @click="closePermissionsModal()" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
                <!-- Loading State -->
                <div x-show="loadingPermissions" class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                </div>

                <!-- Permissions by Category -->
                <div x-show="!loadingPermissions">
                    <template x-for="(perms, category) in userPermissionsByCategory" :key="category">
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <span class="w-2 h-2 bg-purple-600 rounded-full mr-2"></span>
                                <span x-text="category"></span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 ml-4">
                                <template x-for="perm in perms" :key="perm.permission">
                                    <label class="flex items-start p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors border border-gray-100">
                                        <input type="checkbox" 
                                               :checked="perm.enabled"
                                               @change="toggleUserPermission(perm.permission, $event.target.checked)"
                                               class="mt-1 w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                        <div class="ml-3">
                                            <div class="font-medium text-gray-800 text-sm" x-text="formatPermissionName(perm.permission)"></div>
                                            <div class="text-xs text-gray-500" x-text="perm.description"></div>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                <div class="flex items-center space-x-4">
                    <button @click="selectAllPermissions()" class="text-sm text-purple-600 hover:text-purple-800">Select All</button>
                    <button @click="deselectAllPermissions()" class="text-sm text-gray-600 hover:text-gray-800">Deselect All</button>
                </div>
                <div class="flex space-x-3">
                    <button @click="closePermissionsModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                    <button @click="saveUserPermissions()" 
                            :disabled="savingPermissions"
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50 flex items-center">
                        <svg x-show="savingPermissions" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="savingPermissions ? 'Saving...' : 'Save Permissions'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function superadminDashboard() {
            return {
                activeTab: 'dashboard',
                showAddUserModal: false,
                showPermissionsModal: false,
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

                // Roles & Permissions Tab Data
                municipalities: [
                    'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
                    'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 
                    'Sablan', 'Tuba', 'Tublay'
                ],
                roleStats: {
                    farmers: 0,
                    daAdmins: 0,
                    admins: 0,
                    superadmins: 0
                },
                rolesLoading: false,
                rolesError: '',
                rolesUsersList: [],
                filteredRolesUsersList: [],
                paginatedRolesUsers: [],
                roleSearch: '',
                roleFilter: '',
                municipalityFilter: '',
                statusFilter: '',
                rolesCurrentPage: 1,
                rolesPerPage: 10,
                rolesTotalPages: 1,
                
                // Permissions Modal
                selectedUserForPermissions: null,
                userPermissionsByCategory: {},
                loadingPermissions: false,
                savingPermissions: false,

                async init() {
                    await this.fetchDashboardData();
                    await this.fetchUsers();
                    await this.fetchSystemLogs();
                    await this.fetchRolesUsers();
                },

                async fetchDashboardData() {
                    try {
                        const response = await fetch('{{ url("/api/superadmin/dashboard") }}');
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
                        const response = await fetch('{{ url("/api/superadmin/users") }}');
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

                async fetchRolesUsers() {
                    this.rolesLoading = true;
                    this.rolesError = '';
                    try {
                        console.log('Fetching users from:', '{{ url("/admin/api/users") }}');
                        const response = await fetch('{{ url("/admin/api/users") }}', {
                            credentials: 'same-origin',
                            headers: { 
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        console.log('Response status:', response.status);
                        
                        if (response.ok) {
                            const data = await response.json();
                            console.log('Users data received:', data);
                            this.rolesUsersList = (data.users || []).map(user => ({
                                ...user,
                                newRole: user.role || 'Farmer',
                                saving: false
                            }));
                            
                            // Calculate role stats
                            this.roleStats.farmers = this.rolesUsersList.filter(u => u.role === 'Farmer').length;
                            this.roleStats.daAdmins = this.rolesUsersList.filter(u => u.role === 'DA Admin').length;
                            this.roleStats.admins = this.rolesUsersList.filter(u => u.role === 'Admin').length;
                            this.roleStats.superadmins = this.rolesUsersList.filter(u => u.is_superadmin).length;
                            
                            console.log('Role stats:', this.roleStats);
                            console.log('Total users loaded:', this.rolesUsersList.length);
                            this.filterRolesUsers();
                        } else {
                            const errorText = await response.text();
                            console.error('API Error:', response.status, errorText);
                            this.rolesError = `Error ${response.status}: ${errorText.substring(0, 100)}`;
                        }
                    } catch (error) {
                        console.error('Error fetching roles users:', error);
                        this.rolesError = 'Network error: ' + error.message;
                    } finally {
                        this.rolesLoading = false;
                    }
                },

                filterRolesUsers() {
                    let filtered = [...this.rolesUsersList];
                    
                    // Search filter
                    if (this.roleSearch) {
                        const search = this.roleSearch.toLowerCase();
                        filtered = filtered.filter(user => 
                            (user.name && user.name.toLowerCase().includes(search)) || 
                            (user.email && user.email.toLowerCase().includes(search))
                        );
                    }
                    
                    // Role filter
                    if (this.roleFilter) {
                        filtered = filtered.filter(user => user.role === this.roleFilter);
                    }
                    
                    // Municipality filter
                    if (this.municipalityFilter) {
                        filtered = filtered.filter(user => 
                            user.location && user.location.toLowerCase().includes(this.municipalityFilter.toLowerCase())
                        );
                    }
                    
                    // Status filter
                    if (this.statusFilter) {
                        filtered = filtered.filter(user => (user.status || 'Active') === this.statusFilter);
                    }
                    
                    this.filteredRolesUsersList = filtered;
                    this.rolesCurrentPage = 1;
                    this.paginateRolesUsers();
                },

                paginateRolesUsers() {
                    const start = (this.rolesCurrentPage - 1) * this.rolesPerPage;
                    const end = start + this.rolesPerPage;
                    this.paginatedRolesUsers = this.filteredRolesUsersList.slice(start, end);
                    this.rolesTotalPages = Math.max(1, Math.ceil(this.filteredRolesUsersList.length / this.rolesPerPage));
                },

                clearRoleFilters() {
                    this.roleSearch = '';
                    this.roleFilter = '';
                    this.municipalityFilter = '';
                    this.statusFilter = '';
                    this.filterRolesUsers();
                },

                async updateRoleForUser(user) {
                    if (user.role === user.newRole || user.is_superadmin) return;
                    
                    user.saving = true;
                    
                    try {
                        const response = await fetch(`{{ url('/admin/api/users') }}/${user.id}`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ role: user.newRole })
                        });

                        const data = await response.json();
                        
                        if (response.ok) {
                            user.role = user.newRole;
                            await this.fetchRolesUsers();
                            await this.fetchUsers();
                            alert('User role updated successfully!');
                        } else {
                            alert('Error updating role: ' + (data.message || data.error || 'Unknown error'));
                            user.newRole = user.role;
                        }
                    } catch (error) {
                        console.error('Error updating user role:', error);
                        alert('Error updating user role');
                        user.newRole = user.role;
                    } finally {
                        user.saving = false;
                    }
                },

                async openPermissionsModal(user) {
                    if (user.is_superadmin) return;
                    
                    this.selectedUserForPermissions = user;
                    this.showPermissionsModal = true;
                    this.loadingPermissions = true;
                    
                    try {
                        // Fetch permissions for the user's role
                        const response = await fetch(`{{ url('/admin/api/roles-permissions') }}/${user.role}`, {
                            credentials: 'same-origin',
                            headers: { 'Accept': 'application/json' }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.userPermissionsByCategory = data.permissions || {};
                        } else {
                            // If the specific endpoint doesn't exist, use default permissions
                            this.userPermissionsByCategory = this.getDefaultPermissions(user.role);
                        }
                    } catch (error) {
                        console.error('Error loading permissions:', error);
                        this.userPermissionsByCategory = this.getDefaultPermissions(user.role);
                    } finally {
                        this.loadingPermissions = false;
                    }
                },

                getDefaultPermissions(role) {
                    const basePermissions = {
                        'User Management': [
                            { permission: 'view_users', description: 'View user list', enabled: role !== 'Farmer' },
                            { permission: 'create_users', description: 'Create new users', enabled: role !== 'Farmer' },
                            { permission: 'edit_users', description: 'Edit user information', enabled: role !== 'Farmer' },
                            { permission: 'delete_users', description: 'Delete users', enabled: role === 'Admin' || role === 'DA Admin' }
                        ],
                        'Data Management': [
                            { permission: 'view_datasets', description: 'View datasets', enabled: true },
                            { permission: 'import_data', description: 'Import crop data', enabled: role !== 'Farmer' },
                            { permission: 'export_data', description: 'Export data', enabled: role !== 'Farmer' },
                            { permission: 'delete_datasets', description: 'Delete datasets', enabled: role === 'Admin' || role === 'DA Admin' }
                        ],
                        'Market & Prices': [
                            { permission: 'view_prices', description: 'View market prices', enabled: true },
                            { permission: 'edit_prices', description: 'Edit market prices', enabled: role !== 'Farmer' },
                            { permission: 'manage_announcements', description: 'Manage announcements', enabled: role !== 'Farmer' }
                        ],
                        'Reports & Analytics': [
                            { permission: 'view_reports', description: 'View reports', enabled: true },
                            { permission: 'generate_reports', description: 'Generate reports', enabled: role !== 'Farmer' },
                            { permission: 'view_analytics', description: 'View analytics dashboard', enabled: true }
                        ]
                    };
                    return basePermissions;
                },

                closePermissionsModal() {
                    this.showPermissionsModal = false;
                    this.selectedUserForPermissions = null;
                    this.userPermissionsByCategory = {};
                },

                toggleUserPermission(permission, enabled) {
                    Object.keys(this.userPermissionsByCategory).forEach(category => {
                        const perm = this.userPermissionsByCategory[category].find(p => p.permission === permission);
                        if (perm) {
                            perm.enabled = enabled;
                        }
                    });
                },

                selectAllPermissions() {
                    Object.keys(this.userPermissionsByCategory).forEach(category => {
                        this.userPermissionsByCategory[category].forEach(perm => {
                            perm.enabled = true;
                        });
                    });
                },

                deselectAllPermissions() {
                    Object.keys(this.userPermissionsByCategory).forEach(category => {
                        this.userPermissionsByCategory[category].forEach(perm => {
                            perm.enabled = false;
                        });
                    });
                },

                async saveUserPermissions() {
                    if (!this.selectedUserForPermissions) return;
                    
                    this.savingPermissions = true;
                    
                    const permissions = {};
                    Object.keys(this.userPermissionsByCategory).forEach(category => {
                        this.userPermissionsByCategory[category].forEach(perm => {
                            permissions[perm.permission] = perm.enabled;
                        });
                    });

                    try {
                        const response = await fetch(`{{ url('/admin/api/roles-permissions') }}/${this.selectedUserForPermissions.role}`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ permissions })
                        });

                        if (response.ok) {
                            alert('Permissions saved successfully!');
                            this.closePermissionsModal();
                        } else {
                            const data = await response.json();
                            alert('Error saving permissions: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error saving permissions:', error);
                        alert('Error saving permissions');
                    } finally {
                        this.savingPermissions = false;
                    }
                },

                formatPermissionName(permission) {
                    return permission.split('_').map(word => 
                        word.charAt(0).toUpperCase() + word.slice(1)
                    ).join(' ');
                },

                async fetchSystemLogs() {
                    try {
                        const response = await fetch('{{ url("/api/superadmin/logs") }}');
                        if (response.ok) {
                            this.systemLogs = await response.json();
                        }
                    } catch (error) {
                        console.error('Error fetching logs:', error);
                    }
                },

                async addUser() {
                    try {
                        const response = await fetch('{{ url("/api/superadmin/users") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.newUser)
                        });
                        
                        if (response.ok) {
                            await this.fetchUsers();
                            await this.fetchRolesUsers();
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
                            const response = await fetch(`{{ url('/api/superadmin/users') }}/${user.id}/role`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({ role: newRole })
                            });
                            
                            if (response.ok) {
                                await this.fetchUsers();
                                await this.fetchRolesUsers();
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
