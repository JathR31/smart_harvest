<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Import - SmartHarvest Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar { background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
    </style>
</head>
<body class="bg-gray-50 flex" x-data="dataImportApp()">
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
                <a href="{{ route('admin.roles') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"></path></svg>
                    <span>Roles & Permissions</span>
                </a>
            </div>

            <!-- DATA MANAGEMENT Section -->
            <div class="mb-6">
                <p class="text-xs uppercase text-blue-300 mb-2">DATA MANAGEMENT</p>
                <a href="{{ route('admin.datasets') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    <span>Datasets</span>
                </a>
                <a href="{{ route('admin.dataimport') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <span>Data Import</span>
                </a>
            </div>

            <div class="mb-4">
                <p class="text-xs uppercase text-gray-400 mb-2 px-4">System</p>
                <a href="{{ route('admin.monitoring') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Monitoring</span>
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
                    <h1 class="text-2xl font-semibold text-gray-800">Data Import</h1>
                    <p class="text-sm text-gray-600">Upload and import datasets into the system</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <button class="relative">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">3</span>
                    </button>
                    <button onclick="window.location.href='{{ route('admin.logout') }}'" class="flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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
                    <!-- File Upload Area -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
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

                    <!-- Dataset Information -->
                    <div class="mb-6">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dataset Name *</label>
                            <input type="text" x-model="datasetName" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., Crop Production Statistics 2025">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea x-model="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Provide a brief description of the dataset..."></textarea>
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
                                class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center space-x-2">
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
                datasetName: '',
                description: '',
                uploading: false,
                isDragging: false,
                successMessage: '',
                errorMessage: '',
                recentUploads: [],

                init() {
                    this.loadRecentUploads();
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.selectedFile = file;
                        if (!this.datasetName) {
                            this.datasetName = file.name.replace(/\.[^/.]+$/, '');
                        }
                    }
                },

                handleDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file && (file.name.endsWith('.csv') || file.name.endsWith('.xlsx') || file.name.endsWith('.xls'))) {
                        this.selectedFile = file;
                        if (!this.datasetName) {
                            this.datasetName = file.name.replace(/\.[^/.]+$/, '');
                        }
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
                    if (!this.selectedFile || !this.datasetName) {
                        this.showError('Please fill in all required fields (file and dataset name)');
                        return;
                    }

                    this.uploading = true;
                    this.clearMessages();

                    // Get CSRF token from meta tag
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        this.showError('CSRF token not found. Please refresh the page.');
                        this.uploading = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('file', this.selectedFile);
                    formData.append('dataset_name', this.datasetName);
                    formData.append('description', this.description);

                    try {
                        // Use Laravel route helper for correct URL
                        const uploadUrl = "{{ route('admin.api.import') }}";
                        console.log('Uploading to:', uploadUrl);
                        
                        const response = await fetch(uploadUrl, {
                            method: 'POST',
                            body: formData,
                            // DO NOT set Content-Type header - let browser set it with boundary for multipart/form-data
                            headers: {
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });

                        // Read response stream ONCE as text
                        const text = await response.text();
                        
                        // Try to parse as JSON
                        let data;
                        try {
                            data = JSON.parse(text);
                        } catch (parseError) {
                            // Server returned non-JSON (likely HTML error page)
                            console.error('Non-JSON response from server:', text.substring(0, 500));
                            throw new Error('Server returned HTML instead of JSON. Check console for full response.');
                        }

                        // Check if request was successful
                        if (response.ok && data.success) {
                            this.showSuccess(data.message || 'Dataset uploaded successfully! Import is processing in the background.');
                            this.resetForm();
                            this.loadRecentUploads();
                        } else {
                            // Handle validation errors or general errors
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
                    this.datasetName = '';
                    this.description = '';
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
