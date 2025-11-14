<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yield Analysis - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar { background: linear-gradient(180deg, #047857 0%, #065f46 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
    </style>
</head>
<body class="bg-gray-50 flex" x-data="yieldAnalysis()">
    <!-- Sidebar -->
    <aside class="sidebar w-64 min-h-screen text-white flex-shrink-0">
        <div class="p-6">
            <div class="flex items-center space-x-2 mb-8">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/></svg>
                <span class="text-xl font-semibold">SmartHarvest</span>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Main</p>
                <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Analysis</p>
                <a href="{{ route('planting.schedule') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Planting Schedule</span>
                </a>
                <a href="{{ route('yield.analysis') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Yield Analysis</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Weather</p>
                <a href="#" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    <span>Forecast</span>
                </a>
            </div>

            <div>
                <p class="text-xs uppercase text-green-300 mb-3">Account</p>
                <a href="{{ route('settings') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Logout</span>
                    </button>
                </form>
                <a href="{{ route('homepage') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Top Header -->
        <header class="bg-white border-b px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold text-green-700">Yield Analysis</h1>
                    <!-- Municipality Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-medium" x-text="selectedMunicipality"></span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute top-full left-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-10 max-h-64 overflow-y-auto" style="display: none;">
                            <template x-for="municipality in municipalities" :key="municipality">
                                <button @click="selectMunicipality(municipality); open = false" class="block w-full text-left px-4 py-2 hover:bg-gray-50" :class="{'bg-green-50 text-green-700': selectedMunicipality === municipality}">
                                    <span x-text="municipality"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    <!-- Year Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 transition">
                            <span class="font-medium" x-text="selectedYear"></span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute top-full left-0 mt-2 w-32 bg-white border rounded-lg shadow-lg z-10" style="display: none;">
                            <template x-for="year in years" :key="year">
                                <button @click="selectYear(year); open = false" class="block w-full text-left px-4 py-2 hover:bg-gray-50" :class="{'bg-green-50 text-green-700': selectedYear === year}">
                                    <span x-text="year"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Top Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Average Yield -->
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500 mb-2">Average Yield <span x-text="selectedYear"></span></p>
                    <p class="text-3xl font-bold text-gray-800 mb-2"><span x-text="stats.avg_yield"></span> mt/ha</p>
                    <p class="text-sm text-gray-600"><span x-text="selectedMunicipality"></span></p>
                </div>

                <!-- Best Performing Crop -->
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500 mb-2">Best Performing Crop</p>
                    <p class="text-3xl font-bold text-gray-800 mb-2"><span x-text="stats.best_crop?.crop_type || 'N/A'"></span></p>
                    <p class="text-sm text-gray-600"><span x-text="stats.best_crop ? stats.best_crop.avg_yield.toFixed(1) + ' mt/ha average' : ''"></span></p>
                </div>

                <!-- Total Production -->
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500 mb-2">Total Production</p>
                    <p class="text-3xl font-bold text-gray-800 mb-2"><span x-text="stats.total_production"></span> MT</p>
                    <p class="text-sm text-gray-600"><span x-text="stats.total_area"></span> ha planted</p>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Yield Comparison Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-green-700 mb-2">Yield Comparison (2020-2025)</h2>
                    <p class="text-sm text-gray-600 mb-6">Actual vs Predicted</p>
                    <div class="h-64">
                        <canvas id="yieldComparisonChart"></canvas>
                    </div>
                </div>

                <!-- Crop Performance Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-green-700 mb-2">Crop Performance by Variety</h2>
                    <p class="text-sm text-gray-600 mb-6">Average yield per hectare in 2025</p>
                    <div class="h-64">
                        <canvas id="cropPerformanceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Monthly Yield Trend -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-green-700 mb-2">Monthly Yield Trend</h2>
                    <p class="text-sm text-gray-600 mb-6">Seasonal variation in 2025</p>
                    <div class="h-64">
                        <canvas id="monthlyYieldChart"></canvas>
                    </div>
                </div>

                <!-- Yield Impact Factors -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-green-700 mb-2">Yield Impact Factors</h2>
                    <p class="text-sm text-gray-600 mb-6">Key factors affecting yield performance</p>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-700 font-medium">Rainfall</span>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">High</span>
                            </div>
                            <div class="flex items-center text-green-600 font-semibold">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                <span>+15%</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-700 font-medium">Temperature</span>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">Medium</span>
                            </div>
                            <div class="flex items-center text-gray-600 font-semibold">
                                <span>+2%</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-700 font-medium">Soil Quality</span>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">High</span>
                            </div>
                            <div class="flex items-center text-green-600 font-semibold">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                <span>+12%</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-700 font-medium">Fertilizer Use</span>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">Medium</span>
                            </div>
                            <div class="flex items-center text-green-600 font-semibold">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                <span>+8%</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-700 font-medium">Pest Control</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">Low</span>
                            </div>
                            <div class="flex items-center text-red-600 font-semibold">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                <span>-3%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Insights -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-green-700 mb-6">Key Insights</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Insight 1 -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            <div>
                                <h3 class="font-semibold text-green-800 mb-1">Improved Yields</h3>
                                <p class="text-sm text-green-700">Vegetable crops show 15% improvement with optimal cool season planting in Benguet.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Insight 2 -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                            <div>
                                <h3 class="font-semibold text-blue-800 mb-1">Weather Impact</h3>
                                <p class="text-sm text-blue-700">Consistent rainfall patterns in 2025 contributed to higher yields across all varieties.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Insight 3 -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-orange-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            <div>
                                <h3 class="font-semibold text-orange-800 mb-1">Regional Performance</h3>
                                <p class="text-sm text-orange-700">Your farm outperforms regional average by 9.8% consistently.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Yield Comparison Chart (Line Chart)
        const yieldComparisonCtx = document.getElementById('yieldComparisonChart').getContext('2d');
        new Chart(yieldComparisonCtx, {
            type: 'line',
            data: {
                labels: ['2020', '2021', '2022', '2023', '2024', '2025'],
                datasets: [{
                    label: 'Actual',
                    data: [4.5, 5.2, 5.4, 5.0, 5.8, 6.0],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981',
                    tension: 0.4
                }, {
                    label: 'Predicted',
                    data: [4.6, 5.1, 5.5, 4.9, 5.9, 5.8],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 8,
                        ticks: {
                            callback: function(value) {
                                return value;
                            }
                        },
                        title: {
                            display: true,
                            text: 'Yield (mt/ha)'
                        }
                    }
                }
            }
        });

        // Crop Performance Chart (Horizontal Bar Chart)
        const cropPerformanceCtx = document.getElementById('cropPerformanceChart').getContext('2d');
        new Chart(cropPerformanceCtx, {
            type: 'bar',
            data: {
                labels: ['Tinawon Rice', 'Highland Cabbage', 'Arabica Coffee', 'Sweet Potato', 'Highland Potato'],
                datasets: [{
                    data: [5.78, 22.13, 1.93, 13.4, 19.61],
                    backgroundColor: '#10b981',
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 24
                    }
                }
            }
        });

        // Monthly Yield Trend Chart (Area Chart)
        const monthlyYieldCtx = document.getElementById('monthlyYieldChart').getContext('2d');
        new Chart(monthlyYieldCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    data: [5.2, 5.4, 5.5, 5.6, 5.7, 5.9, 5.8, 5.7, 5.6, 5.4, 5.3, 5.2],
                    backgroundColor: 'rgba(16, 185, 129, 0.3)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 8,
                        title: {
                            display: true,
                            text: 'Yield (mt/ha)'
                        }
                    }
                }
            }
        });
    </script>

    <script>
        function yieldAnalysis() {
            return {
                selectedMunicipality: 'La Trinidad',
                selectedYear: 2025,
                municipalities: [
                    'Atok', 'Baguio City', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
                    'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 
                    'Sablan', 'Tuba', 'Tublay'
                ],
                years: [2025, 2024, 2023, 2022, 2021, 2020],
                stats: {
                    avg_yield: '0.0',
                    best_crop: null,
                    total_production: '0',
                    total_area: '0'
                },
                comparisonData: [],
                cropPerformance: [],
                monthlyData: [],
                loading: false,
                yieldChart: null,
                cropChart: null,
                monthlyChart: null,

                init() {
                    this.loadYieldData();
                },

                selectMunicipality(municipality) {
                    this.selectedMunicipality = municipality;
                    this.loadYieldData();
                },

                selectYear(year) {
                    this.selectedYear = year;
                    this.loadYieldData();
                },

                async loadYieldData() {
                    this.loading = true;
                    
                    try {
                        // Load yield stats
                        const statsResponse = await fetch(`/api/yield/stats?municipality=${encodeURIComponent(this.selectedMunicipality)}&year=${this.selectedYear}`);
                        if (statsResponse.ok) {
                            this.stats = await statsResponse.json();
                        }

                        // Load comparison data (multi-year)
                        const comparisonResponse = await fetch(`/api/yield/comparison?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        if (comparisonResponse.ok) {
                            this.comparisonData = await comparisonResponse.json();
                            this.updateYieldChart();
                        }

                        // Load crop performance
                        const cropResponse = await fetch(`/api/yield/crops?municipality=${encodeURIComponent(this.selectedMunicipality)}&year=${this.selectedYear}`);
                        if (cropResponse.ok) {
                            this.cropPerformance = await cropResponse.json();
                            this.updateCropChart();
                        }

                        // Load monthly data
                        const monthlyResponse = await fetch(`/api/yield/monthly?municipality=${encodeURIComponent(this.selectedMunicipality)}&year=${this.selectedYear}`);
                        if (monthlyResponse.ok) {
                            this.monthlyData = await monthlyResponse.json();
                            this.updateMonthlyChart();
                        }
                    } catch (error) {
                        console.error('Error loading yield data:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                updateYieldChart() {
                    const ctx = document.getElementById('yieldComparisonChart');
                    if (!ctx) return;

                    if (this.yieldChart) {
                        this.yieldChart.destroy();
                    }

                    const labels = this.comparisonData.map(d => d.year);
                    const data = this.comparisonData.map(d => parseFloat(d.avg_yield));

                    this.yieldChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Average Yield (mt/ha)',
                                data: data,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Yield (mt/ha)'
                                    }
                                }
                            }
                        }
                    });
                },

                updateCropChart() {
                    const ctx = document.getElementById('cropPerformanceChart');
                    if (!ctx) return;

                    if (this.cropChart) {
                        this.cropChart.destroy();
                    }

                    const labels = this.cropPerformance.map(d => d.crop_type);
                    const data = this.cropPerformance.map(d => parseFloat(d.avg_yield));

                    this.cropChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Average Yield (mt/ha)',
                                data: data,
                                backgroundColor: ['#10b981', '#059669', '#047857', '#065f46', '#064e3b']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Yield (mt/ha)'
                                    }
                                }
                            }
                        }
                    });
                },

                updateMonthlyChart() {
                    const ctx = document.getElementById('monthlyTrendChart');
                    if (!ctx) return;

                    if (this.monthlyChart) {
                        this.monthlyChart.destroy();
                    }

                    const labels = this.monthlyData.map(d => d.month_name);
                    const data = this.monthlyData.map(d => parseFloat(d.avg_yield));

                    this.monthlyChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Average Yield (mt/ha)',
                                data: data,
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Yield (mt/ha)'
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
</body>
</html>
