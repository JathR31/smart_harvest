<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Users Management - SmartHarvest Admin</title>
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
<body class="bg-gray-50" x-data="usersManagement()">
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
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">OVERVIEW</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Admin Dashboard</span>
                    </a>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">USER MANAGEMENT</p>
                    <a href="{{ route('admin.users') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded">
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
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">DATA MANAGEMENT</p>
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
                    <p class="text-xs uppercase text-blue-300 mb-2 px-4">SYSTEM</p>
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
            <!-- Top Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center border-b">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-800">SmartHarvest Admin</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="px-3 py-2 border border-gray-300 rounded-lg flex items-center space-x-2 hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            <span class="text-sm">English</span>
                        </button>
                    </div>

                    <div class="relative">
                        <button class="relative p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" x-text="stats.pendingApproval"></span>
                        </button>
                    </div>

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

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Page Header with Add Button -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-green-700 mb-2">Users Management</h1>
                        <p class="text-gray-600">Manage all user accounts and permissions</p>
                    </div>
                    <button @click="showAddUserModal = true" class="flex items-center space-x-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>Add New User</span>
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Total Users</p>
                        <h3 class="text-3xl font-bold text-gray-800" x-text="stats.totalUsers"></h3>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Active Users</p>
                        <h3 class="text-3xl font-bold text-green-600" x-text="stats.activeUsers"></h3>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Pending Approval</p>
                        <h3 class="text-3xl font-bold text-yellow-600" x-text="stats.pendingApproval"></h3>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Suspended</p>
                        <h3 class="text-3xl font-bold text-red-600" x-text="stats.suspended"></h3>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <div class="relative">
                                <input type="text" x-model="searchQuery" @input="filterUsers()" placeholder="Search by name, email, or location..." class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <select x-model="filterRole" @change="filterUsers()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Roles</option>
                                <option value="Farmer">Farmer</option>
                                <option value="DA Admin">DA Admin</option>
                            </select>
                        </div>
                        <div>
                            <select x-model="filterStatus" @change="filterUsers()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Status</option>
                                <option value="Active">Active</option>
                                <option value="Pending Approval">Pending Approval</option>
                                <option value="Suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Full Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location/Dept</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Login</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <template x-for="user in paginatedUsers" :key="user.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-white font-semibold" :class="{
                                                'bg-green-500': user.role === 'Farmer',
                                                'bg-blue-500': user.role === 'DA Admin'
                                            }">
                                                <span x-text="user.name.charAt(0)"></span>
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
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" :class="{
                                            'bg-green-100 text-green-800': user.role === 'Farmer',
                                            'bg-blue-100 text-blue-800': user.role === 'DA Admin'
                                        }" x-text="user.role"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" :class="{
                                            'bg-green-100 text-green-800': user.status === 'Active',
                                            'bg-yellow-100 text-yellow-800': user.status === 'Pending Approval',
                                            'bg-red-100 text-red-800': user.status === 'Suspended'
                                        }" x-text="user.status"></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900" x-text="user.location || 'Not set'"></div>
                                        <div class="text-xs text-gray-500" x-show="user.farm_id" x-text="'ID: ' + user.farm_id"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="user.last_login"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <button @click="editUser(user)" class="text-blue-600 hover:text-blue-800" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button @click="toggleUserStatus(user)" class="text-yellow-600 hover:text-yellow-800" title="Toggle Status">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </button>
                                            <button @click="approveUser(user)" x-show="user.status === 'Pending Approval'" class="text-green-600 hover:text-green-800" title="Approve">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                            <button @click="deleteUser(user)" class="text-red-600 hover:text-red-800" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            Showing <span x-text="((currentPage - 1) * perPage) + 1"></span> to <span x-text="Math.min(currentPage * perPage, filteredUsers.length)"></span> of <span x-text="filteredUsers.length"></span> results
                        </div>
                        <div class="flex space-x-2">
                            <button @click="currentPage > 1 && currentPage--" :disabled="currentPage === 1" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                                Previous
                            </button>
                            <template x-for="page in totalPages" :key="page">
                                <button @click="currentPage = page" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100" :class="{'bg-blue-600 text-white border-blue-600': currentPage === page}" x-text="page"></button>
                            </template>
                            <button @click="currentPage < totalPages && currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add New User Modal -->
    <div x-show="showAddUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Add New User</h3>
                    <p class="text-sm text-gray-500 mt-1">Add a new user to the system with the necessary details.</p>
                </div>
                <button @click="showAddUserModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="addUser()">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" x-model="newUser.name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Juan Dela Cruz" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" x-model="newUser.email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="juan@example.com" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" x-model="newUser.password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="********" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" x-model="newUser.phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="09123456789">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select x-model="newUser.role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="Farmer">Farmer</option>
                                <option value="DA Admin">DA Admin (DA-CAR Officer)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select x-model="newUser.status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="Active">Active</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        
                        <!-- Role-specific section header -->
                        <div class="col-span-2 mt-4">
                            <div x-show="newUser.role === 'Farmer'" class="flex items-center space-x-2 mb-2">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Farm Information</span>
                            </div>
                            <div x-show="newUser.role === 'DA Admin'" class="flex items-center space-x-2 mb-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Office & Permissions</span>
                            </div>
                            <hr class="border-gray-200">
                        </div>
                        
                        <!-- Farmer-specific fields -->
                        <template x-if="newUser.role === 'Farmer'">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Municipality</label>
                                <select x-model="newUser.location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select Municipality</option>
                                    <option value="Atok, Benguet">Atok, Benguet</option>
                                    <option value="Bakun, Benguet">Bakun, Benguet</option>
                                    <option value="Bokod, Benguet">Bokod, Benguet</option>
                                    <option value="Buguias, Benguet">Buguias, Benguet</option>
                                    <option value="Itogon, Benguet">Itogon, Benguet</option>
                                    <option value="Kabayan, Benguet">Kabayan, Benguet</option>
                                    <option value="Kapangan, Benguet">Kapangan, Benguet</option>
                                    <option value="Kibungan, Benguet">Kibungan, Benguet</option>
                                    <option value="La Trinidad, Benguet">La Trinidad, Benguet</option>
                                    <option value="Mankayan, Benguet">Mankayan, Benguet</option>
                                    <option value="Sablan, Benguet">Sablan, Benguet</option>
                                    <option value="Tuba, Benguet">Tuba, Benguet</option>
                                    <option value="Tublay, Benguet">Tublay, Benguet</option>
                                </select>
                            </div>
                        </template>
                        <template x-if="newUser.role === 'Farmer'">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Farm Name</label>
                                <input type="text" x-model="newUser.farm_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Green Valley Farm">
                            </div>
                        </template>
                        <template x-if="newUser.role === 'Farmer'">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Farm Size (hectares)</label>
                                <input type="number" step="0.1" x-model="newUser.farm_size" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="2.5">
                            </div>
                        </template>
                        <template x-if="newUser.role === 'Farmer'">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Crop</label>
                                <select x-model="newUser.primary_crop" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select Primary Crop</option>
                                    <option value="Rice">Rice</option>
                                    <option value="Corn">Corn</option>
                                    <option value="Vegetables">Vegetables</option>
                                    <option value="Root Crops">Root Crops</option>
                                    <option value="Fruits">Fruits</option>
                                    <option value="Coffee">Coffee</option>
                                </select>
                            </div>
                        </template>
                        
                        <!-- DA Admin-specific fields -->
                        <template x-if="newUser.role === 'DA Admin'">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Office/Unit</label>
                                <select x-model="newUser.office" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select Office/Unit</option>
                                    <option value="DA-CAR Regional Office">DA-CAR Regional Office</option>
                                    <option value="Provincial Agriculture Office - Benguet">Provincial Agriculture Office - Benguet</option>
                                    <option value="Municipal Agriculture Office">Municipal Agriculture Office</option>
                                    <option value="Research Division">Research Division</option>
                                    <option value="Extension Services Division">Extension Services Division</option>
                                    <option value="Regulatory Division">Regulatory Division</option>
                                    <option value="Planning & Monitoring Division">Planning & Monitoring Division</option>
                                </select>
                            </div>
                        </template>
                        <template x-if="newUser.role === 'DA Admin'">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Position/Designation</label>
                                <input type="text" x-model="newUser.position" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Agricultural Technologist">
                            </div>
                        </template>
                        <template x-if="newUser.role === 'DA Admin'">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID</label>
                                <input type="text" x-model="newUser.employee_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="DA-CAR-2025-001">
                            </div>
                        </template>
                        <template x-if="newUser.role === 'DA Admin'">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Area of Coverage</label>
                                <select x-model="newUser.location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select Coverage Area</option>
                                    <option value="CAR-Wide">CAR-Wide (All Provinces)</option>
                                    <option value="Benguet Province">Benguet Province</option>
                                    <option value="Abra Province">Abra Province</option>
                                    <option value="Apayao Province">Apayao Province</option>
                                    <option value="Ifugao Province">Ifugao Province</option>
                                    <option value="Kalinga Province">Kalinga Province</option>
                                    <option value="Mountain Province">Mountain Province</option>
                                </select>
                            </div>
                        </template>
                        
                        <!-- Permissions for DA Admin -->
                        <template x-if="newUser.role === 'DA Admin'">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Permissions</label>
                                <div class="grid grid-cols-2 gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" x-model="newUser.permissions.manage_users" class="rounded text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Manage Users</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" x-model="newUser.permissions.view_reports" class="rounded text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">View Reports</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" x-model="newUser.permissions.manage_datasets" class="rounded text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Manage Datasets</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" x-model="newUser.permissions.export_data" class="rounded text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Export Data</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" x-model="newUser.permissions.manage_forecasts" class="rounded text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Manage Forecasts</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" x-model="newUser.permissions.system_settings" class="rounded text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">System Settings</span>
                                    </label>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showAddUserModal = false" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <span x-show="!saving">Add User</span>
                            <span x-show="saving">Adding...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function usersManagement() {
            return {
                users: [],
                filteredUsers: [],
                paginatedUsers: [],
                stats: {
                    totalUsers: 0,
                    activeUsers: 0,
                    pendingApproval: 0,
                    suspended: 0
                },
                searchQuery: '',
                filterRole: '',
                filterStatus: '',
                currentPage: 1,
                perPage: 10,
                totalPages: 1,
                showAddUserModal: false,
                saving: false,
                newUser: {
                    name: '',
                    email: '',
                    password: '',
                    phone: '',
                    role: 'Farmer',
                    status: 'Active',
                    location: '',
                    // Farmer-specific fields
                    farm_name: '',
                    farm_size: '',
                    primary_crop: '',
                    // DA Admin-specific fields
                    office: '',
                    position: '',
                    employee_id: '',
                    permissions: {
                        manage_users: false,
                        view_reports: true,
                        manage_datasets: false,
                        export_data: true,
                        manage_forecasts: false,
                        system_settings: false
                    }
                },

                async init() {
                    await this.loadUsers();
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
                        this.users = data.users || [];
                        this.stats = data.stats || {
                            totalUsers: 0,
                            activeUsers: 0,
                            pendingApproval: 0,
                            suspended: 0
                        };
                        
                        this.filterUsers();
                    } catch (error) {
                        console.error('Error loading users:', error);
                        alert('Error loading users. Please check console for details.');
                    }
                },

                filterUsers() {
                    this.filteredUsers = this.users.filter(user => {
                        const matchesSearch = !this.searchQuery || 
                            user.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            user.email.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            (user.location && user.location.toLowerCase().includes(this.searchQuery.toLowerCase()));
                        
                        const matchesRole = !this.filterRole || user.role === this.filterRole;
                        const matchesStatus = !this.filterStatus || user.status === this.filterStatus;
                        
                        return matchesSearch && matchesRole && matchesStatus;
                    });
                    
                    this.totalPages = Math.ceil(this.filteredUsers.length / this.perPage);
                    this.currentPage = 1;
                    this.updatePagination();
                },

                updatePagination() {
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + this.perPage;
                    this.paginatedUsers = this.filteredUsers.slice(start, end);
                },

                async addUser() {
                    this.saving = true;
                    
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
                            alert('User created successfully!');
                            this.showAddUserModal = false;
                            this.resetNewUserForm();
                            await this.loadUsers();
                        } else {
                            alert('Error creating user: ' + (data.message || data.error || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error creating user:', error);
                        alert('Error creating user. Please check console for details.');
                    } finally {
                        this.saving = false;
                    }
                },

                resetNewUserForm() {
                    this.newUser = {
                        name: '',
                        email: '',
                        password: '',
                        phone: '',
                        role: 'Farmer',
                        status: 'Active',
                        location: '',
                        // Farmer-specific fields
                        farm_name: '',
                        farm_size: '',
                        primary_crop: '',
                        // DA Admin-specific fields
                        office: '',
                        position: '',
                        employee_id: '',
                        permissions: {
                            manage_users: false,
                            view_reports: true,
                            manage_datasets: false,
                            export_data: true,
                            manage_forecasts: false,
                            system_settings: false
                        }
                    };
                },

                editUser(user) {
                    // TODO: Implement edit functionality
                    alert('Edit user: ' + user.name);
                },

                async toggleUserStatus(user) {
                    const newStatus = user.status === 'Active' ? 'Suspended' : 'Active';
                    
                    if (!confirm(`Are you sure you want to ${newStatus === 'Suspended' ? 'suspend' : 'activate'} ${user.name}?`)) {
                        return;
                    }
                    
                    try {
                        const response = await fetch(`{{ url('/admin/api/users') }}/${user.id}`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ status: newStatus })
                        });

                        const data = await response.json();
                        
                        if (response.ok) {
                            user.status = newStatus;
                            await this.loadUsers();
                            alert('User status updated successfully!');
                        } else {
                            alert('Error updating user status: ' + (data.message || data.error || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error updating user status:', error);
                        alert('Error updating user status');
                    }
                },

                async approveUser(user) {
                    if (!confirm(`Approve user: ${user.name}?`)) {
                        return;
                    }
                    
                    try {
                        const response = await fetch(`{{ url('/admin/api/users') }}/${user.id}`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ status: 'Active' })
                        });

                        const data = await response.json();
                        
                        if (response.ok) {
                            user.status = 'Active';
                            await this.loadUsers();
                            alert('User approved successfully!');
                        } else {
                            alert('Error approving user: ' + (data.message || data.error || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error approving user:', error);
                        alert('Error approving user');
                    }
                },

                async deleteUser(user) {
                    if (!confirm(`Are you sure you want to delete ${user.name}? This action cannot be undone.`)) {
                        return;
                    }
                    
                    try {
                        const response = await fetch(`{{ url('/admin/api/users') }}/${user.id}`, {
                            method: 'DELETE',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();
                        
                        if (response.ok) {
                            await this.loadUsers();
                            alert('User deleted successfully!');
                        } else {
                            alert('Error deleting user: ' + (data.message || data.error || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error deleting user:', error);
                        alert('Error deleting user');
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
