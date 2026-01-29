<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/translation.js') }}?v={{ time() }}"></script>
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
                <span class="text-2xl">🌱</span>
                <span class="text-xl font-semibold">SmartHarvest</span>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3" data-translate data-translate-id="menu-main">Main</p>
                <a href="{{ route('dashboard') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span data-translate data-translate-id="menu-dashboard">Dashboard</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3" data-translate data-translate-id="menu-analysis">Analysis</p>
                <a href="{{ route('planting.schedule') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span data-translate data-translate-id="menu-planting-schedule">Planting Schedule</span>
                </a>
                <a href="{{ route('yield.analysis') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span data-translate data-translate-id="menu-yield-analysis">Yield Analysis</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3" data-translate data-translate-id="menu-weather">Weather</p>
                <a href="{{ route('forecast') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    <span data-translate data-translate-id="menu-forecast">Forecast</span>
                </a>
            </div>

            <div>
                <p class="text-xs uppercase text-green-300 mb-3" data-translate data-translate-id="menu-account">Account</p>
                <a href="{{ route('settings') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span data-translate data-translate-id="menu-settings">Settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" onsubmit="sessionStorage.setItem('isLoggedOut','true');">
                    @csrf
                    <button type="submit" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span data-translate data-translate-id="menu-logout">Logout</span>
                    </button>
                </form>
                <a href="{{ route('homepage') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span data-translate data-translate-id="back-to-home">Back to Home</span>
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
                    <h1 class="text-2xl font-semibold text-gray-800" data-translate data-translate-id="dashboard-title">Dashboard</h1>
                    <!-- ML Status Badge -->
                    <span x-show="mlConnected" class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        <span data-translate data-translate-id="ml-active">Active</span>
                    </span>
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
                    <!-- Language Selector -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                            <span class="text-sm font-medium" x-text="selectedLanguageName"></span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50" style="display: none;">
                            <button @click="changeLanguage('en', 'English'); open = false" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm" :class="{'bg-green-50 text-green-700': selectedLanguage === 'en'}">English</button>
                            <button @click="changeLanguage('tl', 'Tagalog'); open = false" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm" :class="{'bg-green-50 text-green-700': selectedLanguage === 'tl'}">Tagalog</button>
                            <button @click="changeLanguage('ilo', 'Ilocano'); open = false" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm" :class="{'bg-green-50 text-green-700': selectedLanguage === 'ilo'}">Ilocano</button>
                        </div>
                    </div>
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
        <main class="flex-1 p-8 overflow-y-auto bg-gray-50">
            <!-- Top 3 Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Weather Forecast Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-50 to-green-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-2xl">🌧️</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Weather Forecast</p>
                            <h3 class="text-xl font-bold text-green-700" x-text="weatherPrediction.condition || 'Good Rain'"></h3>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700">
                        Expected rainfall: <span class="font-semibold" x-text="weatherPrediction.rainfall || '120'"></span>mm this month
                    </p>
                </div>

                <!-- Best Planting Date Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-50 to-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Best Planting Date</p>
                            <h3 class="text-lg font-bold text-green-700" x-text="optimal.planting_window || 'May 15 - June 5'"></h3>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700">Optimal window for highest yield</p>
                </div>

                <!-- Recommended Variety Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-50 to-green-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-2xl">🥬</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Recommended Variety</p>
                            <h3 class="text-xl font-bold text-green-700" x-text="optimal.crop || 'Cabbage'"></h3>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700">Cool season, high yield vegetable</p>
                </div>
            </div>

            <!-- Top 5 Recommended Crops -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-green-700">Top 5 Recommended Crops</h2>
                    <a href="{{ route('planting.schedule') }}" class="text-sm text-green-600 hover:text-green-700 font-medium flex items-center">
                        View Full Schedule
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <template x-for="(crop, index) in topCrops.slice(0, 5)" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition" :class="index === 0 ? 'border-green-500 bg-green-50' : ''">
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2 py-1 text-xs font-bold rounded-full" 
                                      :class="index === 0 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700'"
                                      x-text="'#' + (index + 1)">
                                </span>
                                <span class="text-xs text-gray-500" x-text="crop.variety || 'Mixed'"></span>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-2" x-text="crop.crop"></h4>
                            <div class="space-y-1 text-xs text-gray-600">
                                <div class="flex justify-between">
                                    <span>Yield:</span>
                                    <span class="font-semibold text-green-600" x-text="crop.yield_prediction || crop.historical_yield"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Plant:</span>
                                    <span class="font-semibold" x-text="crop.optimal_planting"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Harvest:</span>
                                    <span class="font-semibold" x-text="crop.expected_harvest"></span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <span class="text-xs px-2 py-1 rounded" 
                                      :class="crop.status === 'Recommended' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                      x-text="crop.status">
                                </span>
                            </div>
                        </div>
                    </template>
                    <template x-if="topCrops.length === 0">
                        <div class="col-span-5 text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p>Loading top crop recommendations...</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- 7-Day Weather Forecast -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-green-700 mb-6">7-Day Weather Forecast</h2>
                <div class="grid grid-cols-7 gap-3">
                    <template x-for="(day, index) in weatherForecast.slice(0, 7)" :key="index">
                        <div class="text-center p-3 rounded-lg hover:bg-gray-50 transition">
                            <p class="text-xs text-gray-600 font-medium mb-2" x-text="day.dayName"></p>
                            <div class="my-3" x-html="getWeatherIcon(day.icon, day.description)"></div>
                            <p class="text-xl font-bold text-gray-800" x-text="Math.round(day.temp) + '°'"></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Best Time to Plant & Weather Outlook -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-green-700 mb-4">Best Time to Plant</h3>
                    <p class="text-sm text-gray-600 font-medium mb-2" x-text="optimal.planting_window || 'May 20 and June 10'"></p>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Based on 5-6 years of past yield data and local climate data, the best planting window is between 
                        <span class="font-semibold" x-text="optimal.planting_dates || 'May 20 and June 10'"></span>. 
                        Planting within this period helps you take advantage of ideal temperature and rainfall patterns.
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-green-700 mb-4">Weather Outlook</h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Expect moderate rainfall and mild temperatures in the coming weeks. These conditions support healthy crop growth, 
                        but it's best to delay planting after heavy rain (4+ cm daily) to avoid waterlogging issues.
                    </p>
                </div>
            </div>

            <!-- Current Market Prices -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-green-700">Current Market Prices</h2>
                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Latest prices for major Cordillera crops (Updated: January 20, 2026)</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <template x-for="(price, index) in marketPrices" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-gray-800" x-text="price.crop"></h4>
                                <span class="text-xs px-2 py-1 rounded" 
                                      :class="price.trend > 0 ? 'bg-green-100 text-green-700' : price.trend < 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700'"
                                      x-text="price.trend > 0 ? '+' + price.trend + '%' : price.trend + '%'">
                                </span>
                            </div>
                            <p class="text-2xl font-bold text-green-600 mb-2">₱<span x-text="price.price"></span><span class="text-sm text-gray-500">/kg</span></p>
                            <div class="flex items-center justify-between text-xs">
                                <span class="px-2 py-1 rounded" 
                                      :class="price.demand === 'High' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'">
                                    Market Demand: <span class="font-semibold" x-text="price.demand"></span>
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Price Insights -->
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-base font-semibold text-blue-900 mb-2">💡 Price Insights</h3>
                        <p class="text-sm text-blue-800 leading-relaxed">
                            Prices are based on current market conditions in La Trinidad Trading Post and Baguio City Public Market. Highland Cabbage and Potatoes show strong upward 
                            trend due to high demand and favorable weather conditions. Plan your planting accordingly to maximize returns.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Conclusion & Future Outlook -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-green-700 mb-4">Conclusion & Future Outlook</h2>
                <div class="space-y-4 text-sm text-gray-700 leading-relaxed">
                    <p>
                        Your Farm Analysis combines up to 5-6 years of historical crop yield data, demonstrating that data-driven decision-making combined with sustainable agricultural practices leads to superior outcomes. The 9.8% 
                        performance advantage over regional averages validates your strategic approach to crop selection, timing, and resource management.
                    </p>
                    <p>
                        Looking ahead to 2026, the forecast suggests continued improvement with incremental improvements positions your farm for continued growth. Climate projections suggest 
                        similar favorable conditions, with potential for achieving 6.1MT/ha average yields through incremental optimizations. The SmartHarvest system will continue monitoring and analyzing 
                        data to provide timely insights for maximizing your farming success.
                    </p>
                </div>
            </div>
        </main>
    </div>

    <script>
        function farmerDashboard() {
            return {
                selectedLanguage: localStorage.getItem('preferredLanguage') || 'en',
                selectedLanguageName: localStorage.getItem('preferredLanguageName') || 'English',
                originalTexts: {},
                selectedMunicipality: '{{ $userMunicipality ?? "La Trinidad" }}',
                municipalities: [
                    'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
                    'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 
                    'Sablan', 'Tuba', 'Tublay'
                ],
                stats: {
                    expected_harvest: 0,
                    percentage_change: 0,
                    ml_confidence: 0
                },
                climate: {
                    current: null,
                    historical_avg: null
                },
                optimal: {
                    crop: '',
                    variety: '',
                    next_date: '',
                    planting_window: '',
                    planting_dates: '',
                    expected_yield: 0,
                    confidence: ''
                },
                weatherPrediction: {
                    condition: 'Good Rain',
                    rainfall: '120'
                },
                marketPrices: [
                    { crop: 'Highland Cabbage', price: '25', demand: 'High', trend: 4 },
                    { crop: 'Tinawon Rice', price: '45', demand: 'High', trend: 0 },
                    { crop: 'Lettuce', price: '35', demand: 'Medium', trend: -3 },
                    { crop: 'Potatoes', price: '28', demand: 'High', trend: 12 },
                    { crop: 'Carrots', price: '30', demand: 'Medium', trend: 4 },
                    { crop: 'Strawberries', price: '250', demand: 'High', trend: 8 }
                ],
                weatherForecast: [],
                recentHarvests: [],
                topCrops: [],
                loading: false,
                mlConnected: false,

                init() {
                    console.log('Dashboard initialized for municipality:', this.selectedMunicipality);
                    this.loadDashboardData();
                    SmartHarvestTranslation.init();
                    if (this.selectedLanguage !== 'en') {
                        this.translatePage(this.selectedLanguage);
                    }
                },
                
                async changeLanguage(code, name) {
                    this.selectedLanguage = code;
                    this.selectedLanguageName = name;
                    localStorage.setItem('preferredLanguage', code);
                    localStorage.setItem('preferredLanguageName', name);
                    
                    if (code !== 'en') {
                        await this.translatePage(code);
                    } else {
                        location.reload();
                    }
                },
                
                async translatePage(targetLang) {
                    const elements = document.querySelectorAll('[data-translate]');
                    const texts = Array.from(elements).map(el => {
                        const id = el.getAttribute('data-translate-id');
                        if (!this.originalTexts[id]) {
                            this.originalTexts[id] = el.textContent.trim();
                        }
                        return this.originalTexts[id];
                    });
                    
                    if (texts.length === 0) return;
                    
                    try {
                        const response = await fetch('/api/translate/batch', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                texts: texts,
                                target_language: targetLang
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            elements.forEach((el, index) => {
                                if (data.translations[index]?.translatedText) {
                                    el.textContent = data.translations[index].translatedText;
                                }
                            });
                        }
                    } catch (error) {
                        console.error('Translation error:', error);
                    }
                },

                getWeatherIcon(iconCode, description) {
                    const icons = {
                        '01d': '<svg class="w-12 h-12 mx-auto mb-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>',
                        '01n': '<svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.75 4.09L15.22 6.03L16.13 9.09L13.5 7.28L10.87 9.09L11.78 6.03L9.25 4.09L12.44 4L13.5 1L14.56 4L17.75 4.09M21.25 11L19.61 12.25L20.2 14.23L18.5 13.06L16.8 14.23L17.39 12.25L15.75 11L17.81 10.95L18.5 9L19.19 10.95L21.25 11M18.97 15.95C19.8 15.87 20.69 17.05 20.16 17.8C19.84 18.25 19.5 18.67 19.08 19.07C15.17 23 8.84 23 4.94 19.07C1.03 15.17 1.03 8.83 4.94 4.93C5.34 4.53 5.76 4.17 6.21 3.85C6.96 3.32 8.14 4.21 8.06 5.04C7.79 7.9 8.75 10.87 10.95 13.06C13.14 15.26 16.1 16.22 18.97 15.95M17.33 17.97C14.5 17.81 11.7 16.64 9.53 14.5C7.36 12.31 6.2 9.5 6.04 6.68C3.23 9.82 3.34 14.64 6.35 17.66C9.37 20.67 14.19 20.78 17.33 17.97Z"/></svg>',
                        '02d': '<svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/></svg>',
                        '03d': '<svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/></svg>',
                        '04d': '<svg class="w-12 h-12 mx-auto mb-2 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/></svg>',
                        '09d': '<svg class="w-12 h-12 mx-auto mb-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/><path d="M8 16l1.5 4m3-4l1.5 4m3-4l1.5 4"/></svg>',
                        '10d': '<svg class="w-12 h-12 mx-auto mb-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/><path d="M8 16l1.5 4m3-4l1.5 4m3-4l1.5 4"/></svg>',
                        '11d': '<svg class="w-12 h-12 mx-auto mb-2 text-yellow-600" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/><path d="M13 14l-2 4h2l-2 4"/></svg>',
                        '13d': '<svg class="w-12 h-12 mx-auto mb-2 text-blue-200" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 20C4 20 2 18 2 15.5c0-2.5 2-4.5 4.5-4.5.3 0 .6 0 .9.1C8.4 8.8 10.6 7 13 7c3.3 0 6 2.7 6 6 0 .3 0 .6-.1.9 1.4.4 2.4 1.7 2.4 3.2 0 1.9-1.6 3.4-3.5 3.4h-11z"/><circle cx="8" cy="18" r="1"/><circle cx="12" cy="18" r="1"/><circle cx="16" cy="18" r="1"/></svg>',
                        '50d': '<svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M3 15h18M3 10h18M3 5h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.5"/></svg>'
                    };
                    return icons[iconCode] || icons['02d'];
                },

                selectMunicipality(municipality) {
                    this.selectedMunicipality = municipality;
                    this.loadDashboardData();
                },

                async loadDashboardData() {
                    this.loading = true;
                    
                    try {
                        console.log('Loading dashboard data for:', this.selectedMunicipality);
                        
                        // Load dashboard stats using ML yield analysis API (same as Yield Analysis tab)
                        const timestamp = new Date().getTime();
                        const yieldAnalysisResponse = await fetch(`{{ url('/api/ml/yield/analysis') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}&year=2025&_t=${timestamp}`);
                        
                        if (yieldAnalysisResponse.ok) {
                            const yieldData = await yieldAnalysisResponse.json();
                            console.log('📊 ML Yield Analysis Response:', yieldData);
                            
                            // Use ML data from yield analysis (same source as Yield Analysis tab)
                            if (yieldData.stats && yieldData.ml_api_connected) {
                                this.stats.expected_harvest = yieldData.stats.total_production || '0';
                                this.stats.ml_confidence = 85;
                                this.mlConnected = true;
                                
                                // Calculate percentage change from comparison data
                                if (yieldData.comparison && yieldData.comparison.length > 1) {
                                    const latest = yieldData.comparison[yieldData.comparison.length - 1];
                                    const previous = yieldData.comparison[yieldData.comparison.length - 2];
                                    if (latest && previous && previous.actual > 0) {
                                        this.stats.percentage_change = Math.round(((latest.predicted - previous.actual) / previous.actual) * 100);
                                    }
                                }
                                
                                console.log('✓ Using ML data from Yield Analysis API');
                                console.log('✓ Expected Harvest:', this.stats.expected_harvest);
                                console.log('✓ ML Connected:', this.mlConnected);
                            }
                        }
                        
                        // Load recent harvests from database
                        console.log('📋 Fetching recent harvests for:', this.selectedMunicipality);
                        const harvestsResponse = await fetch(`{{ url('/api/dashboard/stats') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}&_t=${timestamp}`);
                        console.log('📋 Harvests response status:', harvestsResponse.status);
                        
                        if (harvestsResponse.ok) {
                            const harvestsData = await harvestsResponse.json();
                            console.log('📋 Full harvests response:', harvestsData);
                            console.log('📋 Recent harvests array:', harvestsData.recent_harvests);
                            
                            // Ensure we have an array
                            if (harvestsData.recent_harvests && Array.isArray(harvestsData.recent_harvests)) {
                                this.recentHarvests = harvestsData.recent_harvests;
                                console.log('✓ Recent Harvests loaded:', this.recentHarvests.length, 'records');
                                console.log('✓ Sample data:', this.recentHarvests[0]);
                            } else {
                                console.error('❌ recent_harvests is not an array:', typeof harvestsData.recent_harvests);
                                this.recentHarvests = [];
                            }
                        } else {
                            console.error('❌ Failed to load recent harvests:', harvestsResponse.status);
                            const errorText = await harvestsResponse.text();
                            console.error('❌ Error response:', errorText);
                            this.recentHarvests = [];
                        }

                        // Load top crops recommendations
                        console.log('🌾 Fetching top crops for:', this.selectedMunicipality);
                        const topCropsResponse = await fetch(`{{ url('/api/planting/schedule') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}&_t=${timestamp}`);
                        if (topCropsResponse.ok) {
                            const topCropsData = await topCropsResponse.json();
                            if (Array.isArray(topCropsData)) {
                                this.topCrops = topCropsData.slice(0, 5); // Get top 5
                                console.log('✓ Top Crops loaded:', this.topCrops.length, 'crops');
                            } else {
                                console.error('❌ topCrops is not an array');
                                this.topCrops = [];
                            }
                        } else {
                            console.error('❌ Failed to load top crops:', topCropsResponse.status);
                            this.topCrops = [];
                        }

                        // Load climate data for selected municipality
                        const climateResponse = await fetch(`{{ url('/api/climate/current') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        if (climateResponse.ok) {
                            const climateData = await climateResponse.json();
                            this.climate = climateData;
                        }

                        // Load optimal planting data with ML predictions
                        const optimalResponse = await fetch(`{{ url('/api/planting/optimal') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        if (optimalResponse.ok) {
                            const optimalData = await optimalResponse.json();
                            this.optimal = {
                                crop: optimalData.crop || 'Cabbage',
                                variety: optimalData.variety || 'Scorpio',
                                next_date: optimalData.next_date || 'N/A',
                                planting_window: 'May 15 - June 5',
                                planting_dates: 'May 20 and June 10',
                                expected_yield: optimalData.expected_yield?.toFixed ? optimalData.expected_yield.toFixed(1) : optimalData.expected_yield || '0.0',
                                historical_yield: optimalData.historical_yield ? (optimalData.historical_yield.toFixed ? optimalData.historical_yield.toFixed(1) : optimalData.historical_yield) : null,
                                confidence: optimalData.confidence || 'Medium',
                                confidence_score: optimalData.confidence_score || null,
                                ml_status: optimalData.ml_status || 'unknown'
                            };
                            console.log('✓ Optimal planting data loaded:', this.optimal);
                        }

                        // Update weather prediction based on climate data
                        if (this.climate.current) {
                            const rainfall = this.climate.current.rainfall || 120;
                            this.weatherPrediction = {
                                condition: rainfall > 100 ? 'Good Rain' : rainfall > 50 ? 'Moderate Rain' : 'Light Rain',
                                rainfall: rainfall.toString()
                            };
                        }

                        // Load 7-day weather forecast
                        const weatherResponse = await fetch(`{{ url('/api/weather') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}&_t=${timestamp}`);
                        if (weatherResponse.ok) {
                            const weatherData = await weatherResponse.json();
                            console.log('🌤️ Weather data:', weatherData);
                            
                            if (weatherData.daily && Array.isArray(weatherData.daily)) {
                                const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                this.weatherForecast = weatherData.daily.slice(0, 7).map(day => ({
                                    dayName: days[new Date(day.dt * 1000).getDay()],
                                    temp: day.temp.max,
                                    icon: day.weather[0].icon,
                                    description: day.weather[0].description
                                }));
                                console.log('✓ Weather forecast loaded:', this.weatherForecast.length, 'days');
                            }
                        }
                    } catch (error) {
                        console.error('Error loading dashboard data:', error);
                        this.mlConnected = false;
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

    <script>
        // Prevent back button after logout
        if (typeof(Storage) !== 'undefined') {
            if (sessionStorage.getItem('isLoggedOut') === 'true') {
                sessionStorage.removeItem('isLoggedOut');
                window.location.replace('{{ route("login") }}');
            }
        }
        
        // Disable browser back button
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>

</body>
</html>
