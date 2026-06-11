<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Management - SmartHarvest DA Officer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden" x-data="usersApp()" x-init="init()">
        <!-- Sidebar -->
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
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}?section=market-prices" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Market Prices</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}?section=announcements" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Announcements</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}?section=inbox" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Inbox</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Weather</p>
                    <a href="{{ route('admin.forecast') }}" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                        </svg>
                        <span>Forecast</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">User Management</p>
                    <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 px-4 py-3 rounded bg-green-600">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                        <span>Crop Monitoring</span>
                    </a>
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
                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded hover:bg-red-600 transition-colors text-left">
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
            <!-- Top Header -->
        <header class="bg-white border-b px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">User Management</h1>
                    <p class="text-sm text-gray-600">Manage user accounts and permissions</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr(Auth::user()->name ?? 'D', 0, 1) }}
                        </div>
                        <span class="text-sm text-gray-700">{{ Auth::user()->name ?? 'DA Officer' }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Users</p>
                            <h3 class="text-3xl font-bold text-gray-800" x-text="stats.total">0</h3>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Farmers</p>
                            <h3 class="text-3xl font-bold text-green-800" x-text="stats.farmers">0</h3>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">DA Admins</p>
                            <h3 class="text-3xl font-bold text-blue-800" x-text="stats.daAdmins">0</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Active Users</p>
                            <h3 class="text-3xl font-bold text-green-800" x-text="stats.active">0</h3>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">All Users</h3>
                            <p class="text-sm text-gray-500 mt-1">Total: <span x-text="users.length">0</span> users</p>
                        </div>
                        <div class="flex space-x-3">
                                <input type="text" x-model="searchQuery" placeholder="Search users..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <button @click="showAddUserModal = true" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add User</button>
                                <button @click="showImportModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Import Users</button>
                                <button @click="deleteAllFarmersConfirm()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete All Farmers</button>
                            <select x-model="roleFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">All Roles</option>
                                <option value="Farmer">Farmer</option>
                                <option value="DA Admin">DA Admin</option>
                                <option value="Admin">Admin</option>                                <option value="Superadmin">Superadmin</option>                            </select>
                        </div>
                    </div>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RSBSA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-if="loading">
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <svg class="animate-spin w-12 h-12 mx-auto mb-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-gray-500">Loading users...</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="!loading" x-for="user in filteredUsers" :key="user.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-white" x-text="user.name.substring(0, 2).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800" x-text="user.name"></p>
                                            <p class="text-xs text-gray-500" x-text="user.email"></p>
                                            <p class="text-xs text-gray-400" x-text="user.rsbsa_number ? ('RSBSA: ' + user.rsbsa_number) : ''"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600" x-text="user.rsbsa_number || '-' "></td>
                                <td class="px-6 py-4">
                                    <span :class="{
                                        'bg-green-100 text-green-800': user.role === 'Farmer',
                                        'bg-blue-100 text-blue-800': user.role === 'DA Admin' || user.role === 'Admin',
                                        'bg-purple-100 text-purple-800': user.role === 'Superadmin'
                                    }" class="px-3 py-1 text-xs font-medium rounded-full" x-text="user.role"></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600" x-text="user.location || 'N/A'"></td>
                                <td class="px-6 py-4">
                                    <span :class="{
                                        'bg-green-100 text-green-800': user.status === 'Active',
                                        'bg-yellow-100 text-yellow-800': user.status === 'Pending',
                                        'bg-red-100 text-red-800': user.status === 'Suspended' || user.status === 'Archived',
                                        'bg-gray-100 text-gray-700': user.status === 'Inactive'
                                    }" class="px-3 py-1 text-xs font-medium rounded-full" x-text="user.status || 'Pending'"></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600" x-text="new Date(user.created_at).toLocaleDateString()"></td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex space-x-2">
                                        <button @click="editUser(user)" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</button>
                                        <button x-show="user.role === 'Farmer'" @click="archiveUser(user)" :class="{'bg-gray-500': user.status === 'Archived', 'bg-orange-500': user.status !== 'Archived'}" class="px-3 py-1 text-white text-xs rounded hover:opacity-90" x-text="user.status === 'Archived' ? 'Restore' : 'Archive'"></button>
                                        <button x-show="user.role === 'Farmer'" @click="deleteUserConfirm(user.id, user.name)" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="!loading && filteredUsers.length === 0">
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-gray-500">No users found</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Edit User Modal -->
    <div x-show="showEditUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50 p-0 sm:p-4" x-cloak>
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full sm:max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Edit User</h2>
                <button @click="showEditUserModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" x-model="editingUser.name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" x-model="editingUser.email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">RSBSA Number</label>
                    <input type="text" x-model="editingUser.rsbsa_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="e.g. 4-11-10-001-00045">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="tel" x-model="editingUser.phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" x-model="editingUser.location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select x-model="editingUser.status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="showEditUserModal = false" class="px-4 py-2 border border-gray-300 rounded-lg">Cancel</button>
                <button @click="updateUser()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div x-show="showAddUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50 p-0 sm:p-4" x-cloak>
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full sm:max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Add New User</h3>
                </div>
                <button @click="showAddUserModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" x-model="newUser.name" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" x-model="newUser.email" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" x-model="newUser.password" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RSBSA Number</label>
                        <input type="text" x-model="newUser.rsbsa_number" class="w-full px-4 py-2 border rounded-lg" placeholder="4-11-10-001-00045">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select x-model="newUser.role" class="w-full px-4 py-2 border rounded-lg">
                            <option value="Farmer">Farmer</option>
                            <option value="DA Admin">DA Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Municipality</label>
                        <input type="text" x-model="newUser.location" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="showAddUserModal = false" class="px-4 py-2 border rounded-lg">Cancel</button>
                    <button @click="addUser()" class="px-4 py-2 bg-green-600 text-white rounded-lg">Add User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div x-show="showImportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50 p-0 sm:p-4" x-cloak>
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full sm:max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Import Users</h3>
                    <p class="text-sm text-gray-500">Upload Excel or CSV with RSBSA numbers. Include a dedicated RSBSA Number / Reference Number column for each farmer.</p>
                </div>
                <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <div class="p-6">
                <input x-ref="importFileInput" type="file" accept=".csv, .xls, .xlsx" @change="importFile = $event.target.files[0]">
                <div class="mt-3 text-sm text-gray-500" x-show="importFile">
                    Selected file: <span class="font-medium text-gray-700" x-text="importFile ? importFile.name : ''"></span>
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button @click="clearImportFile()" class="px-4 py-2 border rounded-lg" x-show="importFile">Remove CSV</button>
                    <button @click="showImportModal = false" class="px-4 py-2 border rounded-lg">Cancel</button>
                    <button @click="uploadUsersImport()" class="px-4 py-2 bg-blue-600 text-white rounded-lg ml-2">Upload & Import</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function usersApp() {
            return {
                users: [],
                filteredUsers: [],
                searchQuery: '',
                roleFilter: '',
                loading: true,
                showAddUserModal: false,
                showImportModal: false,
                importFile: null,
                importResult: null,
                showEditUserModal: false,
                editingUser: {
                    id: null,
                    name: '',
                    email: '',
                    phone: '',
                    rsbsa_number: '',
                    location: '',
                    status: 'Active'
                },
                newUser: {
                    name: '',
                    email: '',
                    password: '',
                    rsbsa_number: '',
                    phone: '',
                    role: 'Farmer',
                    status: 'Active',
                    location: ''
                },
                stats: {
                    total: 0,
                    farmers: 0,
                    daAdmins: 0,
                    active: 0
                },

                async init() {
                    await this.loadUsers();
                    this.$watch('searchQuery', () => this.filterUsers());
                    this.$watch('roleFilter', () => this.filterUsers());
                },

                async loadUsers() {
                    try {
                        this.loading = true;
                        const response = await fetch('{{ url("/api/admin/users") }}');
                        if (!response.ok) {
                            throw new Error('Failed to fetch users');
                        }
                        const data = await response.json();
                        this.users = data.users || [];
                        this.filteredUsers = this.users;
                        this.calculateStats();
                    } catch (error) {
                        console.error('Error loading users:', error);
                        alert('Failed to load users. Please refresh the page.');
                    } finally {
                        this.loading = false;
                    }
                },

                calculateStats() {
                    this.stats.total = this.users.length;
                    this.stats.farmers = this.users.filter(u => u.role === 'Farmer').length;
                    this.stats.daAdmins = this.users.filter(u => ['DA Admin', 'Admin', 'Superadmin'].includes(u.role)).length;
                    this.stats.active = this.users.filter(u => u.status === 'Active').length;
                },

                filterUsers() {
                    const query = this.searchQuery.trim().toLowerCase();
                    this.filteredUsers = this.users.filter(user => {
                        const matchesSearch = !query || 
                            user.name.toLowerCase().includes(query) ||
                            user.email.toLowerCase().includes(query) ||
                            (user.location && user.location.toLowerCase().includes(query)) ||
                            (user.rsbsa_number && user.rsbsa_number.toLowerCase().includes(query));
                        const matchesRole = !this.roleFilter || user.role === this.roleFilter;
                        return matchesSearch && matchesRole;
                    });
                },

                resetNewUser() {
                    this.newUser = {
                        name: '',
                        email: '',
                        password: '',
                        rsbsa_number: '',
                        phone: '',
                        role: 'Farmer',
                        status: 'Active',
                        location: ''
                    };
                },

                async addUser() {
                    try {
                        const response = await fetch('{{ route("admin.api.users.create") }}', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.newUser)
                        });
                        const data = await response.json();
                        if (response.ok) {
                            alert('User created successfully');
                            this.showAddUserModal = false;
                            this.resetNewUser();
                            await this.loadUsers();
                        } else {
                            const message = data.message || data.error || JSON.stringify(data.errors || data);
                            alert('Error creating user: ' + message);
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Failed to create user. See console for details.');
                    }
                },

                editUser(user) {
                    this.editingUser = {
                        id: user.id,
                        name: user.name,
                        email: user.email,
                        phone: user.phone || '',
                        rsbsa_number: user.rsbsa_number || '',
                        location: user.location || '',
                        status: user.status || 'Active'
                    };
                    this.showEditUserModal = true;
                },

                clearImportFile() {
                    this.importFile = null;
                    if (this.$refs.importFileInput) {
                        this.$refs.importFileInput.value = '';
                    }
                },

                async updateUser() {
                    try {
                        const response = await fetch(`{{ url('/admin/api/users') }}/${this.editingUser.id}`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.editingUser)
                        });
                        const data = await response.json();
                        if (response.ok) {
                            alert('User updated successfully');
                            this.showEditUserModal = false;
                            await this.loadUsers();
                        } else {
                            alert('Error updating user: ' + (data.message || data.error || 'Unknown error'));
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Failed to update user');
                    }
                },

                async archiveUser(user) {
                    if (!user) return;
                    const action = user.status === 'Archived' ? 'restore' : 'archive';
                    if (!confirm(`Are you sure you want to ${action} this user?`)) return;
                    try {
                        const response = await fetch(`{{ url('/admin/api/users') }}/${user.id}`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ archived: user.status !== 'Archived' })
                        });
                        if (response.ok) {
                            alert(`User ${action}d successfully`);
                            await this.loadUsers();
                        } else {
                            alert(`Error ${action}ing user`);
                        }
                    } catch (e) {
                        console.error(e);
                        alert(`Failed to ${action} user`);
                    }
                },

                deleteUserConfirm(userId, userName) {
                    if (!confirm(`Are you sure you want to delete ${userName}? This action cannot be undone.`)) return;
                    this.deleteUser(userId);
                },

                async deleteUser(userId) {
                    try {
                        const response = await fetch(`{{ url('/admin/api/users') }}/${userId}`, {
                            method: 'DELETE',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        if (response.ok) {
                            alert('User deleted successfully');
                            await this.loadUsers();
                        } else {
                            alert('Error deleting user');
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Failed to delete user');
                    }
                },

                deleteAllFarmersConfirm() {
                    const farmerCount = this.users.filter(u => u.role === 'Farmer').length;
                    if (farmerCount === 0) {
                        alert('No farmers to delete');
                        return;
                    }
                    const confirmed = confirm(`Are you sure you want to DELETE ALL ${farmerCount} farmers? This action cannot be undone.`);
                    if (confirmed) {
                        this.deleteAllFarmers();
                    }
                },

                async deleteAllFarmers() {
                    try {
                        const response = await fetch('{{ url("/admin/api/users/farmers/bulk-delete") }}', {
                            method: 'DELETE',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            alert(`Successfully deleted ${data.deleted || 0} farmers`);
                            await this.loadUsers();
                        } else {
                            alert('Error deleting farmers');
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Failed to delete farmers');
                    }
                },

                deleteUploadedCsv() {
                    this.clearImportFile();
                    alert('CSV file removed');
                },

                async uploadUsersImport() {
                    if (!this.importFile) {
                        alert('Please select an Excel or CSV file first');
                        return;
                    }
                    const form = new FormData();
                    form.append('file', this.importFile);
                    try {
                        console.log('Starting import for file:', this.importFile.name);
                        const response = await fetch('{{ route("admin.api.users.import") }}', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: form
                        });
                        console.log('Response status:', response.status);
                        const data = await response.json();
                        console.log('Response data:', data);
                        this.importResult = data;
                        if (response.ok) {
                            const imported = data.results?.imported || 0;
                            const skipped = data.results?.skipped || 0;
                            const message = `Import completed!\n- Imported: ${imported} users\n- Skipped: ${skipped} users` + (data.results?.errors?.length ? `\n- Errors: ${data.results.errors.length}` : '');
                            this.importedFilePath = this.importFile.name;
                            alert(message);
                            this.showImportModal = false;
                            this.importFile = null;
                            await this.loadUsers();
                        } else {
                            const message = data.error || JSON.stringify(data.errors || data);
                            console.error('Import error:', message);
                            alert('Import failed: ' + message);
                        }
                    } catch (e) {
                        console.error('Import error:', e);
                        alert('Import failed. See console for details.');
                    }
                }
            };
        }
    </script>
</body>
</html>
