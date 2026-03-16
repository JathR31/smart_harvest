<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Planting Schedule - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/translation-v2.js') }}?v={{ time() }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar { background: linear-gradient(180deg, #047857 0%, #065f46 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
    </style>
</head>
<body class="bg-gray-50 flex" x-data="plantingSchedule()">
    <!-- Sidebar -->
    <aside class="sidebar w-64 min-h-screen text-white flex-shrink-0">
        <div class="p-6">
            <div class="flex items-center space-x-2 mb-8">
                <span class="text-2xl">🌱</span>
                <span class="text-xl font-semibold">SmartHarvest</span>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Main</p>
                <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('dashboard') }}?tab=my-crops" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 21h14M5 21a2 2 0 01-2-2V7a2 2 0 012-2h4l2-2h2l2 2h4a2 2 0 012 2v12a2 2 0 01-2 2M9 14c0-1.657 1.79-3 4-3s4 1.343 4 3m-8 0v2m8-2v2"></path></svg>
                    <span>My Crops</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Analysis</p>
                <a href="{{ route('planting.schedule') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-2.5 rounded transition">
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

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Information</p>
                <a href="{{ route('dashboard') }}?tab=market-prices" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Market Prices</span>
                </a>
                <a href="{{ route('dashboard') }}?tab=announcements" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    <span>Announcements</span>
                </a>
                <a href="{{ route('dashboard') }}?tab=inbox" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Inbox</span>
                </a>
            </div>

            <div>
                <p class="text-xs uppercase text-green-300 mb-3">Account</p>
                <a href="{{ route('settings') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" onsubmit="sessionStorage.setItem('isLoggedOut','true');">
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
                    <h1 class="text-2xl font-semibold text-green-700">Planting Schedule Optimizer</h1>
                    <span x-show="mlConnected" class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        Active
                    </span>
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

        <!-- Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Top Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Next Optimal Date -->
                <div class="bg-white rounded-lg shadow p-6 relative">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">Next Optimal Date</p>
                    <p class="text-2xl font-bold text-gray-800 mb-1"><span x-text="optimal.next_date"></span></p>
                    <p class="text-xs text-green-600"><span x-text="optimal.crop + ' (' + optimal.variety + ')'"></span></p>
                </div>

                <!-- Expected Yield -->
                <div class="bg-white rounded-lg shadow p-6 relative">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">Expected Yield</p>
                    <p class="text-3xl font-bold text-gray-800 mb-1"><span x-text="optimal.expected_yield"></span> <span class="text-base font-normal text-gray-600">mt/ha</span></p>
                    <p class="text-xs text-gray-600"><span x-text="optimal.confidence"></span> confidence</p>
                </div>

                <!-- Best Performing Crop -->
                <div class="bg-white rounded-lg shadow p-6 relative">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">Top Recommendation</p>
                    <p class="text-3xl font-bold text-gray-800 mb-1"><span x-text="optimal.crop"></span></p>
                    <p class="text-xs text-gray-600" x-text="mlConnected ? 'Based on machine learning prediction' : 'Fallback to historical data'"></p>
                </div>
            </div>

            <!-- Recommended Planting Schedule -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-green-700">Recommended Planting Schedule</h2>
                    <button @click="exportSchedule()" :disabled="schedules.length === 0" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Export Schedule</span>
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Crop & Variety</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Planting Window</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Harvest Window</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Duration</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Yield Forecast</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Confidence</th>
                                <th class="pb-3 text-sm font-semibold text-gray-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <template x-for="(schedule, index) in schedules" :key="index">
                                <tr class="border-b">
                                    <td class="py-4 text-gray-800 font-medium">
                                        <div class="flex items-center">
                                            <span x-text="schedule.crop"></span>
                                            <span x-show="schedule.ml_prediction" class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-semibold">Predicted</span>
                                        </div>
                                        <div class="text-xs text-gray-500" x-text="schedule.variety"></div>
                                    </td>
                                    <td class="py-4 text-gray-800" x-text="schedule.optimal_planting"></td>
                                    <td class="py-4 text-gray-800" x-text="schedule.expected_harvest"></td>
                                    <td class="py-4 text-gray-800" x-text="schedule.duration"></td>
                                    <td class="py-4 text-gray-800">
                                        <div class="font-semibold text-green-600" x-text="schedule.yield_prediction"></div>
                                        <div class="text-xs text-gray-500" x-text="'Historical: ' + schedule.historical_yield"></div>
                                    </td>
                                    <td class="py-4">
                                        <div>
                                            <span x-show="schedule.confidence === 'High'" class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">High</span>
                                            <span x-show="schedule.confidence === 'Medium'" class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">Medium</span>
                                            <span x-show="schedule.confidence === 'Low'" class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">Low</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1" x-show="schedule.confidence_score" x-text="schedule.confidence_score + '%'"></div>
                                    </td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium" x-text="schedule.status" x-show="schedule.status === 'Recommended'"></span>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium" x-text="schedule.status" x-show="schedule.status === 'Consider'"></span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="schedules.length === 0">
                                <td colspan="7" class="py-4 text-center text-gray-500">No planting schedule data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function plantingSchedule() {
            return {
                selectedLanguage: localStorage.getItem('sh_language') || 'en',
                selectedLanguageName: {
                    'en': 'English',
                    'tl': 'Tagalog',
                    'ilo': 'Ilokano'
                }[localStorage.getItem('sh_language')] || 'English',
                originalTexts: {},
                selectedMunicipality: '{{ $userMunicipality ?? "La Trinidad" }}',
                municipalities: [
                    'Atok', 'Baguio City', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
                    'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 
                    'Sablan', 'Tuba', 'Tublay'
                ],
                optimal: {
                    crop: '',
                    variety: '',
                    next_date: '',
                    expected_yield: '0.0',
                    confidence: ''
                },
                schedules: [],
                loading: false,
                mlConnected: false,

                init() {
                    this.loadPlantingData();
                    
                    // Initialize global translation system
                    if (typeof SmartHarvestTranslation !== 'undefined') {
                        SmartHarvestTranslation.init();
                    }
                },
                
                async changeLanguage(code, name) {
                    this.selectedLanguage = code;
                    this.selectedLanguageName = name;
                    
                    // Use global translation system
                    if (typeof SmartHarvestTranslation !== 'undefined') {
                        SmartHarvestTranslation.changeLanguage(code);
                    }
                },
                
                // Legacy translatePage - now uses global system
                async translatePage(targetLang) {
                    if (typeof SmartHarvestTranslation !== 'undefined') {
                        SmartHarvestTranslation.changeLanguage(targetLang);
                    }
                },

                selectMunicipality(municipality) {
                    this.selectedMunicipality = municipality;
                    this.loadPlantingData();
                },

                async loadPlantingData() {
                    this.loading = true;
                    
                    try {
                        // Load optimal planting data
                        console.log('Loading optimal planting data for:', this.selectedMunicipality);
                        const optimalResponse = await fetch(`{{ url('/api/planting/optimal') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        console.log('Optimal API response status:', optimalResponse.status);
                        
                        if (optimalResponse.ok) {
                            const optimalData = await optimalResponse.json();
                            console.log('Optimal data received:', optimalData);
                            this.optimal = {
                                crop: optimalData.crop,
                                variety: optimalData.variety,
                                next_date: optimalData.next_date,
                                expected_yield: optimalData.expected_yield,
                                confidence: optimalData.confidence || 'High'
                            };
                            this.mlConnected = optimalData.ml_api_connected || false;
                        } else {
                            console.error('Optimal API failed with status:', optimalResponse.status);
                            const errorText = await optimalResponse.text();
                            console.error('Error response:', errorText);
                        }

                        // Load planting schedule from API
                        console.log('Loading planting schedule...');
                        const scheduleResponse = await fetch(`{{ url('/api/planting/schedule') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        console.log('Schedule API response status:', scheduleResponse.status);
                        
                        if (scheduleResponse.ok) {
                            const scheduleData = await scheduleResponse.json();
                            console.log('Schedule data received:', scheduleData);
                            this.schedules = scheduleData.map(item => ({
                                crop: item.crop,
                                variety: item.variety,
                                optimal_planting: item.optimal_planting,
                                expected_harvest: item.expected_harvest,
                                duration: item.duration,
                                yield_prediction: item.yield_prediction,
                                historical_yield: item.historical_yield,
                                confidence: item.confidence,
                                confidence_score: item.confidence_score,
                                status: item.status,
                                ml_prediction: item.ml_prediction || false
                            }));
                            console.log('✓ Planting schedules loaded:', this.schedules.length, 'crops');
                            console.log('✓ Predictions:', this.schedules.filter(s => s.ml_prediction).length);
                            console.log('✓ Database records:', this.schedules.filter(s => !s.ml_prediction).length);
                        } else {
                            console.error('Schedule API failed with status:', scheduleResponse.status);
                            const errorText = await scheduleResponse.text();
                            console.error('Error response:', errorText);
                        }
                    } catch (error) {
                        console.error('Error loading planting data:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                },

                formatMonth(month) {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    return months[month - 1] || '';
                },

                exportSchedule() {
                    if (this.schedules.length === 0) {
                        alert('No planting schedule data to export');
                        return;
                    }

                    // Create CSV content
                    let csvContent = '';
                    
                    // Add header information
                    csvContent += 'SmartHarvest - Planting Schedule Report\n';
                    csvContent += `Municipality: ${this.selectedMunicipality}\n`;
                    csvContent += `Generated: ${new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' })}\n\n`;
                    
                    // Add optimal recommendation section
                    if (this.optimal.crop) {
                        csvContent += 'TOP RECOMMENDATION\n';
                        csvContent += `Crop: ${this.optimal.crop}\n`;
                        csvContent += `Variety: ${this.optimal.variety || 'N/A'}\n`;
                        csvContent += `Next Planting Date: ${this.optimal.next_date || 'N/A'}\n`;
                        csvContent += `Expected Yield: ${this.optimal.expected_yield} mt/ha\n`;
                        csvContent += `Confidence: ${this.optimal.confidence}\n\n`;
                    }
                    
                    // Add column headers
                    csvContent += 'PLANTING SCHEDULE\n';
                    csvContent += 'Crop,Variety,Planting Window,Harvest Window,Duration,Yield Forecast,Historical Yield,Confidence,Confidence Score,Status,Source\n';
                    
                    // Add schedule data rows
                    this.schedules.forEach(schedule => {
                        const row = [
                            this.escapeCsvField(schedule.crop),
                            this.escapeCsvField(schedule.variety),
                            this.escapeCsvField(schedule.optimal_planting),
                            this.escapeCsvField(schedule.expected_harvest),
                            this.escapeCsvField(schedule.duration),
                            this.escapeCsvField(schedule.yield_prediction),
                            this.escapeCsvField(schedule.historical_yield),
                            this.escapeCsvField(schedule.confidence),
                            this.escapeCsvField(schedule.confidence_score ? schedule.confidence_score + '%' : ''),
                            this.escapeCsvField(schedule.status),
                            schedule.ml_prediction ? 'ML Prediction' : 'Database'
                        ].join(',');
                        csvContent += row + '\n';
                    });
                    
                    // Add footer
                    csvContent += '\n';
                    csvContent += '---\n';
                    csvContent += `Total Crops: ${this.schedules.length}\n`;
                    csvContent += `ML Predictions: ${this.schedules.filter(s => s.ml_prediction).length}\n`;
                    csvContent += `Database Records: ${this.schedules.filter(s => !s.ml_prediction).length}\n`;
                    csvContent += '\nGenerated by SmartHarvest - Agricultural Decision Support System\n';
                    
                    // Create blob and download
                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    
                    const fileName = `planting_schedule_${this.selectedMunicipality.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`;
                    
                    link.setAttribute('href', url);
                    link.setAttribute('download', fileName);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Show success message
                    alert(`Schedule exported successfully as ${fileName}`);
                },

                escapeCsvField(field) {
                    if (field === null || field === undefined) return '';
                    const str = String(field);
                    if (str.includes(',') || str.includes('"') || str.includes('\n')) {
                        return '"' + str.replace(/"/g, '""') + '"';
                    }
                    return str;
                }
            }
        }
    </script>

</body>
</html>
