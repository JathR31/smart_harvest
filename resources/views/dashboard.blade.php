<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar { background: linear-gradient(180deg, #047857 0%, #065f46 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
    </style>
</head>
<body class="bg-gray-50 flex" x-data="farmerDashboard()">
    <!-- Sidebar -->
    <aside class="sidebar w-64 min-h-screen text-white flex-shrink-0">
        <div class="p-6">
            <div class="flex items-center space-x-2 mb-8">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/></svg>
                <span class="text-xl font-semibold">SmartHarvest</span>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Main</p>
                <a href="{{ route('dashboard') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-2.5 rounded transition">
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
                <a href="{{ route('yield.analysis') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Yield Analysis</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Weather</p>
                <a href="{{ route('forecast') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
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
                    <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
                    <!-- Municipality Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="font-medium" x-text="selectedMunicipality"></span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute top-full left-0 mt-2 w-56 bg-white border rounded-lg shadow-lg z-10 max-h-96 overflow-y-auto" style="display: none;">
                            <template x-for="municipality in municipalities" :key="municipality">
                                <button @click="selectMunicipality(municipality); open = false" 
                                        class="block w-full text-left px-4 py-2 hover:bg-gray-50 transition"
                                        :class="{'bg-green-50 text-green-700 font-semibold': municipality === selectedMunicipality}">
                                    <span x-text="municipality"></span>
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

        <!-- Dashboard Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Top Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Year Expected Harvest -->
                <div class="bg-white rounded-lg shadow p-6 relative">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">Year Expected Harvest</p>
                    <p class="text-3xl font-bold text-gray-800 mb-1"><span x-text="stats.expected_harvest"></span> <span class="text-base font-normal text-gray-600">metric tons</span></p>
                    <p class="text-xs" :class="stats.percentage_change >= 0 ? 'text-green-600' : 'text-red-600'">
                        <span x-text="(stats.percentage_change >= 0 ? '↑ ' : '↓ ') + Math.abs(stats.percentage_change) + '%'"></span> vs last year
                    </p>
                </div>

                <!-- Current Climate -->
                <div class="bg-white rounded-lg shadow p-6 relative">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">Current Climate</p>
                    <p class="text-3xl font-bold text-gray-800 mb-1"><span x-text="climate.current?.weather_condition || 'Loading...'"></span></p>
                    <p class="text-xs text-gray-600"><span x-text="climate.current?.avg_temperature"></span>°C • Rainfall: <span x-text="climate.current?.rainfall"></span>mm</p>
                </div>

                <!-- Best Planting Date -->
                <div class="bg-white rounded-lg shadow p-6 relative">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">Next Optimal Planting</p>
                    <p class="text-2xl font-bold text-gray-800 mb-1"><span x-text="optimal.next_date"></span></p>
                    <p class="text-xs text-gray-600"><span x-text="optimal.crop + ' - ' + optimal.variety"></span></p>
                </div>

                <!-- Expected Yield -->
                <div class="bg-white rounded-lg shadow p-6 relative">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">Expected Yield</p>
                    <p class="text-3xl font-bold text-gray-800 mb-1"><span x-text="optimal.expected_yield"></span> <span class="text-base font-normal text-gray-600">mt/ha</span></p>
                    <p class="text-xs text-gray-600"><span x-text="optimal.confidence"></span> confidence</p>
                </div>
            </div>

            <!-- 7-Day Weather Forecast -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold text-green-700 mb-6">7-Day Weather Forecast</h2>
                <div class="grid grid-cols-7 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-3">Mon</p>
                        <svg class="w-12 h-12 mx-auto mb-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <p class="text-lg font-semibold">31°</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-3">Tue</p>
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/></svg>
                        <p class="text-lg font-semibold">30°</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-3">Wed</p>
                        <svg class="w-12 h-12 mx-auto mb-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/><path d="M8 16l1.5 4m3-4l1.5 4m3-4l1.5 4"/></svg>
                        <p class="text-lg font-semibold">29°</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-3">Thu</p>
                        <svg class="w-12 h-12 mx-auto mb-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/><path d="M8 16l1.5 4m3-4l1.5 4m3-4l1.5 4"/></svg>
                        <p class="text-lg font-semibold">28°</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-3">Fri</p>
                        <svg class="w-12 h-12 mx-auto mb-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <p class="text-lg font-semibold">30°</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-3">Sat</p>
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/><circle cx="10" cy="18" r="1" opacity="0.5"/><circle cx="14" cy="18" r="1" opacity="0.5"/></svg>
                        <p class="text-lg font-semibold">31°</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-3">Sun</p>
                        <svg class="w-12 h-12 mx-auto mb-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                        <p class="text-lg font-semibold">32°</p>
                    </div>
                </div>
            </div>

            <!-- Your Planting Guide -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold text-green-700 mb-6">Your Planting Guide</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Preparation Phase -->
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="text-lg font-semibold text-green-700 mb-2">1. Preparation Phase</h3>
                        <p class="text-sm text-gray-600 mb-3">(Week 1-2)</p>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li>• Clear and prepare planting bed area</li>
                            <li>• Purchase cabbage seeds</li>
                            <li>• Test soil pH (ideal: 5.8-6.5)</li>
                            <li>• Repair irrigation canals</li>
                        </ul>
                    </div>

                    <!-- Planting Phase -->
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="text-lg font-semibold text-green-700 mb-2">2. Planting Phase</h3>
                        <p class="text-sm text-gray-600 mb-3">(Week 3-4)</p>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li>• Sow seeds in nursery beds</li>
                            <li>• Transplant seedlings after 21 days</li>
                            <li>• Maintain 45cm spacing</li>
                            <li>• Initial shallow watering (2-3cm)</li>
                        </ul>
                    </div>

                    <!-- Growth Phase -->
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="text-lg font-semibold text-green-700 mb-2">3. Growth Phase</h3>
                        <p class="text-sm text-gray-600 mb-3">(Week 5-12)</p>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li>• First fertilizer at day 30</li>
                            <li>• Second fertilizer at day 60</li>
                            <li>• Weed every other week</li>
                            <li>• Maintain consistent moisture level</li>
                        </ul>
                    </div>

                    <!-- Harvest Phase -->
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="text-lg font-semibold text-green-700 mb-2">4. Harvest Phase</h3>
                        <p class="text-sm text-gray-600 mb-3">(Week 13-16)</p>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li>• Stop watering 3-5 days before harvest</li>
                            <li>• Check head firmness</li>
                            <li>• Harvest when heads are tight and firm</li>
                            <li>• Store in a cool, dry place</li>
                        </ul>
                    </div>
                </div>

                <!-- Best Time to Plant & Weather Outlook -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <div>
                        <h3 class="text-lg font-semibold text-green-700 mb-3">Best Time to Plant</h3>
                        <p class="text-sm text-gray-700">Based on 5-6 years of past yield data and local climate data, the best planting window for your cabbage is between <strong>May 20 and June 10</strong>. Planting within this period helps you take advantage of ideal temperature and rainfall patterns.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-green-700 mb-3">Weather Outlook</h3>
                        <p class="text-sm text-gray-700">Expect moderate rainfall and mild temperatures in the coming weeks. These conditions support healthy crop growth, but it's best to delay planting after heavy rain (4+ cm daily) to avoid waterlogging and uneven germination.</p>
                    </div>
                </div>

                <!-- What to Do This Week -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-green-700 mb-4">What to Do This Week</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
                            <p class="text-sm text-gray-800">Prepare your seedbeds by clearing and leveling the area</p>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
                            <p class="text-sm text-gray-800">Buy cabbage seeds from your certified dealers</p>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
                            <p class="text-sm text-gray-800">Test your soil pH level and adjust if needed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Harvest Data -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-green-700">Recent Harvest Data</h2>
                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Export</span>
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Crop</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Municipality</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Year</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Area (HA)</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Production (MT)</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Yield (MT/HA)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <template x-for="harvest in recentHarvests" :key="harvest.id">
                                <tr class="border-b">
                                    <td class="py-4 text-gray-800">
                                        <div class="font-semibold" x-text="harvest.crop_type"></div>
                                        <div class="text-xs text-gray-500" x-text="harvest.variety"></div>
                                    </td>
                                    <td class="py-4 text-gray-800" x-text="harvest.municipality"></td>
                                    <td class="py-4 text-gray-800" x-text="harvest.year"></td>
                                    <td class="py-4 text-gray-800" x-text="harvest.area_planted"></td>
                                    <td class="py-4 text-gray-800" x-text="harvest.yield_amount"></td>
                                    <td class="py-4 text-gray-800 font-semibold" x-text="(harvest.yield_amount / harvest.area_planted).toFixed(1)"></td>
                                </tr>
                            </template>
                            <tr x-show="recentHarvests.length === 0">
                                <td colspan="6" class="py-4 text-center text-gray-500">No recent harvest data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function farmerDashboard() {
            return {
                selectedMunicipality: 'La Trinidad',
                municipalities: [
                    'Atok', 'Baguio City', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
                    'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 
                    'Sablan', 'Tuba', 'Tublay'
                ],
                stats: {
                    expected_harvest: 0,
                    percentage_change: 0
                },
                climate: {
                    current: null,
                    historical_avg: null
                },
                optimal: {
                    crop: '',
                    variety: '',
                    next_date: '',
                    expected_yield: 0,
                    confidence: ''
                },
                recentHarvests: [],
                loading: false,

                init() {
                    this.loadDashboardData();
                },

                selectMunicipality(municipality) {
                    this.selectedMunicipality = municipality;
                    this.loadDashboardData();
                },

                async loadDashboardData() {
                    this.loading = true;
                    
                    try {
                        // Load dashboard stats
                        const statsResponse = await fetch('/api/dashboard/stats');
                        if (statsResponse.ok) {
                            const statsData = await statsResponse.json();
                            this.stats = statsData.stats || this.stats;
                            this.recentHarvests = statsData.recent_harvests || [];
                        }

                        // Load climate data for selected municipality
                        const climateResponse = await fetch(`/api/climate/current?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        if (climateResponse.ok) {
                            const climateData = await climateResponse.json();
                            this.climate = climateData;
                        }

                        // Load optimal planting data
                        const optimalResponse = await fetch(`/api/planting/optimal?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        if (optimalResponse.ok) {
                            const optimalData = await optimalResponse.json();
                            if (optimalData.optimal_crop) {
                                this.optimal = {
                                    crop: optimalData.optimal_crop.crop_type,
                                    variety: optimalData.optimal_crop.variety,
                                    next_date: this.formatDate(optimalData.next_planting_date),
                                    expected_yield: optimalData.optimal_crop.avg_yield.toFixed(1),
                                    confidence: optimalData.confidence || 'High'
                                };
                            }
                        }
                    } catch (error) {
                        console.error('Error loading dashboard data:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                }
            }
        }
    </script>

</body>
</html>
