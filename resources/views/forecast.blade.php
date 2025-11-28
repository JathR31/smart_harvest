<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Weather Forecast - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar { background: linear-gradient(180deg, #047857 0%, #065f46 100%); }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 4px solid #fff; }
    </style>
</head>
<body class="bg-gray-50 flex" x-data="weatherDashboard()">
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
                <a href="{{ route('yield.analysis') }}" class="sidebar-item flex items-center space-x-3 px-4 py-2.5 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Yield Analysis</span>
                </a>
            </div>

            <div class="mb-8">
                <p class="text-xs uppercase text-green-300 mb-3">Weather</p>
                <a href="{{ route('forecast') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-2.5 rounded transition">
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
                <h1 class="text-2xl font-semibold text-green-700">Weather Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <!-- Municipality Dropdown -->
                    <div class="relative">
                        <button @click="municipalityOpen = !municipalityOpen" class="flex items-center space-x-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-medium" x-text="selectedMunicipality"></span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="municipalityOpen" @click.away="municipalityOpen = false" class="absolute top-full right-0 mt-2 w-56 bg-white border rounded-lg shadow-lg z-10 max-h-96 overflow-y-auto" style="display: none;">
                            <template x-for="municipality in municipalities" :key="municipality">
                                <button @click="changeMunicipality(municipality)" class="block w-full text-left px-4 py-2 hover:bg-gray-50" x-text="municipality"></button>
                            </template>
                        </div>
                    </div>
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
                            <button @click="changeLanguage('kan', 'Kankanaey'); open = false" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm" :class="{'bg-green-50 text-green-700': selectedLanguage === 'kan'}">Kankanaey</button>
                            <button @click="changeLanguage('ibl', 'Ibaloi'); open = false" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm" :class="{'bg-green-50 text-green-700': selectedLanguage === 'ibl'}">Ibaloi</button>
                        </div>
                    </div>
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Loading State -->
            <div x-show="loading" class="text-center py-20">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-green-500 border-t-transparent"></div>
                <p class="mt-4 text-gray-600">Loading weather data...</p>
            </div>

            <!-- Error State -->
            <div x-show="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center" style="display: none;">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-red-800 font-semibold mb-2">Unable to load weather data</p>
                <p class="text-red-600 text-sm" x-text="errorMessage"></p>
            </div>

            <!-- Weather Content -->
            <div x-show="!loading && !error" style="display: none;">
                <!-- Current Conditions -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-green-700 mb-4">Current Conditions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current Temperature -->
                        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                            <p class="text-sm mb-2 opacity-90">Current Temperature</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-5xl font-bold" x-text="current.temp + '°C'"></p>
                                    <p class="text-sm mt-2 opacity-90">Feels Like <span x-text="current.feels_like + '°C'"></span></p>
                                    <p class="text-sm opacity-90">Humidity <span x-text="current.humidity + '%'"></span></p>
                                </div>
                                <div class="text-right">
                                    <img :src="'https://openweathermap.org/img/wn/' + current.icon + '@2x.png'" class="w-20 h-20" alt="Weather icon">
                                    <p class="text-sm capitalize" x-text="current.description"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Rainfall Today -->
                        <div class="bg-gradient-to-br from-gray-500 to-gray-700 rounded-lg shadow-lg p-6 text-white">
                            <p class="text-sm mb-2 opacity-90">Rainfall Today</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-5xl font-bold" x-text="current.rain + 'mm'"></p>
                                    <p class="text-sm mt-2 opacity-90">Precipitation <span x-text="current.precipitation + '%'"></span></p>
                                    <p class="text-sm opacity-90">Cloud Cover <span x-text="current.clouds + '%'"></span></p>
                                </div>
                                <div>
                                    <svg class="w-20 h-20 opacity-90" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- 5-Day Temperature Forecast -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-green-700">5-Day Temperature Forecast</h2>
                        </div>
                        <div class="h-64">
                            <canvas id="temperatureForecastChart"></canvas>
                        </div>
                        <!-- Temperature Interpretation -->
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-700 mb-2">Temperature Forecast Analysis</p>
                                    <div x-show="tempInterpretation.loading" class="text-xs text-gray-500 italic">Analyzing temperature patterns...</div>
                                    <div x-show="!tempInterpretation.loading && tempInterpretation.text" 
                                         class="text-xs text-gray-700 space-y-1"
                                         x-html="tempInterpretation.text.replace(/•/g, '<br>•')"></div>
                                    <p x-show="!tempInterpretation.loading && tempInterpretation.error" 
                                       class="text-xs text-red-600" 
                                       x-text="tempInterpretation.error"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Rainfall Prediction -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-green-700 mb-2">Monthly Rainfall Prediction</h2>
                        <p class="text-sm text-gray-600 mb-6">Next 30 days</p>
                        <div class="h-64">
                            <canvas id="rainfallPredictionChart"></canvas>
                        </div>
                        <!-- Rainfall Interpretation -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-700 mb-2">Rainfall Forecast Analysis</p>
                                    <div x-show="rainfallInterpretation.loading" class="text-xs text-gray-500 italic">Analyzing rainfall patterns...</div>
                                    <div x-show="!rainfallInterpretation.loading && rainfallInterpretation.text" 
                                         class="text-xs text-gray-700 leading-relaxed"
                                         x-html="rainfallInterpretation.text.replace(/\\n/g, '<br>')"></div>
                                    <div x-show="!rainfallInterpretation.loading && !rainfallInterpretation.text && !rainfallInterpretation.error"
                                         class="text-xs text-gray-500 italic">Loading interpretation...</div>
                                    <p x-show="!rainfallInterpretation.loading && rainfallInterpretation.error" 
                                       class="text-xs text-red-600" 
                                       x-text="rainfallInterpretation.error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hourly Forecast -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-green-700 mb-6">Hourly Forecast</h2>
                    <div class="flex space-x-6 overflow-x-auto pb-4">
                        <template x-for="(hour, index) in hourlyForecast" :key="index">
                            <div class="flex-shrink-0 text-center">
                                <p class="text-sm text-gray-600 mb-2" x-text="hour.time"></p>
                                <img :src="'https://openweathermap.org/img/wn/' + hour.icon + '.png'" class="w-12 h-12 mx-auto" alt="Weather icon">
                                <p class="text-lg font-semibold" x-text="hour.temp + '°'"></p>
                            </div>
                        </template>
                    </div>
                    <!-- Hourly Interpretation -->
                    <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Hourly Forecast Analysis</p>
                                <div x-show="hourlyInterpretation.loading" class="text-xs text-gray-500 italic">Analyzing hourly patterns...</div>
                                <div x-show="!hourlyInterpretation.loading && hourlyInterpretation.text" 
                                     class="text-xs text-gray-700 space-y-1"
                                     x-html="hourlyInterpretation.text.replace(/•/g, '<br>•')"></div>
                                <p x-show="!hourlyInterpretation.loading && hourlyInterpretation.error" 
                                   class="text-xs text-red-600" 
                                   x-text="hourlyInterpretation.error"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function weatherDashboard() {
            return {
                selectedLanguage: localStorage.getItem('preferredLanguage') || 'en',
                selectedLanguageName: localStorage.getItem('preferredLanguageName') || 'English',
                originalTexts: {},
                loading: true,
                error: false,
                errorMessage: '',
                municipalityOpen: false,
                selectedMunicipality: '{{ $userMunicipality ?? "Atok" }}',
                municipalities: [
                    'La Trinidad',
                    'Itogon',
                    'Sablan',
                    'Tuba',
                    'Tublay',
                    'Atok',
                    'Bakun',
                    'Bokod',
                    'Buguias',
                    'Kabayan',
                    'Kapangan',
                    'Kibungan',
                    'Mankayan'
                ],
                current: {
                    temp: 0,
                    feels_like: 0,
                    humidity: 0,
                    icon: '01d',
                    description: '',
                    rain: 0,
                    precipitation: 0,
                    clouds: 0
                },
                soilMoisture: {
                    level: 'Medium',
                    lastWatered: '2 days',
                    nextWater: 'Soon'
                },
                hourlyForecast: [],
                dailyForecast: [],
                rainfallWeeklyData: [40, 75, 55, 45],
                temperatureChart: null,
                rainfallChart: null,
                tempInterpretation: { text: '', loading: false, error: '' },
                rainfallInterpretation: { text: '', loading: false, error: '' },
                hourlyInterpretation: { text: '', loading: false, error: '' },

                async init() {
                    await this.fetchWeatherData();
                    await this.fetchRainfallSoilData();
                    await this.fetchRainfallData();
                    if (this.selectedLanguage !== 'en') {
                        setTimeout(() => this.translatePage(this.selectedLanguage), 1000);
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

                async fetchWeatherData() {
                    this.loading = true;
                    this.error = false;
                    
                    try {
                        const response = await fetch(`{{ url('/api/weather') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        const data = await response.json();
                        
                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to fetch weather data');
                        }

                        // Update current weather (but don't override rain/precipitation/clouds yet)
                        this.current = {
                            temp: Math.round(data.current.temp),
                            feels_like: Math.round(data.current.feels_like),
                            humidity: data.current.humidity,
                            icon: data.current.weather[0].icon,
                            description: data.current.weather[0].description,
                            rain: data.current.rain || 0,
                            precipitation: data.current.pop ? Math.round(data.current.pop * 100) : 70,
                            clouds: data.current.clouds
                        };

                        // Update hourly forecast (next 8 hours)
                        this.hourlyForecast = data.hourly.slice(0, 8).map((hour, index) => ({
                            time: index === 0 ? 'Now' : new Date(hour.dt * 1000).toLocaleTimeString('en-US', { hour: 'numeric', hour12: true }),
                            icon: hour.weather[0].icon,
                            temp: Math.round(hour.temp)
                        }));

                        // Update daily forecast
                        this.dailyForecast = data.daily.slice(0, 5);

                        // Update charts
                        this.updateCharts();
                        
                        // Load interpretations
                        this.loadTemperatureInterpretation();
                        this.loadHourlyInterpretation();
                        // Rainfall interpretation will be loaded after fetchRainfallData completes

                        this.loading = false;
                    } catch (err) {
                        console.error('Weather fetch error:', err);
                        this.error = true;
                        this.errorMessage = err.message;
                        this.loading = false;
                    }
                },

                async fetchRainfallData() {
                    try {
                        const response = await fetch(`{{ url('/api/climate/weekly-rainfall') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        if (response.ok) {
                            const data = await response.json();
                            if (data.weekly && data.weekly.length === 4) {
                                this.rainfallWeeklyData = data.weekly;
                                this.updateCharts();
                                // Reload interpretation with new data
                                await this.loadRainfallInterpretation();
                            }
                        }
                    } catch (error) {
                        console.error('Error fetching rainfall data:', error);
                    }
                },

                async fetchRainfallSoilData() {
                    try {
                        console.log('Fetching rainfall and soil moisture data for:', this.selectedMunicipality);
                        const response = await fetch(`{{ url('/api/climate/rainfall-soil') }}?municipality=${encodeURIComponent(this.selectedMunicipality)}`);
                        const data = await response.json();
                        
                        if (response.ok && data.rainfall) {
                            // Update rainfall data from database
                            this.current.rain = data.rainfall.today;
                            this.current.precipitation = data.rainfall.precipitation;
                            this.current.clouds = data.rainfall.clouds;
                            
                            // Update soil moisture from database
                            this.soilMoisture = {
                                level: data.soilMoisture.level,
                                lastWatered: data.soilMoisture.lastWatered,
                                nextWater: data.soilMoisture.nextWater
                            };
                            
                            console.log('✓ Rainfall/Soil data loaded from', data.source);
                            console.log('  Rainfall today:', data.rainfall.today + 'mm');
                            console.log('  Soil moisture:', data.soilMoisture.level);
                        }
                    } catch (err) {
                        console.error('Rainfall/Soil fetch error:', err);
                        // Keep default values if fetch fails
                    }
                },

                async changeMunicipality(municipality) {
                    this.selectedMunicipality = municipality;
                    this.municipalityOpen = false;
                    await this.fetchWeatherData();
                    await this.fetchRainfallSoilData();
                    await this.fetchRainfallData();
                },

                async loadTemperatureInterpretation() {
                    this.tempInterpretation = { text: '', loading: true, error: '' };
                    try {
                        const response = await fetch('{{ url("/api/weather/interpretation/temperature") }}?municipality=' + encodeURIComponent(this.selectedMunicipality));
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            this.tempInterpretation = { text: data.interpretation, loading: false, error: '' };
                        } else {
                            // Provide fallback interpretation
                            const highs = this.dailyForecast.map(d => Math.round(d.temp.max));
                            const lows = this.dailyForecast.map(d => Math.round(d.temp.min));
                            const maxTemp = Math.max(...highs);
                            const minTemp = Math.min(...lows);
                            const avgTemp = Math.round((maxTemp + minTemp) / 2);
                            
                            const fallback = `• Temperature ranges from ${minTemp}°C to ${maxTemp}°C across the week\n• Average temperature is ${avgTemp}°C, suitable for most highland crops\n• Maintain consistent irrigation during warmer days above 18°C`;
                            this.tempInterpretation = { text: fallback, loading: false, error: '' };
                        }
                    } catch (error) {
                        console.error('Error loading temperature interpretation:', error);
                        // Provide fallback interpretation on error
                        const fallback = '• Temperature data is being analyzed for farming recommendations\n• Monitor daily weather for optimal planting conditions\n• Cool season crops perform best in current conditions';
                        this.tempInterpretation = { text: fallback, loading: false, error: '' };
                    }
                },

                async loadRainfallInterpretation() {
                    // Set loading state
                    this.rainfallInterpretation = { text: '', loading: true, error: '' };
                    
                    // Generate fallback from current data immediately
                    const maxRainfall = Math.max(...this.rainfallWeeklyData);
                    const minRainfall = Math.min(...this.rainfallWeeklyData);
                    const totalRainfall = this.rainfallWeeklyData.reduce((a, b) => a + b, 0);
                    const maxWeek = this.rainfallWeeklyData.indexOf(maxRainfall) + 1;
                    const minWeek = this.rainfallWeeklyData.indexOf(minRainfall) + 1;
                    
                    const fallbackText = `• Week ${maxWeek} shows highest rainfall at ${maxRainfall}mm, ideal for crop growth\n• Total monthly rainfall of ${totalRainfall}mm is ${totalRainfall > 200 ? 'adequate' : 'moderate'} for highland vegetables\n• Plan irrigation for Week ${minWeek} with lower rainfall at ${minRainfall}mm`;
                    
                    try {
                        // Prepare rainfall data in the same format as the chart
                        const rainfallData = this.rainfallWeeklyData.map((rainfall, index) => ({
                            week: `Week ${index + 1}`,
                            rainfall: rainfall
                        }));
                        
                        // Send actual chart data to API
                        const response = await fetch('{{ url("/api/weather/interpretation/rainfall") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({
                                municipality: this.selectedMunicipality,
                                rainfallData: rainfallData
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.status === 'success' && data.interpretation && data.interpretation.trim()) {
                            this.rainfallInterpretation = { text: data.interpretation, loading: false, error: '' };
                        } else {
                            // Use calculated fallback
                            this.rainfallInterpretation = { text: fallbackText, loading: false, error: '' };
                        }
                    } catch (error) {
                        console.error('Error loading rainfall interpretation:', error);
                        // Use calculated fallback on error
                        this.rainfallInterpretation = { text: fallbackText, loading: false, error: '' };
                    }
                },

                async loadHourlyInterpretation() {
                    this.hourlyInterpretation = { text: '', loading: true, error: '' };
                    try {
                        const response = await fetch('{{ url("/api/weather/interpretation/hourly") }}?municipality=' + encodeURIComponent(this.selectedMunicipality));
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            this.hourlyInterpretation = { text: data.interpretation, loading: false, error: '' };
                        } else {
                            // Provide fallback interpretation
                            const temps = this.hourlyForecast.map(h => h.temp);
                            const maxTemp = Math.max(...temps);
                            const minTemp = Math.min(...temps);
                            const avgTemp = Math.round(temps.reduce((a, b) => a + b, 0) / temps.length);
                            
                            const fallback = `• Temperature varies from ${minTemp}°C to ${maxTemp}°C in next 8 hours\n• Average hourly temperature is ${avgTemp}°C, ideal for outdoor farm activities\n• Best time for fieldwork is during moderate temperature hours`;
                            this.hourlyInterpretation = { text: fallback, loading: false, error: '' };
                        }
                    } catch (error) {
                        console.error('Error loading hourly interpretation:', error);
                        // Provide fallback interpretation on error
                        const fallback = '• Hourly conditions are stable for farming activities\n• Monitor temperature changes for optimal work scheduling\n• Plan tasks during cooler hours for better efficiency';
                        this.hourlyInterpretation = { text: fallback, loading: false, error: '' };
                    }
                },

                updateCharts() {
                    this.$nextTick(() => {
                        this.createTemperatureChart();
                        this.createRainfallChart();
                    });
                },

                createTemperatureChart() {
                    const ctx = document.getElementById('temperatureForecastChart');
                    if (!ctx) return;

                    if (this.temperatureChart) {
                        this.temperatureChart.destroy();
                    }

                    // Generate day labels based on actual data received
                    const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                    const days = this.dailyForecast.slice(0, 5).map((day, index) => dayNames[index] || 'Day ' + (index + 1));
                    const highs = this.dailyForecast.slice(0, 5).map(day => Math.round(day.temp.max));
                    const lows = this.dailyForecast.slice(0, 5).map(day => Math.round(day.temp.min));

                    this.temperatureChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: days,
                            datasets: [{
                                label: 'High',
                                data: highs,
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderWidth: 2,
                                pointRadius: 4,
                                pointBackgroundColor: '#ef4444',
                                tension: 0.4
                            }, {
                                label: 'Low',
                                data: lows,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
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
                                    display: true,
                                    position: 'bottom'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false
                                }
                            }
                        }
                    });
                },

                createRainfallChart() {
                    const ctx = document.getElementById('rainfallPredictionChart');
                    if (!ctx) return;

                    if (this.rainfallChart) {
                        this.rainfallChart.destroy();
                    }

                    // Use dynamic rainfall data for the selected municipality
                    const rainfallData = this.rainfallWeeklyData;

                    this.rainfallChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                            datasets: [{
                                data: rainfallData,
                                backgroundColor: '#3b82f6',
                                borderRadius: 4
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
                                    max: 100,
                                    ticks: {
                                        callback: function(value) {
                                            return value;
                                        }
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
