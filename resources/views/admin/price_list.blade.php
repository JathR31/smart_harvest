<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price List - SmartHarvest</title>
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
<body class="bg-gray-50" x-data="priceListPage()" x-init="init()">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Green theme for DA-CAR Officers -->
        <aside class="w-64 bg-gradient-to-b from-green-700 to-green-900 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-6 border-b border-green-600">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <span class="text-xl">🌾</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SmartHarvest</h1>
                        <p class="text-xs text-green-200">DA-CAR Officer</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-1">
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Overview</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">User Management</p>
                    <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Users</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Data Management</p>
                    <a href="{{ route('admin.datasets') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
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
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Market</p>
                    <a href="{{ route('admin.price-list') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Price List</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Communication</p>
                    <a href="{{ route('admin.announcements') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Announcements</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">System</p>
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
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-lg font-semibold text-gray-800">Price List Management</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">Market Prices</span>
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
                <!-- Header with Add Button -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Crop Price List</h1>
                        <p class="text-gray-500">Manage market prices and trading post locations</p>
                    </div>
                    <button @click="showAddModal = true" class="flex items-center space-x-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Add Price</span>
                    </button>
                </div>

                <!-- Trading Posts Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-green-700">Trading Post Locations</h3>
                        <button @click="showAddLocationModal = true" class="text-green-600 hover:text-green-700 text-sm font-medium">+ Add Location</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="location in tradingPosts" :key="location.id">
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-800" x-text="location.name"></h4>
                                        <p class="text-sm text-gray-500" x-text="location.address"></p>
                                        <div class="flex items-center mt-2">
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full" x-text="location.municipality"></span>
                                        </div>
                                    </div>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Price Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-green-700">Current Market Prices</h3>
                            <div class="flex items-center space-x-2 text-sm text-gray-500">
                                <span>Last updated:</span>
                                <span class="font-medium" x-text="lastUpdated"></span>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crop</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price (₱)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trading Post</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <template x-for="price in prices" :key="price.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3" x-text="price.icon"></span>
                                                <span class="font-medium text-gray-800" x-text="price.crop"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-lg font-bold text-green-600">₱<span x-text="price.price"></span></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500" x-text="'per ' + price.unit"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500" x-text="price.tradingPost"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="price.change >= 0 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100'" class="px-2 py-1 rounded-full text-xs font-medium" x-text="(price.change >= 0 ? '+' : '') + price.change + '%'"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button @click="editPrice(price)" class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                                            <button @click="deletePrice(price)" class="text-red-600 hover:text-red-800">Delete</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add Price Modal -->
                <div x-show="showAddModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4" @click.away="showAddModal = false">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Add New Price</h3>
                        <form @submit.prevent="addPrice()">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Crop Name</label>
                                    <select x-model="newPrice.crop" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        <option value="">Select Crop</option>
                                        <option value="Broccoli">🥦 Broccoli</option>
                                        <option value="Cabbage">🥬 Cabbage</option>
                                        <option value="Carrots">🥕 Carrots</option>
                                        <option value="Cauliflower">🥦 Cauliflower</option>
                                        <option value="Chinese Cabbage">🥬 Chinese Cabbage</option>
                                        <option value="Garden Peas">🫛 Garden Peas</option>
                                        <option value="Lettuce">🥗 Lettuce</option>
                                        <option value="Snap Beans">🫘 Snap Beans</option>
                                        <option value="Sweet Pepper">🫑 Sweet Pepper</option>
                                        <option value="White Potato">🥔 White Potato</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                                        <input type="number" x-model="newPrice.price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                        <select x-model="newPrice.unit" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <option value="kg">Kilogram (kg)</option>
                                            <option value="bundle">Bundle</option>
                                            <option value="piece">Piece</option>
                                            <option value="cavan">Cavan</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Trading Post</label>
                                    <select x-model="newPrice.tradingPost" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <template x-for="post in tradingPosts" :key="post.id">
                                            <option :value="post.name" x-text="post.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" @click="showAddModal = false" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add Price</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function priceListPage() {
            return {
                showAddModal: false,
                showAddLocationModal: false,
                lastUpdated: 'January 29, 2026 at 10:30 AM',
                newPrice: { crop: '', price: '', unit: 'kg', tradingPost: 'La Trinidad Trading Post' },
                tradingPosts: [
                    { id: 1, name: 'La Trinidad Trading Post', address: 'Km. 5, La Trinidad', municipality: 'La Trinidad' },
                    { id: 2, name: 'Baguio City Market', address: 'Magsaysay Ave, Baguio City', municipality: 'Baguio' },
                    { id: 3, name: 'Sayangan Trading Center', address: 'Atok, Benguet', municipality: 'Atok' }
                ],
                // ML Dataset Crops with icons
                mlCrops: {
                    'Broccoli': '🥦',
                    'Cabbage': '🥬',
                    'Carrots': '🥕',
                    'Cauliflower': '🥦',
                    'Chinese Cabbage': '🥬',
                    'Garden Peas': '🫛',
                    'Lettuce': '🥗',
                    'Snap Beans': '🫘',
                    'Sweet Pepper': '🫑',
                    'White Potato': '🥔'
                },
                prices: [
                    { id: 1, icon: '🥬', crop: 'Cabbage', price: 25, unit: 'kg', tradingPost: 'La Trinidad Trading Post', change: 8 },
                    { id: 2, icon: '🥔', crop: 'White Potato', price: 28, unit: 'kg', tradingPost: 'La Trinidad Trading Post', change: 12 },
                    { id: 3, icon: '🥕', crop: 'Carrots', price: 30, unit: 'kg', tradingPost: 'Baguio City Market', change: -2 },
                    { id: 4, icon: '🥗', crop: 'Lettuce', price: 35, unit: 'kg', tradingPost: 'La Trinidad Trading Post', change: 0 },
                    { id: 5, icon: '🥦', crop: 'Broccoli', price: 45, unit: 'kg', tradingPost: 'La Trinidad Trading Post', change: 5 },
                    { id: 6, icon: '🥦', crop: 'Cauliflower', price: 40, unit: 'kg', tradingPost: 'Baguio City Market', change: 0 },
                    { id: 7, icon: '🫘', crop: 'Snap Beans', price: 38, unit: 'kg', tradingPost: 'Sayangan Trading Center', change: -3 },
                    { id: 8, icon: '🫑', crop: 'Sweet Pepper', price: 55, unit: 'kg', tradingPost: 'La Trinidad Trading Post', change: 10 },
                    { id: 9, icon: '🥬', crop: 'Chinese Cabbage', price: 30, unit: 'kg', tradingPost: 'La Trinidad Trading Post', change: 4 },
                    { id: 10, icon: '🫛', crop: 'Garden Peas', price: 65, unit: 'kg', tradingPost: 'Baguio City Market', change: 2 }
                ],

                init() {
                    // Load prices from localStorage if available
                    this.loadPrices();
                },

                addPrice() {
                    const newId = Math.max(...this.prices.map(p => p.id)) + 1;
                    const icon = this.mlCrops[this.newPrice.crop] || '🌿';
                    this.prices.push({
                        id: newId,
                        icon: icon,
                        crop: this.newPrice.crop,
                        price: parseFloat(this.newPrice.price),
                        unit: this.newPrice.unit,
                        tradingPost: this.newPrice.tradingPost,
                        change: 0
                    });
                    
                    // Save to localStorage for persistence
                    this.savePrices();
                    
                    this.newPrice = { crop: '', price: '', unit: 'kg', tradingPost: 'La Trinidad Trading Post' };
                    this.showAddModal = false;
                    this.lastUpdated = new Date().toLocaleString('en-US', { dateStyle: 'long', timeStyle: 'short' });
                },
                
                savePrices() {
                    localStorage.setItem('smartharvest_crop_prices', JSON.stringify(this.prices));
                    localStorage.setItem('smartharvest_prices_updated', this.lastUpdated);
                },
                
                loadPrices() {
                    const saved = localStorage.getItem('smartharvest_crop_prices');
                    const savedDate = localStorage.getItem('smartharvest_prices_updated');
                    if (saved) {
                        this.prices = JSON.parse(saved);
                    }
                    if (savedDate) {
                        this.lastUpdated = savedDate;
                    }
                },

                editPrice(price) {
                    // Edit price logic - prompt for new price
                    const newPrice = prompt('Enter new price for ' + price.crop + ':', price.price);
                    if (newPrice !== null && !isNaN(newPrice)) {
                        const priceIndex = this.prices.findIndex(p => p.id === price.id);
                        if (priceIndex !== -1) {
                            const oldPrice = this.prices[priceIndex].price;
                            this.prices[priceIndex].price = parseFloat(newPrice);
                            // Calculate change percentage
                            this.prices[priceIndex].change = oldPrice > 0 ? Math.round(((parseFloat(newPrice) - oldPrice) / oldPrice) * 100) : 0;
                            this.lastUpdated = new Date().toLocaleString('en-US', { dateStyle: 'long', timeStyle: 'short' });
                            this.savePrices();
                        }
                    }
                },

                deletePrice(price) {
                    if (confirm('Are you sure you want to delete ' + price.crop + '?')) {
                        this.prices = this.prices.filter(p => p.id !== price.id);
                        this.savePrices();
                    }
                }
            };
        }
    </script>
</body>
</html>
