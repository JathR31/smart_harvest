<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles & Permissions - SmartHarvest Admin</title>
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
    </style>
</head>
<body class="bg-gray-50" x-data="rolesPermissionsApp()" x-init="init()">
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
                        <p class="text-xs text-blue-200">Superadmin</p>
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
                    <a href="{{ route('admin.roles') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded">
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
                    <a href="{{ route('admin.monitoring') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Monitoring</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="sidebar-item" onsubmit="sessionStorage.setItem('isLoggedOut','true');">
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
                        <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                        </svg>
                        <h1 class="text-xl font-semibold text-gray-800">SmartHarvest Admin</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search..." 
                                   class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Roles & Permissions</h2>
                    <p class="text-gray-600 mt-1">Define what each role can do in the system</p>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
                </div>

                <!-- Roles Grid -->
                <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <template x-for="role in roles" :key="role.name">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div :class="role.color + ' p-3 rounded-lg'">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-1" x-text="role.name"></h3>
                            <p class="text-sm text-gray-600 mb-4">
                                <span x-text="role.user_count"></span> users
                            </p>
                            <p class="text-sm text-gray-600 mb-4" x-text="role.description"></p>
                            <div class="mb-4">
                                <span class="text-sm font-medium text-gray-700" x-text="role.permission_count + ' permissions'"></span><br>
                                <span class="text-xs text-gray-500">assigned</span>
                            </div>
                            <button @click="openPermissionModal(role.name)" 
                                    class="w-full py-2 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Permissions
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Permissions Overview Section -->
                <div x-show="!loading" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Permissions Overview</h3>
                    <p class="text-sm text-gray-500 mb-6">Select a role above to view and modify its permissions</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Profile Management -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Profile Management</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>View own farm profile</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Edit own farm profile</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>View other farmers' profiles</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Edit other farmers' profiles</li>
                            </ul>
                        </div>
                        
                        <!-- Reporting -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Reporting</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Submit crop planting report</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Submit harvest yield data</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Report pest/disease outbreak</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>View all system reports</li>
                            </ul>
                        </div>
                        
                        <!-- Services -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Services</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Apply for subsidies or loans</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>View status of applications</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Approve subsidy applications</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Manage all services</li>
                            </ul>
                        </div>
                        
                        <!-- Data Access -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Data Access</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>View market price dashboard</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>View weather alerts</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Access system-wide aggregated data</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Export data reports</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Manage data datasets</li>
                            </ul>
                        </div>
                        
                        <!-- User Management -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">User Management</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Access the "Users" page</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Access the "Roles & Permissions" page</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Create new users</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Delete users</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Assign roles to users</li>
                            </ul>
                        </div>
                        
                        <!-- System Administration -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">System Administration</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Modify system settings</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>View system logs</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Manage system notifications</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Backup and restore data</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- About Roles & Permissions -->
                <div x-show="!loading" class="bg-blue-50 rounded-xl border border-blue-200 p-6 mb-8">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-blue-800 mb-2">About Roles & Permissions</h4>
                            <ul class="space-y-1 text-sm text-blue-700">
                                <li>• Roles define groups of permissions that can be assigned to users. Each role determines what actions users can perform in the system.</li>
                                <li>• Users inherit all permissions from their assigned role</li>
                                <li>• Changes to role permissions affect all users with that role</li>
                                <li>• Admin role has full system access by default</li>
                                <li>• Be careful when modifying permissions for roles with many users</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- User Role Assignment Section -->
                <div x-show="!loading" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">User Role Assignment</h3>
                            <p class="text-sm text-gray-500">Change user roles between Farmer, Admin, DA-CAR Officer, etc.</p>
                        </div>
                        <button @click="loadUsers()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                    
                    <!-- Search -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" x-model="userSearch" @input="filterUsersList()" 
                                   placeholder="Search users by name or email..." 
                                   class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Current Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Change Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="user in filteredUsersList" :key="user.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold" 
                                                     :class="{
                                                        'bg-green-500': user.role === 'Farmer',
                                                        'bg-blue-500': user.role === 'Field Agent',
                                                        'bg-purple-500': user.role === 'Admin',
                                                        'bg-orange-500': user.role === 'Researcher'
                                                     }">
                                                    <span x-text="user.name.charAt(0).toUpperCase()"></span>
                                                </div>
                                                <span class="ml-3 text-sm font-medium text-gray-900" x-text="user.name"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600" x-text="user.email"></td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                                                  :class="{
                                                    'bg-green-100 text-green-800': user.role === 'Farmer',
                                                    'bg-blue-100 text-blue-800': user.role === 'Field Agent',
                                                    'bg-purple-100 text-purple-800': user.role === 'Admin',
                                                    'bg-orange-100 text-orange-800': user.role === 'Researcher'
                                                  }" 
                                                  x-text="user.role"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <select x-model="user.newRole" 
                                                    class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                                <option value="Farmer">Farmer</option>
                                                <option value="DA Admin">DA Admin</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button @click="updateUserRole(user)" 
                                                    :disabled="user.role === user.newRole || user.saving"
                                                    class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center">
                                                <svg x-show="user.saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span x-text="user.saving ? 'Saving...' : 'Update'"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="filteredUsersList.length === 0">
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        <p>No users found</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Permission Modal -->
                <div x-show="showModal" 
                     x-cloak
                     @click.away="closeModal()"
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-black opacity-50"></div>
                        
                        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                            <!-- Modal Header -->
                            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-semibold text-white" x-text="'Edit Permissions - ' + selectedRole"></h3>
                                    <p class="text-green-100 text-sm mt-1">Manage what this role can access</p>
                                </div>
                                <button @click="closeModal()" class="text-white hover:text-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                                <template x-for="(perms, category) in permissionsByCategory" :key="category">
                                    <div class="mb-6">
                                        <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                            <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span>
                                            <span x-text="category"></span>
                                        </h4>
                                        <div class="space-y-2 ml-4">
                                            <template x-for="perm in perms" :key="perm.permission">
                                                <label class="flex items-start p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                                    <input type="checkbox" 
                                                           :checked="perm.enabled"
                                                           @change="togglePermission(perm.permission, $event.target.checked)"
                                                           class="mt-1 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                                    <div class="ml-3">
                                                        <div class="font-medium text-gray-800" x-text="formatPermission(perm.permission)"></div>
                                                        <div class="text-sm text-gray-600" x-text="perm.description"></div>
                                                    </div>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Modal Footer -->
                            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                                <button @click="closeModal()" 
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                                    Cancel
                                </button>
                                <button @click="savePermissions()" 
                                        :disabled="saving"
                                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 flex items-center">
                                    <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function rolesPermissionsApp() {
            return {
                loading: true,
                roles: [],
                showModal: false,
                selectedRole: '',
                permissionsByCategory: {},
                saving: false,
                
                // User role assignment
                usersList: [],
                filteredUsersList: [],
                userSearch: '',

                async init() {
                    await this.loadRoles();
                    await this.loadUsers();
                },

                async loadRoles() {
                    try {
                        const response = await fetch('{{ route("admin.api.roles") }}', {
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error('Failed to load roles');
                        }
                        
                        const data = await response.json();
                        this.roles = data.roles;
                        this.loading = false;
                    } catch (error) {
                        console.error('Error loading roles:', error);
                        this.loading = false;
                    }
                },
                
                async loadUsers() {
                    try {
                        const response = await fetch('{{ route("admin.api.users") }}', {
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error('Failed to load users');
                        }
                        
                        const data = await response.json();
                        this.usersList = data.users.map(user => ({
                            ...user,
                            newRole: user.role,
                            saving: false
                        }));
                        this.filteredUsersList = [...this.usersList];
                    } catch (error) {
                        console.error('Error loading users:', error);
                    }
                },
                
                filterUsersList() {
                    const search = this.userSearch.toLowerCase();
                    this.filteredUsersList = this.usersList.filter(user => 
                        user.name.toLowerCase().includes(search) || 
                        user.email.toLowerCase().includes(search)
                    );
                },
                
                async updateUserRole(user) {
                    if (user.role === user.newRole) return;
                    
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
                            // Reload roles to update user counts
                            await this.loadRoles();
                            alert('User role updated successfully!');
                        } else {
                            alert('Error updating role: ' + (data.message || data.error || 'Unknown error'));
                            user.newRole = user.role; // Reset selection
                        }
                    } catch (error) {
                        console.error('Error updating user role:', error);
                        alert('Error updating user role');
                        user.newRole = user.role; // Reset selection
                    } finally {
                        user.saving = false;
                    }
                },

                async openPermissionModal(roleName) {
                    this.selectedRole = roleName;
                    this.showModal = true;
                    
                    try {
                        const response = await fetch(`{{ url('/admin/api/roles-permissions') }}/${roleName}`, {
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error('Failed to load permissions');
                        }
                        
                        const data = await response.json();
                        this.permissionsByCategory = data.permissions;
                    } catch (error) {
                        console.error('Error loading permissions:', error);
                    }
                },

                closeModal() {
                    this.showModal = false;
                    this.selectedRole = '';
                    this.permissionsByCategory = {};
                },

                togglePermission(permission, enabled) {
                    // Update local state
                    Object.keys(this.permissionsByCategory).forEach(category => {
                        const perm = this.permissionsByCategory[category].find(p => p.permission === permission);
                        if (perm) {
                            perm.enabled = enabled;
                        }
                    });
                },

                async savePermissions() {
                    this.saving = true;
                    
                    // Collect all permissions and their states
                    const permissions = {};
                    Object.keys(this.permissionsByCategory).forEach(category => {
                        this.permissionsByCategory[category].forEach(perm => {
                            permissions[perm.permission] = perm.enabled;
                        });
                    });

                    try {
                        const response = await fetch(`{{ url('/admin/api/roles-permissions') }}/${this.selectedRole}`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ permissions })
                        });

                        const data = await response.json();
                        
                        if (response.ok) {
                            // Reload roles to update counts
                            await this.loadRoles();
                            this.closeModal();
                        } else {
                            alert('Error saving permissions: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error saving permissions:', error);
                        alert('Error saving permissions');
                    } finally {
                        this.saving = false;
                    }
                },

                formatPermission(permission) {
                    return permission.split('_').map(word => 
                        word.charAt(0).toUpperCase() + word.slice(1)
                    ).join(' ');
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
