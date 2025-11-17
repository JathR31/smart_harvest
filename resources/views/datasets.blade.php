<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Datasets - SmartHarvest Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar { background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
    </style>
</head>
<body class="bg-gray-50 flex" x-data="datasetsApp()">
    <!-- Sidebar -->
    <aside class="sidebar w-64 min-h-screen text-white flex-shrink-0">
        <div class="p-6">
            <div class="flex items-center space-x-2 mb-8">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/></svg>
                <div>
                    <h1 class="text-xl font-bold">SmartHarvest</h1>
                    <p class="text-xs text-blue-200">Admin</p>
                </div>
            </div>

            <!-- OVERVIEW Section -->
            <div class="mb-6">
                <p class="text-xs uppercase text-blue-300 mb-2">OVERVIEW</p>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Admin Dashboard</span>
                </a>
            </div>

            <!-- USER MANAGEMENT Section -->
            <div class="mb-6">
                <p class="text-xs uppercase text-blue-300 mb-2">USER MANAGEMENT</p>
                <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Users</span>
                </a>
                <a href="#" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    <span>Roles & Permissions</span>
                </a>
            </div>

            <!-- DATA MANAGEMENT Section -->
            <div class="mb-6">
                <p class="text-xs uppercase text-blue-300 mb-2">DATA MANAGEMENT</p>
                <a href="{{ route('admin.datasets') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    <span>Datasets</span>
                </a>
                <a href="{{ route('admin.dataimport') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Data Import</span>
                </a>
            </div>

            <!-- SYSTEM Section -->
            <div class="mb-6">
                <p class="text-xs uppercase text-blue-300 mb-2">SYSTEM</p>
                <a href="#" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Monitoring</span>
                </a>
                <a href="#" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Logs</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Top Header -->
        <header class="bg-white border-b px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/></svg>
                        <h1 class="text-2xl font-semibold text-green-700">SmartHarvest Admin</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." x-model="searchQuery" @input="filterDatasets"
                               class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span class="text-sm font-medium">English</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>
                    <button class="relative">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full">3</span>
                    </button>
                    <button onclick="window.location.href='{{ route('homepage') }}'" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 p-8 overflow-y-auto bg-gray-50">
            <!-- Page Title -->
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Datasets</h2>
                <p class="text-gray-600 mt-1">Master data library for the Cordillera region</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-gray-500 text-sm mb-2">Total Datasets</p>
                    <p class="text-4xl font-bold text-gray-800" x-text="stats.total"></p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-gray-500 text-sm mb-2">Total Records</p>
                    <p class="text-4xl font-bold text-blue-600" x-text="stats.totalRecords.toLocaleString()"></p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-gray-500 text-sm mb-2">Total Size</p>
                    <p class="text-4xl font-bold text-green-600" x-text="formatFileSize(stats.totalSize)"></p>
                </div>
            </div>

            <!-- Filter and Refresh -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <input type="text" placeholder="Search datasets..." x-model="searchQuery" @input="filterDatasets"
                           class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-80">
                </div>
                <button @click="loadDatasets" class="flex items-center space-x-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Refresh</span>
                </button>
            </div>

            <!-- Datasets Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <template x-for="dataset in filteredDatasets" :key="dataset.id">
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition">
                        <div class="p-6">
                            <!-- Icon -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-100">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Dataset Name -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-2" x-text="dataset.name"></h3>
                            
                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2" x-text="dataset.description"></p>

                            <!-- Metadata -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>Updated <span x-text="dataset.updated"></span> by <span x-text="dataset.updated_by"></span></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                                    <span x-text="dataset.records.toLocaleString() + ' records'"></span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2">
                                <button class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <span class="text-sm">View</span>
                                </button>
                                <button class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    <span class="text-sm">Export</span>
                                </button>
                                <button @click="deleteDataset(dataset.id)" class="px-3 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition" title="Delete dataset">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="filteredDatasets.length === 0" class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <p class="text-gray-600">No datasets found</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        function datasetsApp() {
            return {
                datasets: [],
                filteredDatasets: [],
                searchQuery: '',
                stats: {
                    total: 0,
                    totalRecords: 0,
                    totalSize: 0
                },

                init() {
                    this.loadDatasets();
                },

                async loadDatasets() {
                    try {
                        const response = await fetch('{{ route('admin.api.datasets') }}');
                        const data = await response.json();
                        this.datasets = data.datasets || [];
                        this.filteredDatasets = this.datasets;
                        this.stats = data.stats || this.stats;
                        console.log('Loaded datasets:', this.datasets);
                    } catch (error) {
                        console.error('Failed to load datasets:', error);
                    }
                },

                async deleteDataset(id) {
                    if (!confirm('Are you sure you want to delete this dataset? This action cannot be undone.')) {
                        return;
                    }

                    try {
                        const deleteUrl = '{{ url('admin/api/datasets') }}/' + id;
                        const response = await fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            }
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            alert('Dataset deleted successfully');
                            this.loadDatasets(); // Reload the list
                        } else {
                            alert('Failed to delete dataset: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Failed to delete dataset:', error);
                        alert('An error occurred while deleting the dataset');
                    }
                },

                filterDatasets() {
                    let filtered = this.datasets;

                    // Filter by search query
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(dataset => 
                            dataset.name.toLowerCase().includes(query) ||
                            dataset.description.toLowerCase().includes(query)
                        );
                    }

                    this.filteredDatasets = filtered;
                },

                formatFileSize(bytes) {
                    if (!bytes) return '0 B';
                    if (bytes < 1024) return bytes + ' B';
                    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
                    return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
                }
            }
        }
    </script>
</body>
</html>
