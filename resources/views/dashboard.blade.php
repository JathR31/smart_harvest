<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/translation-v2.js') }}?v={{ time() }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar { background: linear-gradient(180deg, #047857 0%, #065f46 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
        
        /* Fixed layout styles */
        body { height: 100vh; overflow: hidden; }
        .sidebar-container { position: fixed; top: 0; left: 0; bottom: 0; width: 16rem; overflow-y: auto; z-index: 40; }
        .main-container { margin-left: 16rem; height: 100vh; display: flex; flex-direction: column; }
        .content-scrollable { overflow-y: auto; flex: 1; }
        .da-banner { position: fixed; top: 0; left: 16rem; right: 0; z-index: 50; }
        .main-container-with-banner { padding-top: 3.5rem; }
    </style>
</head>
<body class="bg-gray-50" x-data="farmerDashboard()">
    @if(isset($viewingAsFarmer) && $viewingAsFarmer)
    <!-- DA Officer View Banner -->
    <div class="da-banner bg-gradient-to-r from-green-700 to-green-600 text-white py-3 px-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span class="font-medium">You are viewing the Farmer Dashboard as a DA Officer</span>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 px-4 py-2 bg-white text-green-700 rounded-lg hover:bg-green-50 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to DA Dashboard</span>
        </a>
    </div>
    @endif
    
    <!-- Sidebar -->
    <aside class="sidebar sidebar-container w-64 text-white">
        <div class="p-6">
            <div class="flex items-center space-x-2 mb-8">
                <span class="text-2xl">🌱</span>
                <span class="text-xl font-semibold">SmartHarvest</span>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3" data-translate data-translate-id="menu-main">Main</p>
                <a href="#" @click.prevent="showSection = 'dashboard'" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition" :class="{'active': showSection === 'dashboard'}">
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

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3" data-translate data-translate-id="menu-info">Information</p>
                <a href="#" @click.prevent="showSection = 'market-prices'" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition" :class="{'active': showSection === 'market-prices'}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span data-translate data-translate-id="menu-market-prices">Market Prices</span>
                </a>
                <a href="#" @click.prevent="showSection = 'announcements'" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition" :class="{'active': showSection === 'announcements'}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    <span data-translate data-translate-id="menu-announcements">Announcements</span>
                    <span x-show="unreadAnnouncements > 0" class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5" x-text="unreadAnnouncements"></span>
                </a>
                <a href="#" @click.prevent="showSection = 'inbox'" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition" :class="{'active': showSection === 'inbox'}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span data-translate data-translate-id="menu-inbox">Inbox</span>
                    <span x-show="unreadMessages > 0" class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5" x-text="unreadMessages"></span>
                </a>
            </div>

            <div>
                <p class="text-xs uppercase text-green-300 mb-3" data-translate data-translate-id="menu-account">Account</p>
                <a href="{{ route('settings') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span data-translate data-translate-id="menu-settings">Settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" id="logoutForm" onsubmit="return handleLogout(event);">
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
    <div class="main-container {{ isset($viewingAsFarmer) && $viewingAsFarmer ? 'main-container-with-banner' : '' }}">
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
                            <button @click="changeLanguage('en', 'English'); open = false" class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm" :class="{'bg-green-50 text-green-700': selectedLanguage === 'en'}">
                                <span class="mr-2">🇺🇸</span> English
                            </button>
                            <button @click="changeLanguage('tl', 'Tagalog'); open = false" class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm" :class="{'bg-green-50 text-green-700': selectedLanguage === 'tl'}">
                                <span class="mr-2">🇵🇭</span> Tagalog
                            </button>
                            <button @click="changeLanguage('ilo', 'Ilokano'); open = false" class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm" :class="{'bg-green-50 text-green-700': selectedLanguage === 'ilo'}">
                                <span class="mr-2">🇵🇭</span> Ilokano
                            </button>
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
        <main class="content-scrollable p-8 bg-gray-50">
            
            <!-- Main Dashboard Section -->
            <div x-show="showSection === 'dashboard'">
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
                        <span>Latest prices for major Cordillera crops (Updated: {{ now()->format('F d, Y') }})</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <template x-for="(price, index) in marketPrices" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-gray-800" x-text="price.crop"></h4>
                                <template x-if="price.hasPrice && price.previousPrice">
                                    <span class="text-xs px-2 py-1 rounded" 
                                          :class="price.trend === 'up' ? 'bg-green-100 text-green-700' : price.trend === 'down' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700'"
                                          x-text="price.trend === 'up' ? '↑ Up' : price.trend === 'down' ? '↓ Down' : '→ Stable'">
                                    </span>
                                </template>
                            </div>
                            <template x-if="price.hasPrice">
                                <p class="text-2xl font-bold text-green-600 mb-2">₱<span x-text="price.price.toFixed(2)"></span><span class="text-sm text-gray-500">/kg</span></p>
                            </template>
                            <template x-if="!price.hasPrice">
                                <p class="text-xl font-semibold text-gray-400 mb-2">Not Set</p>
                            </template>
                            <template x-if="price.hasPrice">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="px-2 py-1 rounded" 
                                          :class="price.demand === 'high' || price.demand === 'very_high' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'">
                                        Market Demand: <span class="font-semibold capitalize" x-text="price.demand.replace('_', ' ')"></span>
                                    </span>
                                </div>
                            </template>
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
            </div> <!-- End Main Dashboard Section -->

            <!-- Market Prices Section -->
            <div x-show="showSection === 'market-prices'" x-cloak
                 x-data="{
                    pricesCurrentPage: 1,
                    pricesPerPage: 10,
                    pricesSearchQuery: '',
                    pricesSortColumn: 'crop_name',
                    pricesSortDirection: 'asc',
                    allPrices: [],
                    
                    init() {
                        this.loadPricesData();
                    },
                    
                    async loadPricesData() {
                        try {
                            const response = await fetch(`{{ url('/api/market-prices') }}`);
                            if (response.ok) {
                                this.allPrices = await response.json();
                            }
                        } catch (error) {
                            console.error('Error loading market prices:', error);
                        }
                    },
                    
                    get filteredPrices() {
                        let filtered = this.allPrices.filter(p => 
                            p.crop_name.toLowerCase().includes(this.pricesSearchQuery.toLowerCase()) ||
                            (p.variety && p.variety.toLowerCase().includes(this.pricesSearchQuery.toLowerCase())) ||
                            (p.market_location && p.market_location.toLowerCase().includes(this.pricesSearchQuery.toLowerCase()))
                        );
                        
                        // Sort
                        filtered.sort((a, b) => {
                            let aVal = a[this.pricesSortColumn] ?? '';
                            let bVal = b[this.pricesSortColumn] ?? '';
                            
                            if (this.pricesSortColumn === 'price_per_kg' || this.pricesSortColumn === 'previous_price') {
                                aVal = parseFloat(aVal) || 0;
                                bVal = parseFloat(bVal) || 0;
                            } else {
                                aVal = String(aVal).toLowerCase();
                                bVal = String(bVal).toLowerCase();
                            }
                            
                            if (this.pricesSortDirection === 'asc') {
                                return aVal > bVal ? 1 : -1;
                            } else {
                                return aVal < bVal ? 1 : -1;
                            }
                        });
                        
                        return filtered;
                    },
                    
                    get paginatedPrices() {
                        const start = (this.pricesCurrentPage - 1) * this.pricesPerPage;
                        return this.filteredPrices.slice(start, start + this.pricesPerPage);
                    },
                    
                    get totalPages() {
                        return Math.ceil(this.filteredPrices.length / this.pricesPerPage);
                    },
                    
                    get startIndex() {
                        return (this.pricesCurrentPage - 1) * this.pricesPerPage + 1;
                    },
                    
                    get endIndex() {
                        return Math.min(this.pricesCurrentPage * this.pricesPerPage, this.filteredPrices.length);
                    },
                    
                    sortBy(column) {
                        if (this.pricesSortColumn === column) {
                            this.pricesSortDirection = this.pricesSortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.pricesSortColumn = column;
                            this.pricesSortDirection = 'asc';
                        }
                        this.pricesCurrentPage = 1;
                    },
                    
                    goToPage(page) {
                        if (page >= 1 && page <= this.totalPages) {
                            this.pricesCurrentPage = page;
                        }
                    },
                    
                    getTrendPercent(price) {
                        if (price.price_per_kg && price.previous_price && price.previous_price > 0) {
                            return Math.round(((price.price_per_kg - price.previous_price) / price.previous_price) * 100);
                        }
                        return 0;
                    },
                    
                    getCropIcon(cropName) {
                        const icons = {
                            'CABBAGE': '🥬', 'CHINESE CABBAGE': '🥬', 'LETTUCE': '🥗',
                            'CAULIFLOWER': '🥦', 'BROCCOLI': '🥦', 'SNAP BEANS': '🫛',
                            'GARDEN PEAS': '🫛', 'SWEET PEPPER': '🫑', 'WHITE POTATO': '🥔',
                            'CARROTS': '🥕', 'STRAWBERRIES': '🍓', 'TINAWON RICE': '🌾',
                            'HIGHLAND CABBAGE': '🥬', 'POTATOES': '🥔'
                        };
                        return icons[cropName.toUpperCase()] || '🌱';
                    },
                    
                    getDemandClass(level) {
                        const classes = {
                            'high': 'bg-green-100 text-green-700',
                            'very_high': 'bg-green-200 text-green-800',
                            'moderate': 'bg-yellow-100 text-yellow-700',
                            'low': 'bg-gray-100 text-gray-600'
                        };
                        return classes[level] || 'bg-gray-100 text-gray-600';
                    },
                    
                    formatDemand(level) {
                        if (!level) return 'N/A';
                        return level === 'very_high' ? 'Very High' : level.charAt(0).toUpperCase() + level.slice(1);
                    }
                 }">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Current Market Prices</h2>
                        <p class="text-gray-500 text-sm">Latest prices for major Cordillera crops (Updated: {{ now()->format('F d, Y') }})</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                </div>
                
                <!-- Search and Controls -->
                <div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="relative flex-1 max-w-md">
                            <input type="text" 
                                   x-model="pricesSearchQuery" 
                                   @input="pricesCurrentPage = 1"
                                   placeholder="Search crops, varieties, or locations..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600">Show:</label>
                            <select x-model="pricesPerPage" @change="pricesCurrentPage = 1" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="text-sm text-gray-600">per page</span>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th @click="sortBy('crop_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            Crop Name
                                            <svg x-show="pricesSortColumn === 'crop_name'" class="w-4 h-4" :class="pricesSortDirection === 'desc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </div>
                                    </th>
                                    <th @click="sortBy('variety')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            Variety
                                            <svg x-show="pricesSortColumn === 'variety'" class="w-4 h-4" :class="pricesSortDirection === 'desc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </div>
                                    </th>
                                    <th @click="sortBy('price_per_kg')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            Price (₱/kg)
                                            <svg x-show="pricesSortColumn === 'price_per_kg'" class="w-4 h-4" :class="pricesSortDirection === 'desc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Trend
                                    </th>
                                    <th @click="sortBy('demand_level')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            Demand
                                            <svg x-show="pricesSortColumn === 'demand_level'" class="w-4 h-4" :class="pricesSortDirection === 'desc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </div>
                                    </th>
                                    <th @click="sortBy('market_location')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        <div class="flex items-center gap-1">
                                            Market Location
                                            <svg x-show="pricesSortColumn === 'market_location'" class="w-4 h-4" :class="pricesSortDirection === 'desc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="price in paginatedPrices" :key="price.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xl" x-text="getCropIcon(price.crop_name)"></span>
                                                <span class="font-medium text-gray-900" x-text="price.crop_name"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="price.variety || '-'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <template x-if="price.price_per_kg">
                                                <span class="text-lg font-bold text-green-600">₱<span x-text="parseFloat(price.price_per_kg).toFixed(2)"></span></span>
                                            </template>
                                            <template x-if="!price.price_per_kg">
                                                <span class="text-gray-400">Not set</span>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <template x-if="price.price_per_kg && getTrendPercent(price) > 0">
                                                <span class="inline-flex items-center text-green-600 text-sm font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                                    +<span x-text="getTrendPercent(price)"></span>%
                                                </span>
                                            </template>
                                            <template x-if="price.price_per_kg && getTrendPercent(price) < 0">
                                                <span class="inline-flex items-center text-red-500 text-sm font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg>
                                                    <span x-text="getTrendPercent(price)"></span>%
                                                </span>
                                            </template>
                                            <template x-if="price.price_per_kg && getTrendPercent(price) === 0">
                                                <span class="text-gray-400 text-sm">0%</span>
                                            </template>
                                            <template x-if="!price.price_per_kg">
                                                <span class="text-gray-400 text-sm">-</span>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium" :class="getDemandClass(price.demand_level)" x-text="formatDemand(price.demand_level)"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="price.market_location || '-'"></td>
                                    </tr>
                                </template>
                                <template x-if="paginatedPrices.length === 0">
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-lg font-medium">No crops found</p>
                                                <p class="text-sm" x-show="pricesSearchQuery">Try adjusting your search terms</p>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-sm text-gray-600">
                                <template x-if="filteredPrices.length > 0">
                                    <span>Showing <span class="font-medium" x-text="startIndex"></span> to <span class="font-medium" x-text="endIndex"></span> of <span class="font-medium" x-text="filteredPrices.length"></span> results</span>
                                </template>
                                <template x-if="filteredPrices.length === 0">
                                    <span>No results found</span>
                                </template>
                            </div>
                            <div class="flex items-center gap-2" x-show="totalPages > 1">
                                <button @click="goToPage(1)" :disabled="pricesCurrentPage === 1" 
                                        class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                                    First
                                </button>
                                <button @click="goToPage(pricesCurrentPage - 1)" :disabled="pricesCurrentPage === 1" 
                                        class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                
                                <template x-for="page in totalPages" :key="page">
                                    <button x-show="page === 1 || page === totalPages || (page >= pricesCurrentPage - 1 && page <= pricesCurrentPage + 1)"
                                            @click="goToPage(page)" 
                                            :class="pricesCurrentPage === page ? 'bg-green-600 text-white border-green-600' : 'border-gray-300 hover:bg-gray-100'"
                                            class="px-3 py-1.5 text-sm border rounded-lg font-medium"
                                            x-text="page">
                                    </button>
                                </template>
                                
                                <button @click="goToPage(pricesCurrentPage + 1)" :disabled="pricesCurrentPage === totalPages" 
                                        class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                                <button @click="goToPage(totalPages)" :disabled="pricesCurrentPage === totalPages" 
                                        class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Last
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Insights -->
                <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="text-xl">💡</span>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-1">Price Insights</h4>
                            <p class="text-sm text-blue-800">Prices are based on current market conditions in La Trinidad Trading Post and Baguio City Public Market. Highland Cabbage and Potatoes show strong upward trends due to high demand and favorable weather conditions. Plan your planting accordingly to maximize returns.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcements Section -->
            <div x-show="showSection === 'announcements'" x-cloak>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Announcements</h2>
                <p class="text-gray-600 mb-4">Important updates and announcements from the Department of Agriculture.</p>
                
                <!-- Filters -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 mb-4">
                    <div class="flex flex-wrap items-center gap-4">
                        <!-- Priority Filter -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Priority:</label>
                            <select x-model="priorityFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 min-w-[140px]">
                                <option value="all">All Priorities</option>
                                <option value="urgent">🔴 Urgent</option>
                                <option value="high">🟡 High</option>
                                <option value="normal">🔵 Normal</option>
                            </select>
                        </div>
                        
                        <!-- Sort Order -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Sort:</label>
                            <select x-model="sortOrder" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 min-w-[140px]">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                            </select>
                        </div>
                        
                        <!-- Results Count -->
                        <div class="ml-auto text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                            <span class="font-semibold" x-text="filteredAnnouncements.length"></span>
                            <span x-text="filteredAnnouncements.length === 1 ? ' announcement' : ' announcements'"></span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(announcement, index) in filteredAnnouncements" :key="index">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div :class="{
                                        'bg-red-100': announcement.priority === 'urgent',
                                        'bg-yellow-100': announcement.priority === 'high',
                                        'bg-blue-100': announcement.priority === 'normal',
                                        'bg-gray-100': announcement.priority === 'low'
                                    }" class="w-10 h-10 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5" :class="{
                                            'text-red-600': announcement.priority === 'urgent',
                                            'text-yellow-600': announcement.priority === 'high',
                                            'text-blue-600': announcement.priority === 'normal',
                                            'text-gray-600': announcement.priority === 'low'
                                        }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800" x-text="announcement.title"></h3>
                                        <p class="text-xs text-gray-500" x-text="announcement.created_at"></p>
                                    </div>
                                </div>
                                <span :class="{
                                    'bg-red-100 text-red-700': announcement.type === 'urgent',
                                    'bg-yellow-100 text-yellow-700': announcement.type === 'weather',
                                    'bg-green-100 text-green-700': announcement.type === 'market',
                                    'bg-blue-100 text-blue-700': announcement.type === 'advisory',
                                    'bg-gray-100 text-gray-700': announcement.type === 'general'
                                }" class="text-xs px-2 py-1 rounded" x-text="announcement.type"></span>
                            </div>
                            <p class="text-gray-700" x-text="announcement.content"></p>
                        </div>
                    </template>
                    <!-- Empty State -->
                    <div x-show="filteredAnnouncements.length === 0" class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No announcements found</h3>
                        <p class="text-gray-500 text-sm">
                            <span x-show="priorityFilter !== 'all'">Try changing the filter to see more announcements</span>
                            <span x-show="priorityFilter === 'all' && announcements.length === 0">No announcements at this time.</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Inbox Section -->
            <div x-show="showSection === 'inbox'" x-cloak x-data="{
                selectedMessage: null,
                showMessageModal: false,
                showReplyModal: false,
                replyContent: '',
                sendSMS: false,
                
                async viewMessage(message) {
                    try {
                        const response = await fetch(`{{ url('/api/messages') }}/${message.id}`);
                        if (response.ok) {
                            const data = await response.json();
                            this.selectedMessage = data;
                            this.showMessageModal = true;
                            await this.loadMessages();
                        }
                    } catch (error) {
                        console.error('Error loading message:', error);
                    }
                },
                
                async submitReply() {
                    if (!this.replyContent.trim()) {
                        alert('Please enter a message');
                        return;
                    }
                    
                    try {
                        const response = await fetch(`{{ url('/api/messages') }}/${this.selectedMessage.message.id}/reply`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content
                            },
                            body: JSON.stringify({
                                content: this.replyContent,
                                send_sms: this.sendSMS
                            })
                        });
                        
                        if (response.ok) {
                            this.replyContent = '';
                            this.sendSMS = false;
                            this.showReplyModal = false;
                            alert('Reply sent successfully!' + (this.sendSMS ? ' SMS sent.' : ''));
                            await this.viewMessage(this.selectedMessage.message);
                            await this.loadMessages();
                        }
                    } catch (error) {
                        console.error('Error sending reply:', error);
                        alert('Error sending reply');
                    }
                }
            }">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Inbox</h2>
                        <p class="text-gray-600">Send and receive messages with DA Officers.</p>
                    </div>
                    <button @click="showComposeModal = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>New Message</span>
                    </button>
                </div>

                <!-- Tabs for Received/Sent -->
                <div x-data="{ inboxTab: 'received' }" class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="border-b border-gray-200">
                        <nav class="flex">
                            <button @click="inboxTab = 'received'" :class="{'border-b-2 border-green-500 text-green-600': inboxTab === 'received', 'text-gray-500': inboxTab !== 'received'}" class="px-6 py-3 font-medium">
                                Received (<span x-text="messages.received.length"></span>)
                            </button>
                            <button @click="inboxTab = 'sent'" :class="{'border-b-2 border-green-500 text-green-600': inboxTab === 'sent', 'text-gray-500': inboxTab !== 'sent'}" class="px-6 py-3 font-medium">
                                Sent (<span x-text="messages.sent.length"></span>)
                            </button>
                        </nav>
                    </div>
                    
                    <!-- Received Messages -->
                    <div x-show="inboxTab === 'received'" class="p-4">
                        <!-- Unread Messages Section -->
                        <div x-show="unreadMessages.length > 0">
                            <div class="flex items-center justify-between mb-3 pb-2 border-b-2 border-green-500">
                                <h3 class="text-sm font-bold text-green-700 uppercase tracking-wide flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    New Messages
                                </h3>
                                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full" x-text="unreadMessages.length"></span>
                            </div>
                            <template x-for="msg in unreadMessages" :key="msg.id">
                                <div @click="viewMessage(msg)" class="p-4 mb-2 border-b border-gray-100 hover:bg-gray-50 cursor-pointer bg-green-50 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="inline-block w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                <p class="font-bold text-gray-800" x-text="msg.subject"></p>
                                            </div>
                                            <p class="text-sm text-gray-500">From: <span class="font-medium" x-text="msg.sender_name"></span></p>
                                            <p class="text-sm text-gray-600 mt-2 line-clamp-2" x-text="msg.content"></p>
                                            <template x-if="msg.reply_count > 0">
                                                <div class="mt-2 flex items-center text-xs text-green-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                    </svg>
                                                    <span x-text="msg.reply_count + ' ' + (msg.reply_count === 1 ? 'reply' : 'replies')"></span>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="text-right ml-4">
                                            <p class="text-xs text-gray-400" x-text="msg.created_at"></p>
                                            <span class="inline-block mt-2 px-2 py-1 bg-green-500 text-white text-xs rounded-full font-medium">NEW</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Divider between new and previous messages -->
                        <div x-show="unreadMessages.length > 0 && readMessages.length > 0" class="my-6">
                            <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-300">
                                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                    Previous Messages
                                </h3>
                                <span class="text-xs text-gray-500" x-text="readMessages.length + ' read'"></span>
                            </div>
                        </div>
                        
                        <!-- Read Messages Section -->
                        <template x-for="msg in readMessages" :key="msg.id">
                            <div @click="viewMessage(msg)" class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-700" x-text="msg.subject"></p>
                                        <p class="text-sm text-gray-500">From: <span x-text="msg.sender_name"></span></p>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2" x-text="msg.content"></p>
                                        <template x-if="msg.reply_count > 0">
                                            <div class="mt-2 flex items-center text-xs text-gray-600">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                </svg>
                                                <span x-text="msg.reply_count + ' ' + (msg.reply_count === 1 ? 'reply' : 'replies')"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="text-right ml-4">
                                        <p class="text-xs text-gray-400" x-text="msg.created_at"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="messages.received.length === 0">
                            <div class="text-center py-12 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p>No messages received yet.</p>
                            </div>
                        </template>
                    </div>

                    <!-- Sent Messages -->
                    <div x-show="inboxTab === 'sent'" x-cloak class="p-4">
                        <template x-for="msg in messages.sent" :key="msg.id">
                            <div @click="viewMessage(msg)" class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800" x-text="msg.subject"></p>
                                        <p class="text-sm text-gray-500">To: <span x-text="msg.receiver_name"></span></p>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2" x-text="msg.content"></p>
                                        <div class="mt-2 flex items-center gap-3">
                                            <template x-if="msg.sent_as_sms">
                                                <span class="inline-flex items-center text-xs text-blue-600">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                    SMS sent
                                                </span>
                                            </template>
                                            <template x-if="msg.reply_count > 0">
                                                <span class="inline-flex items-center text-xs text-gray-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                    </svg>
                                                    <span x-text="msg.reply_count + ' ' + (msg.reply_count === 1 ? 'reply' : 'replies')"></span>
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400" x-text="msg.created_at"></p>
                                </div>
                            </div>
                        </template>
                        <template x-if="messages.sent.length === 0">
                            <div class="text-center py-12 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p>No messages sent yet.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Compose Message Modal -->
            <div x-show="showComposeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4" @click.away="showComposeModal = false">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">New Message</h3>
                        <button @click="showComposeModal = false" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <form @submit.prevent="sendMessage">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">To (DA Officer)</label>
                                <select x-model="newMessage.receiver_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                    <option value="">Select a DA Officer...</option>
                                    @php
                                        $daOfficers = \App\Models\User::whereIn('role', ['Admin', 'DA Admin'])->get();
                                    @endphp
                                    @foreach($daOfficers as $officer)
                                        <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <input type="text" x-model="newMessage.subject" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Message subject..." required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea x-model="newMessage.content" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Write your message here..." required></textarea>
                            </div>
                            <div class="flex items-center space-x-2 bg-blue-50 p-3 rounded-lg">
                                <input type="checkbox" x-model="newMessage.send_sms" id="send-sms-compose" class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                <label for="send-sms-compose" class="flex-1">
                                    <span class="text-sm font-medium text-gray-700 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        Send as SMS
                                    </span>
                                    <p class="text-xs text-gray-500 ml-5">Also send this message via text message</p>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3 mt-6">
                            <button type="button" @click="showComposeModal = false" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Message View Modal -->
            <div x-show="showMessageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
                <div class="bg-white rounded-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" @click.away="showMessageModal = false">
                    <div class="sticky top-0 bg-white border-b border-gray-200 p-6 rounded-t-xl">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800" x-text="selectedMessage?.message?.subject"></h3>
                                <p class="text-sm text-gray-500 mt-1" x-text="selectedMessage?.message?.created_at"></p>
                            </div>
                            <button @click="showMessageModal = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Conversation Thread -->
                        <div class="space-y-4">
                            <template x-for="msg in selectedMessage?.conversation" :key="msg.id">
                                <div class="border-l-4 pl-4 py-3" :class="msg.is_mine ? 'border-green-500 bg-green-50' : 'border-blue-500 bg-blue-50'">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-800" x-text="msg.is_mine ? 'You' : msg.sender_name"></p>
                                            <p class="text-xs text-gray-500">To: <span x-text="msg.receiver_name"></span></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500" x-text="msg.created_at"></p>
                                            <template x-if="msg.sent_as_sms">
                                                <span class="inline-flex items-center text-xs text-blue-600 mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                    SMS
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 whitespace-pre-wrap" x-text="msg.content"></p>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Reply Section -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <button @click="showReplyModal = true" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                <span>Reply to this conversation</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reply Modal -->
            <div x-show="showReplyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]" x-cloak>
                <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4" @click.away="showReplyModal = false">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Reply to Message</h3>
                        <button @click="showReplyModal = false" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <form @submit.prevent="submitReply">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Reply</label>
                                <textarea x-model="replyContent" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Write your reply..." required></textarea>
                            </div>
                            <div class="flex items-center space-x-2 bg-blue-50 p-3 rounded-lg">
                                <input type="checkbox" x-model="sendSMS" id="send-sms-reply" class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                <label for="send-sms-reply" class="flex-1">
                                    <span class="text-sm font-medium text-gray-700 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        Send as SMS
                                    </span>
                                    <p class="text-xs text-gray-500 ml-5">Also send this reply via text message</p>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3 mt-6">
                            <button type="button" @click="showReplyModal = false" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Send Reply</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>

        </main>
    </div>

    <script>
        function farmerDashboard() {
            return {
                selectedLanguage: localStorage.getItem('sh_language') || 'en',
                selectedLanguageName: {
                    'en': 'English',
                    'tl': 'Tagalog',
                    'ilo': 'Ilokano'
                }[localStorage.getItem('sh_language')] || 'English',
                originalTexts: {},
                selectedMunicipality: '{{ $userMunicipality ?? "La Trinidad" }}',
                showSection: new URLSearchParams(window.location.search).get('tab') || 'dashboard',
                unreadAnnouncements: 0,
                unreadMessages: 0,
                announcements: [],
                priorityFilter: 'all',
                sortOrder: 'newest',
                
                get filteredAnnouncements() {
                    let filtered = [...this.announcements];
                    
                    // Filter by priority
                    if (this.priorityFilter !== 'all') {
                        filtered = filtered.filter(a => a.priority === this.priorityFilter);
                    }
                    
                    // Sort by date
                    filtered.sort((a, b) => {
                        const dateA = new Date(a.created_at);
                        const dateB = new Date(b.created_at);
                        return this.sortOrder === 'newest' ? dateB - dateA : dateA - dateB;
                    });
                    
                    return filtered;
                },
                
                get unreadMessages() {
                    return this.messages.received.filter(m => !m.is_read);
                },
                
                get readMessages() {
                    return this.messages.received.filter(m => m.is_read);
                },
                messages: { received: [], sent: [] },
                daOfficers: [],
                showComposeModal: false,
                newMessage: { receiver_id: '', subject: '', content: '', send_sms: false },
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
                    condition: 'Loading...',
                    rainfall: '0'
                },
                marketPrices: [],
                weatherForecast: [],
                recentHarvests: [],
                topCrops: [],
                loading: false,
                mlConnected: false,

                init() {
                    console.log('Dashboard initialized for municipality:', this.selectedMunicipality);
                    this.loadDashboardData();
                    this.loadMarketPrices();
                    this.loadAnnouncements();
                    this.loadMessages();
                    
                    // Initialize translation system
                    if (typeof SmartHarvestTranslation !== 'undefined') {
                        SmartHarvestTranslation.init();
                    }
                },

                async loadAnnouncements() {
                    try {
                        const response = await fetch(`{{ url('/api/announcements') }}`);
                        if (response.ok) {
                            this.announcements = await response.json();
                            this.unreadAnnouncements = this.announcements.filter(a => a.is_active).length;
                        }
                    } catch (error) {
                        console.error('Error loading announcements:', error);
                    }
                },

                async loadMarketPrices() {
                    try {
                        const response = await fetch(`{{ url('/api/market-prices') }}`);
                        if (response.ok) {
                            const data = await response.json();
                            this.marketPrices = data.map(p => ({
                                crop: p.crop_name,
                                variety: p.variety,
                                price: p.price_per_kg ? parseFloat(p.price_per_kg) : null,
                                previousPrice: p.previous_price ? parseFloat(p.previous_price) : null,
                                demand: p.demand_level,
                                trend: p.price_trend,
                                location: p.market_location,
                                hasPrice: p.price_per_kg !== null
                            }));
                        }
                    } catch (error) {
                        console.error('Error loading market prices:', error);
                    }
                },

                async loadMessages() {
                    try {
                        const response = await fetch(`{{ url('/api/messages') }}`);
                        if (response.ok) {
                            const data = await response.json();
                            this.messages = { received: data.received, sent: data.sent };
                            this.unreadMessages = data.unread_count;
                        }
                    } catch (error) {
                        console.error('Error loading messages:', error);
                    }
                },

                async sendMessage() {
                    if (!this.newMessage.receiver_id || !this.newMessage.subject || !this.newMessage.content) {
                        alert('Please fill all fields');
                        return;
                    }
                    try {
                        const response = await fetch(`{{ url('/api/messages') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.newMessage)
                        });
                        if (response.ok) {
                            this.showComposeModal = false;
                            this.newMessage = { receiver_id: '', subject: '', content: '', send_sms: false };
                            await this.loadMessages();
                            const result = await response.json();
                            alert(result.message || 'Message sent successfully!');
                        } else {
                            const error = await response.json();
                            alert(error.errors ? JSON.stringify(error.errors) : 'Error sending message');
                        }
                    } catch (error) {
                        console.error('Error sending message:', error);
                        alert('Error sending message');
                    }
                },
                
                async changeLanguage(code, name) {
                    this.selectedLanguage = code;
                    this.selectedLanguageName = name;
                    
                    // Use the static translation system
                    if (typeof SmartHarvestTranslation !== 'undefined') {
                        SmartHarvestTranslation.changeLanguage(code);
                    }
                },
                
                // Legacy translatePage method - kept for compatibility but now uses static translations
                async translatePage(targetLang) {
                    if (typeof SmartHarvestTranslation !== 'undefined') {
                        SmartHarvestTranslation.changeLanguage(targetLang);
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
        // Handle logout with CSRF token expiration fallback
        function handleLogout(event) {
            sessionStorage.setItem('isLoggedOut','true');
            
            // Try POST logout, fallback to GET if CSRF fails
            event.preventDefault();
            
            fetch('{{ route("logout") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            }).then(response => {
                if (response.status === 419) {
                    // CSRF token expired, use fallback GET logout
                    window.location.href = '{{ route("logout.expired") }}';
                } else {
                    window.location.href = '{{ route("login") }}';
                }
            }).catch(error => {
                // Network error or other issue, use fallback
                window.location.href = '{{ route("logout.expired") }}';
            });
            
            return false;
        }

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
