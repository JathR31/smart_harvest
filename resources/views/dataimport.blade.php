<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Import - SmartHarvest DA Officer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar { background: linear-gradient(180deg, #15803d 0%, #166534 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden" x-data="dataImportApp()">
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
                    <a href="{{ route('admin.dashboard') }}#market-prices" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Market Prices</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}#announcements" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Announcements</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}#inbox" class="flex items-center space-x-3 px-4 py-3 rounded hover:bg-green-800 transition-colors">
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
                    <a href="{{ route('admin.dataimport') }}" class="flex items-center space-x-3 px-4 py-3 rounded bg-green-600">
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
    <div class="flex-1 flex flex-col">
        <!-- Top Header -->
        <header class="bg-white border-b px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Data Import</h1>
                    <p class="text-sm text-gray-600">Upload and import datasets into the system</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <button class="relative">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">3</span>
                    </button>
                    <button onclick="window.location.href='{{ route('admin.logout') }}'" class="flex items-center space-x-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Success/Error Messages -->
            <div x-show="successMessage" x-transition class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center space-x-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-green-800" x-text="successMessage"></span>
            </div>

            <div x-show="errorMessage" x-transition class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center space-x-3">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-red-800" x-text="errorMessage"></span>
            </div>

            <!-- Upload Section -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Upload Dataset</h2>
                
                <form @submit.prevent="uploadFile" enctype="multipart/form-data">
                    <!-- Dataset Type Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dataset Type *</label>
                        <select x-model="selectedDatasetType" @change="updateDatasetInfo" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Select a dataset type...</option>
                            <template x-for="dataset in availableDatasets" :key="dataset.id">
                                <option :value="dataset.id" x-text="dataset.name"></option>
                            </template>
                        </select>
                        <template x-if="selectedDataset">
                            <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm text-blue-800" x-text="selectedDataset.description"></p>
                                <div class="mt-2">
                                    <p class="text-xs font-semibold text-blue-900 mb-1">Required Fields:</p>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="field in selectedDataset.required_fields" :key="field">
                                            <span class="text-xs px-2 py-1 bg-white rounded border border-blue-300 text-blue-700" x-text="field"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Download Template Button -->
                    <div class="mb-6" x-show="selectedDatasetType">
                        <button type="button" @click="downloadTemplate" 
                                class="flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Download CSV Template</span>
                        </button>
                    </div>

                    <!-- File Upload Area -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select File *</label>
                        <div @drop.prevent="handleDrop" @dragover.prevent @dragenter.prevent 
                             class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition cursor-pointer"
                             :class="{'border-blue-500 bg-blue-50': isDragging}"
                             @click="$refs.fileInput.click()">
                            <input type="file" x-ref="fileInput" @change="handleFileSelect" accept=".csv,.xlsx,.xls" class="hidden">
                            
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            
                            <p class="text-gray-600 mb-2" x-show="!selectedFile">
                                <span class="font-semibold text-blue-600">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-sm text-gray-500" x-show="!selectedFile">CSV or Excel files only</p>
                            
                            <div x-show="selectedFile" class="flex items-center justify-center space-x-2">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="text-left">
                                    <p class="font-medium text-gray-800" x-text="selectedFile?.name"></p>
                                    <p class="text-sm text-gray-500" x-text="formatFileSize(selectedFile?.size)"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <button type="button" @click="resetForm"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Reset
                        </button>
                        <button type="submit" :disabled="uploading || !selectedFile"
                                class="px-8 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center space-x-2">
                            <svg x-show="uploading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="uploading ? 'Processing import...' : 'Upload Dataset'"></span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Uploads -->
            <div class="bg-white rounded-lg shadow-sm p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Uploads</h2>
                    <button @click="loadRecentUploads" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Refresh
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Dataset Name</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Records</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Uploaded By</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Date</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="upload in recentUploads" :key="upload.id">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <div>
                                                <p class="font-medium text-gray-800" x-text="upload.name"></p>
                                                <p class="text-sm text-gray-500" x-text="upload.description"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-gray-700" x-text="upload.records.toLocaleString()"></td>
                                    <td class="py-4 px-4 text-gray-700" x-text="upload.uploaded_by"></td>
                                    <td class="py-4 px-4 text-gray-700" x-text="upload.date"></td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            Success
                                        </span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="recentUploads.length === 0">
                                <td colspan="6" class="py-8 text-center text-gray-500">
                                    No recent uploads found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function dataImportApp() {
            return {
                selectedFile: null,
                uploading: false,
                isDragging: false,
                successMessage: '',
                errorMessage: '',
                recentUploads: [],
                availableDatasets: [],
                selectedDatasetType: '',
                selectedDataset: null,

                init() {
                    this.loadRecentUploads();
                    this.loadAvailableDatasets();
                },

                async loadAvailableDatasets() {
                    try {
                        const response = await fetch("{{ route('admin.api.import.datasets') }}");
                        const data = await response.json();
                        if (data.success) {
                            this.availableDatasets = data.datasets || [];
                        }
                    } catch (error) {
                        console.error('Failed to load datasets:', error);
                    }
                },

                updateDatasetInfo() {
                    this.selectedDataset = this.availableDatasets.find(d => d.id === this.selectedDatasetType);
                },

                async downloadTemplate() {
                    if (!this.selectedDatasetType) {
                        this.showError('Please select a dataset type first');
                        return;
                    }

                    try {
                        const url = `{{ url('/admin/api/import/template') }}/${this.selectedDatasetType}`;
                        window.location.href = url;
                        this.showSuccess('Template download started');
                    } catch (error) {
                        console.error('Template download error:', error);
                        this.showError('Failed to download template');
                    }
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.selectedFile = file;
                    }
                },

                handleDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file && (file.name.endsWith('.csv') || file.name.endsWith('.xlsx') || file.name.endsWith('.xls'))) {
                        this.selectedFile = file;
                    } else {
                        this.showError('Please upload a CSV or Excel file');
                    }
                },

                formatFileSize(bytes) {
                    if (!bytes) return '';
                    if (bytes < 1024) return bytes + ' B';
                    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
                    return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
                },

                async uploadFile() {
                    if (!this.selectedFile || !this.selectedDatasetType) {
                        this.showError('Please select a dataset type and file');
                        return;
                    }

                    this.uploading = true;
                    this.clearMessages();

                    const formData = new FormData();
                    formData.append('file', this.selectedFile);
                    formData.append('dataset_id', this.selectedDatasetType);

                    try {
                        const uploadUrl = "{{ route('admin.api.import') }}";
                        
                        const response = await fetch(uploadUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            credentials: 'same-origin'
                        });

                        const text = await response.text();
                        
                        let data;
                        try {
                            data = JSON.parse(text);
                        } catch (parseError) {
                            console.error('Non-JSON response from server:', text.substring(0, 500));
                            throw new Error('Server returned HTML instead of JSON. Check console for full response.');
                        }

                        if (response.ok && data.success) {
                            this.showSuccess(data.message || 'Dataset uploaded successfully!');
                            this.resetForm();
                            this.loadRecentUploads();
                        } else {
                            if (data.errors) {
                                const errorMessages = Object.values(data.errors).flat().join(', ');
                                this.showError(errorMessages);
                            } else {
                                this.showError(data.message || data.error || 'Upload failed');
                            }
                        }
                    } catch (error) {
                        console.error('Upload error:', error);
                        this.showError('An error occurred during upload: ' + error.message);
                    } finally {
                        this.uploading = false;
                    }
                },

                async loadRecentUploads() {
                    try {
                        const uploadsUrl = "{{ route('admin.api.recent-uploads') }}";
                        const response = await fetch(uploadsUrl);
                        const data = await response.json();
                        this.recentUploads = data.uploads || [];
                    } catch (error) {
                        console.error('Failed to load recent uploads:', error);
                    }
                },

                resetForm() {
                    this.selectedFile = null;
                    this.selectedDatasetType = '';
                    this.selectedDataset = null;
                    this.$refs.fileInput.value = '';
                    this.clearMessages();
                },

                showSuccess(message) {
                    this.successMessage = message;
                    this.errorMessage = '';
                    setTimeout(() => this.successMessage = '', 5000);
                },

                showError(message) {
                    this.errorMessage = message;
                    this.successMessage = '';
                },

                clearMessages() {
                    this.successMessage = '';
                    this.errorMessage = '';
                }
            }
        }
    </script>
</body>
</html>
