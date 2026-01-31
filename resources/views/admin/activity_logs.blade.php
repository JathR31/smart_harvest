<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item:hover { background: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background: rgba(255, 255, 255, 0.15); border-left: 3px solid white; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50" x-data="activityLogsPage()" x-init="init()">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Purple theme for Superadmin -->
        <aside class="w-64 bg-gradient-to-b from-purple-800 to-purple-950 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-6 border-b border-purple-600">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <span class="text-xl">🌾</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SmartHarvest</h1>
                        <p class="text-xs text-purple-200">Superadmin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-1">
                <div class="mb-4">
                    <p class="text-xs uppercase text-purple-300 mb-2 px-4">Overview</p>
                    <a href="{{ route('superadmin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-purple-300 mb-2 px-4">User Management</p>
                    <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.roles') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Roles & Permissions</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-purple-300 mb-2 px-4">System</p>
                    <a href="{{ route('admin.monitoring') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>System Monitoring</span>
                    </a>
                    <a href="{{ route('admin.logs') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Activity Logs</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-purple-300 mb-2 px-4">View As</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>DA Admin View</span>
                    </a>
                    <a href="{{ route('farmer.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
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
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-lg font-semibold text-gray-800">Activity Logs</span>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-full">System Audit</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
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
                <!-- Header and Filter -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">System Activity Logs</h1>
                        <p class="text-gray-500">Track all system activities including logins, uploads, and changes</p>
                    </div>
                    <button @click="exportLogs()" class="flex items-center space-x-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Export Logs</span>
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Logins Today</p>
                                <p class="text-2xl font-bold text-gray-800" x-text="stats.loginsToday"></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Uploads Today</p>
                                <p class="text-2xl font-bold text-gray-800" x-text="stats.uploadsToday"></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Failed Logins</p>
                                <p class="text-2xl font-bold text-gray-800" x-text="stats.failedLogins"></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Logs</p>
                                <p class="text-2xl font-bold text-gray-800" x-text="stats.totalLogs"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <div>
                            <label class="text-sm text-gray-500">Activity Type</label>
                            <select x-model="filters.type" @change="filterLogs()" class="block mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="all">All Activities</option>
                                <option value="login">Logins</option>
                                <option value="logout">Logouts</option>
                                <option value="upload">Data Uploads</option>
                                <option value="create">Record Created</option>
                                <option value="update">Record Updated</option>
                                <option value="delete">Record Deleted</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">User Role</label>
                            <select x-model="filters.role" @change="filterLogs()" class="block mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="all">All Roles</option>
                                <option value="superadmin">Superadmin</option>
                                <option value="da_admin">DA Admin</option>
                                <option value="farmer">Farmer</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Date Range</label>
                            <select x-model="filters.dateRange" @change="filterLogs()" class="block mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="today">Today</option>
                                <option value="week">Last 7 Days</option>
                                <option value="month">Last 30 Days</option>
                                <option value="all">All Time</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="text-sm text-gray-500">Search</label>
                            <input type="text" x-model="filters.search" @input="filterLogs()" placeholder="Search by user, action, or IP..." class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                </div>

                <!-- Logs Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <template x-for="log in filteredLogs" :key="log.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="log.timestamp"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm" :class="log.roleColor" x-text="log.user.charAt(0)"></div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-800" x-text="log.user"></p>
                                                    <p class="text-xs text-gray-500" x-text="log.role"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium" :class="log.typeClass" x-text="log.type"></span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="log.details"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono" x-text="log.ip"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium" :class="log.status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" x-text="log.status"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <p class="text-sm text-gray-500">Showing <span x-text="filteredLogs.length"></span> of <span x-text="logs.length"></span> logs</p>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-sm">Previous</button>
                            <button class="px-3 py-1 bg-purple-600 text-white rounded text-sm">1</button>
                            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-sm">2</button>
                            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-sm">3</button>
                            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-sm">Next</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function activityLogsPage() {
            return {
                filters: {
                    type: 'all',
                    role: 'all',
                    dateRange: 'today',
                    search: ''
                },
                stats: {
                    loginsToday: 24,
                    uploadsToday: 8,
                    failedLogins: 2,
                    totalLogs: 1547
                },
                logs: [
                    { id: 1, timestamp: '2026-01-29 10:45:23', user: 'Juan Dela Cruz', role: 'Farmer', roleColor: 'bg-green-500', type: 'Login', typeClass: 'bg-blue-100 text-blue-700', details: 'Successful login from mobile device', ip: '192.168.1.45', status: 'success' },
                    { id: 2, timestamp: '2026-01-29 10:42:15', user: 'Admin User', role: 'DA Admin', roleColor: 'bg-green-700', type: 'Upload', typeClass: 'bg-purple-100 text-purple-700', details: 'Uploaded dataset: crop_data_jan2026.csv (2,450 records)', ip: '192.168.1.100', status: 'success' },
                    { id: 3, timestamp: '2026-01-29 10:38:00', user: 'Maria Santos', role: 'Farmer', roleColor: 'bg-green-500', type: 'Update', typeClass: 'bg-yellow-100 text-yellow-700', details: 'Updated farm profile - Location: Atok, Benguet', ip: '192.168.1.67', status: 'success' },
                    { id: 4, timestamp: '2026-01-29 10:35:12', user: 'Unknown', role: 'N/A', roleColor: 'bg-gray-400', type: 'Login', typeClass: 'bg-blue-100 text-blue-700', details: 'Failed login attempt for user: admin@test.com', ip: '45.33.32.156', status: 'failed' },
                    { id: 5, timestamp: '2026-01-29 10:30:45', user: 'Superadmin', role: 'Superadmin', roleColor: 'bg-purple-600', type: 'Create', typeClass: 'bg-green-100 text-green-700', details: 'Created new user: pedro.garcia@email.com with role: Farmer', ip: '192.168.1.1', status: 'success' },
                    { id: 6, timestamp: '2026-01-29 10:25:00', user: 'Superadmin', role: 'Superadmin', roleColor: 'bg-purple-600', type: 'Login', typeClass: 'bg-blue-100 text-blue-700', details: 'Successful login with 2FA verification', ip: '192.168.1.1', status: 'success' },
                    { id: 7, timestamp: '2026-01-29 10:20:33', user: 'Pedro Garcia', role: 'Farmer', roleColor: 'bg-green-500', type: 'Logout', typeClass: 'bg-gray-100 text-gray-700', details: 'User logged out', ip: '192.168.1.89', status: 'success' },
                    { id: 8, timestamp: '2026-01-29 10:15:18', user: 'Admin User', role: 'DA Admin', roleColor: 'bg-green-700', type: 'Delete', typeClass: 'bg-red-100 text-red-700', details: 'Deleted old dataset: legacy_data_2020.csv', ip: '192.168.1.100', status: 'success' },
                    { id: 9, timestamp: '2026-01-29 09:55:00', user: 'Unknown', role: 'N/A', roleColor: 'bg-gray-400', type: 'Login', typeClass: 'bg-blue-100 text-blue-700', details: 'Failed login attempt for user: superadmin@smartharvest.ph (wrong password)', ip: '103.45.67.89', status: 'failed' },
                    { id: 10, timestamp: '2026-01-29 09:45:22', user: 'Ana Reyes', role: 'Farmer', roleColor: 'bg-green-500', type: 'Create', typeClass: 'bg-green-100 text-green-700', details: 'Submitted new yield report for Rice - 2.5 tons', ip: '192.168.1.112', status: 'success' }
                ],
                filteredLogs: [],

                init() {
                    this.filteredLogs = [...this.logs];
                },

                filterLogs() {
                    this.filteredLogs = this.logs.filter(log => {
                        // Type filter
                        if (this.filters.type !== 'all' && log.type.toLowerCase() !== this.filters.type) {
                            return false;
                        }
                        // Role filter
                        if (this.filters.role !== 'all') {
                            const roleMap = { superadmin: 'Superadmin', da_admin: 'DA Admin', farmer: 'Farmer' };
                            if (log.role !== roleMap[this.filters.role]) return false;
                        }
                        // Search filter
                        if (this.filters.search) {
                            const search = this.filters.search.toLowerCase();
                            return log.user.toLowerCase().includes(search) ||
                                   log.details.toLowerCase().includes(search) ||
                                   log.ip.includes(search);
                        }
                        return true;
                    });
                },

                exportLogs() {
                    alert('Exporting logs to CSV...');
                    // Implement export functionality
                }
            };
        }
    </script>
</body>
</html>
