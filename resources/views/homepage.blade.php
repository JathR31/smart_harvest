<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartHarvest</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/translation-v2.js') }}?v={{ time() }}"></script>
    <style>
        .dropdown-menu {
            min-width: 160px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
    </style>
    <script>
        // Language dropdown functionality
        function toggleDropdown() {
            const menu = document.getElementById('languageDropdownMenu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        function selectLanguage(code, name) {
            console.log('Language selected:', name, code);
            
            // Update button text
            const btn = document.getElementById('languageDropdownBtn');
            if (btn) {
                btn.innerHTML = name + ' <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
            }
            
            // Close dropdown
            const menu = document.getElementById('languageDropdownMenu');
            if (menu) {
                menu.classList.add('hidden');
            }
            
            // Use SmartHarvestTranslation system
            if (typeof SmartHarvestTranslation !== 'undefined') {
                SmartHarvestTranslation.changeLanguage(code);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const btn = document.getElementById('languageDropdownBtn');
                const menu = document.getElementById('languageDropdownMenu');
                
                if (btn && menu && !btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
            
            // Initialize SmartHarvestTranslation and restore saved language
            if (typeof SmartHarvestTranslation !== 'undefined') {
                SmartHarvestTranslation.init().then(() => {
                    // Update button to reflect saved language
                    const savedLang = localStorage.getItem('sh_language') || 'en';
                    const langNames = { 'en': 'English', 'tl': 'Tagalog', 'ilo': 'Ilocano' };
                    const btn = document.getElementById('languageDropdownBtn');
                    if (btn && langNames[savedLang]) {
                        btn.innerHTML = langNames[savedLang] + ' <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                    }
                });
            }
        });
    </script>
    <style>
        /* Custom style to overlay the background image with a slight dark filter */
        .hero-background {
            background-image: url('{{ asset('images/homepagebg.jpg') }}'); /* Placeholder for your image */
            background-size: cover;
            background-position: center;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.4); 
        }
        /* Custom light green background for sections like the Weather Insights */
        .bg-custom-light {
            background-color: #f7fcf9;
        }
        /* Styling for the language dropdown to match the image (if needed) */
        .custom-dropdown-menu {
            min-width: 10rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="antialiased text-gray-700">
    <div class="hero-background relative flex flex-col h-screen"> 
        <div class="overlay absolute inset-0"></div>

        <header class="relative z-20 p-4 flex justify-between items-center bg-green-600 text-white flex-shrink-0">
            <div class="flex items-center space-x-2">
                <span class="text-2xl">🌱</span>
                <span class="text-xl font-semibold">SmartHarvest</span>
            </div>
            
            <nav class="flex items-center space-x-4">
                <div class="relative">
                    <button id="languageDropdownBtn" onclick="toggleDropdown()" class="flex items-center px-3 py-1 bg-white text-gray-700 rounded-sm border-none shadow-sm text-sm hover:bg-gray-100 transition duration-150 cursor-pointer">
                        English
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="languageDropdownMenu" class="hidden absolute right-0 mt-2 py-2 w-40 bg-white rounded-md shadow-xl dropdown-menu text-gray-800 z-50">
                        <button onclick="selectLanguage('en', 'English')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 transition">
                            🇺🇸 English
                        </button>
                        <button onclick="selectLanguage('tl', 'Tagalog')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 transition">
                            🇵🇭 Tagalog
                        </button>
                        <button onclick="selectLanguage('ilo', 'Ilocano')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 transition">
                            🇵🇭 Ilocano
                        </button>
                    </div>
                </div>

                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-sm" data-translate data-translate-id="welcome-msg">Welcome, {{ Auth::user()->name }}</span>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white text-green-600 rounded hover:bg-gray-100 transition duration-150 font-bold" data-translate data-translate-id="header-dashboard">DASHBOARD</a>
                        <div class="relative">
                            <button id="userDropdownBtn" class="w-10 h-10 bg-white text-green-600 rounded-full flex items-center justify-center font-bold hover:bg-gray-100 transition duration-150">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </button>
                            <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl text-gray-800">
                                <a href="{{ route('settings') }}" class="block px-4 py-2 hover:bg-gray-100" data-translate data-translate-id="profile-settings">Profile Settings</a>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100" data-translate data-translate-id="dropdown-dashboard">Dashboard</a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}" onsubmit="sessionStorage.setItem('isLoggedOut','true');">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600" data-translate data-translate-id="logout-btn">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 font-bold hover:text-green-100 transition duration-150" data-translate data-translate-id="login-btn">LOGIN</a>
                @endauth
            </nav>
        </header>

        <main class="relative z-10 flex flex-col items-center justify-center flex-grow text-center text-white">
            <h1 class="text-5xl md:text-6xl font-bold mb-4 drop-shadow-lg" data-translate data-translate-id="hero-title">
                Optimize Your Planting with Data
            </h1>
            <p class="max-w-xl text-lg mb-8 font-normal drop-shadow-md" data-translate data-translate-id="hero-subtitle">
                SmartHarvest uses historical yield and climate patterns to help farmers make informed planting decisions for maximum productivity.
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="px-6 py-3 text-base bg-green-500 rounded hover:bg-green-400 transition duration-300 shadow-xl font-semibold" data-translate data-translate-id="dashboard-btn">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="px-6 py-3 text-base bg-green-500 rounded hover:bg-green-400 transition duration-300 shadow-xl font-semibold" data-translate data-translate-id="getstarted-btn">
                    Get Started
                </a>
            @endauth
        </main>
    </div>

    <section class="py-16 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-3xl font-semibold mb-10 text-green-700" data-translate data-translate-id="features-title">Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Planting Schedule -->
                <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                    <div class="mx-auto mb-4 w-14 h-14 flex items-center justify-center bg-gradient-to-br from-green-100 to-green-50 rounded-full">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-green-700 mb-2" data-translate data-translate-id="feature-0-title">Planting Schedule</h3>
                    <p class="text-sm text-gray-600" data-translate data-translate-id="feature-0-desc">Optimize planting times for maximum yield</p>
                </div>
                
                <!-- Weather Insights -->
                <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                    <div class="mx-auto mb-4 w-14 h-14 flex items-center justify-center bg-gradient-to-br from-green-100 to-green-50 rounded-full">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-green-700 mb-2" data-translate data-translate-id="feature-1-title">Weather Insights</h3>
                    <p class="text-sm text-gray-600" data-translate data-translate-id="feature-1-desc">Real-time weather data and forecasts</p>
                </div>
                
                <!-- Yield Analysis -->
                <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
                    <div class="mx-auto mb-4 w-14 h-14 flex items-center justify-center bg-gradient-to-br from-green-100 to-green-50 rounded-full">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-green-700 mb-2" data-translate data-translate-id="feature-2-title">Yield Analysis</h3>
                    <p class="text-sm text-gray-600" data-translate data-translate-id="feature-2-desc">Track and analyze crop yields over time</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 px-4 bg-white">
        <div class="max-w-3xl mx-auto text-center">
            <div class="mx-auto mb-4 w-14 h-14 flex items-center justify-center bg-gradient-to-br from-green-100 to-green-50 rounded-full border-2 border-green-500">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-green-700 mb-4" data-translate data-translate-id="mission-title">Our Mission</h3>
            <p class="text-base text-gray-600 leading-relaxed" data-translate data-translate-id="mission-text">
                To empower farmers with data-driven insights and predictive analytics that optimize crop yields, reduce risks, and promote sustainable agricultural practices.
            </p>
        </div>
    </section>

    <section class="py-16 px-4 bg-gray-50">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <h2 class="text-3xl font-semibold mb-6 text-green-700" data-translate data-translate-id="about-title">About SmartHarvest</h2>
                <h3 class="text-xl font-medium text-green-700 mb-4" data-translate data-translate-id="about-subtitle">Who We Are</h3>
                <p class="mb-4 text-gray-600" data-translate data-translate-id="about-p1">
                    SmartHarvest is a dedicated web-based Decision Support System (DSS) designed to empower farmers across the municipalities of Benguet. Our core mission is to promote sustainable farming methods and drastically improve farm decision-making in the face of persistent economic challenges.
                </p>
                <p class="mb-4 text-gray-600" data-translate data-translate-id="about-p2">
                    We achieve this by utilizing gathered historical data on local crop yields and climate patterns. Through rigorous analysis, SmartHarvest identifies crucial correlations and trends between historical weather events and farm outcomes.
                </p>
                <p class="text-gray-600" data-translate data-translate-id="about-p3">
                    This process allows us to generate data-driven recommendations, providing farmers with a comprehensive report on the optimal planting times to maximize yield, guidance for better resource utilization, and actionable insights to reduce climate risks to their livelihoods and the regional economy. We're here to turn data into smarter harvests.
                </p>
            </div>

            <div class="space-y-6">
                <!-- Farmers Served -->
                <div class="p-6 bg-white rounded-xl shadow-md flex items-center space-x-4 hover:shadow-lg transition">
                    <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-green-100 to-green-50 rounded-full flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-green-700">5,000+</p>
                        <p class="text-sm text-gray-600">Farmers Served</p>
                    </div>
                </div>
                
                <!-- Average Yield Increase -->
                <div class="p-6 bg-white rounded-xl shadow-md flex items-center space-x-4 hover:shadow-lg transition">
                    <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-green-100 to-green-50 rounded-full flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-green-700">23%</p>
                        <p class="text-sm text-gray-600">Average Yield Increase</p>
                    </div>
                </div>
                
                <!-- Water Conservation -->
                <div class="p-6 bg-white rounded-xl shadow-md flex items-center space-x-4 hover:shadow-lg transition">
                    <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-green-100 to-green-50 rounded-full flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-green-700">15% Reduction</p>
                        <p class="text-sm text-gray-600">Water Conservation</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 px-4">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $values = [
                    ['title' => 'Empower Farmers', 'text' => 'Provide accessible tools and insights to help farmers make informed decisions about their crops and maximize productivity.'],
                    ['title' => 'Sustainable Agriculture', 'text' => 'Promote environmentally responsible farming practices through optimized resource utilization and climate-aware planning.'],
                    ['title' => 'Data-Driven Solutions', 'text' => 'Leverage historical data, climate patterns, and advanced analytics to predict outcomes and recommend optimal strategies.'],
                ];
            @endphp

            @foreach ($values as $idx => $value)
            <div class="p-8 bg-white rounded-xl shadow-lg border border-gray-100">
                <h3 class="text-xl font-medium text-green-700 mb-4" data-translate data-translate-id="value-{{ $idx }}-title">{{ $value['title'] }}</h3>
                <p class="text-sm text-gray-600" data-translate data-translate-id="value-{{ $idx }}-text">{{ $value['text'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-16 px-4 bg-custom-light">
        <div class="max-w-6xl mx-auto text-center">
            <div class="mx-auto mb-4 w-12 h-12 flex items-center justify-center text-green-500">
                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4c-3.72 0-6.83 2.82-7.18 6.41C2.85 10.73 2 11.96 2 13.5c0 2.21 1.79 4 4 4h13c2.76 0 5-2.24 5-5 0-2.64-2.07-4.83-4.65-4.96z"/></svg>
            </div>
                <h3 class="text-xl font-medium text-green-700 mb-1" data-translate data-translate-id="weather-title">Live Weather Insights</h3>
            <p class="mb-6 text-gray-600" data-translate data-translate-id="weather-desc">Real-time weather monitoring for informed agricultural decisions</p>

            <!-- Municipality Dropdown -->
            <div class="max-w-md mx-auto mb-10">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2 text-left" data-translate data-translate-id="municipality-label">Select Municipality</label>
                    <select id="municipalitySelect" onchange="fetchWeatherData()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white text-gray-700">
                        <option value="">-- Choose Municipality --</option>
                        <option value="Atok">Atok</option>
                        <option value="Bakun">Bakun</option>
                        <option value="Bokod">Bokod</option>
                        <option value="Buguias">Buguias</option>
                        <option value="Itogon">Itogon</option>
                        <option value="Kabayan">Kabayan</option>
                        <option value="Kapangan">Kapangan</option>
                        <option value="Kibungan">Kibungan</option>
                        <option value="La Trinidad">La Trinidad</option>
                        <option value="Mankayan">Mankayan</option>
                        <option value="Sablan">Sablan</option>
                        <option value="Tuba">Tuba</option>
                        <option value="Tublay">Tublay</option>
                    </select>
                </div>
            </div>

            <div class="space-y-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100 text-left">
                        <div class="flex justify-between items-center mb-2">
                            <svg class="w-6 h-6 fill-current text-red-500" viewBox="0 0 24 24"><path d="M15 13V5c0-1.66-1.34-3-3-3S9 3.34 9 5v8c-1.21.91-2 2.37-2 4 0 2.76 2.24 5 5 5s5-2.24 5-5c0-1.63-.79-3.09-2-4zm-4-8c0-.55.45-1 1-1s1 .45 1 1h-1v1h1v2h-1v1h1v2h-2V5z"/></svg>
                            <span class="text-xs text-red-500 font-semibold">Live</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1" data-translate data-translate-id="temp-label">Temperature</p>
                        <p class="text-xl font-semibold" id="tempValue">-- °C</p>
                    </div>

                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100 text-left">
                        <div class="flex justify-between items-center mb-2">
                            <svg class="w-6 h-6 fill-current text-blue-500" viewBox="0 0 24 24"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                            <span class="text-xs text-red-500 font-semibold">Live</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1" data-translate data-translate-id="humidity-label">Humidity</p>
                        <p class="text-xl font-semibold" id="humidityValue">-- %</p>
                    </div>

                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100 text-left">
                        <div class="flex justify-between items-center mb-2">
                            <svg class="w-6 h-6 fill-current text-sky-500" viewBox="0 0 24 24"><path d="M14.5 17c0 1.65-1.35 3-3 3s-3-1.35-3-3h2c0 .55.45 1 1 1s1-.45 1-1-.45-1-1-1H2v-2h9.5c1.65 0 3 1.35 3 3zM19 6.5C19 4.57 17.43 3 15.5 3S12 4.57 12 6.5h2c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5S16.33 8 15.5 8H2v2h13.5c1.93 0 3.5-1.57 3.5-3.5zm-.5 4.5H2v2h16.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5v2c1.93 0 3.5-1.57 3.5-3.5S20.43 11 18.5 11z"/></svg>
                            <span class="text-xs text-red-500 font-semibold">Live</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1" data-translate data-translate-id="wind-label">Wind Speed</p>
                        <p class="text-xl font-semibold" id="windSpeedValue">-- m/s</p>
                    </div>

                    <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100 text-left">
                        <div class="flex justify-between items-center mb-2">
                            <svg class="w-6 h-6 fill-current text-indigo-500" viewBox="0 0 24 24"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0L12 2.69zM12 4l-4.24 4.24c2.34 2.34 6.14 2.34 8.49 0L12 4z"/></svg>
                            <span class="text-xs text-red-500 font-semibold">Live</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1" data-translate data-translate-id="rain-label">Rainfall (24h)</p>
                        <p class="text-xl font-semibold" id="rainfallValue">-- mm</p>
                    </div>
                </div>

                <div class="max-w-xl mx-auto p-8 bg-white rounded-xl shadow-lg">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 flex items-center justify-center text-green-500 mb-2">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4c-3.72 0-6.83 2.82-7.18 6.41C2.85 10.73 2 11.96 2 13.5c0 2.21 1.79 4 4 4h13c2.76 0 5-2.24 5-5 0-2.64-2.07-4.83-4.65-4.96z"/></svg>
                        </div>
                        <h4 class="text-lg font-medium text-green-700 mb-1" data-translate data-translate-id="current-conditions">Current Conditions</h4>
                        <p class="text-2xl font-semibold mb-4" id="weatherDescription" data-translate data-translate-id="select-municipality">Select a municipality</p>
                        <p class="text-xs text-gray-500 mb-6" data-translate data-translate-id="weather-info">Weather data updates every 5 seconds. Our system integrates real-time weather information with historical patterns to provide accurate planting recommendations.</p>
                        @auth
                            <a href="{{ route('forecast') }}" class="px-8 py-3 bg-green-600 text-white rounded-full hover:bg-green-500 transition duration-300 font-semibold" data-translate data-translate-id="weather-btn">
                                View Detailed Weather Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-green-600 text-white rounded-full hover:bg-green-500 transition duration-300 font-semibold" data-translate data-translate-id="weather-btn">
                                View Detailed Weather Dashboard
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <div id="emptyState" class="text-gray-500 py-8" style="display: none;">
                <p>Please select a municipality to view weather data</p>
            </div>
        </div>

        <script>
            // Weather functionality
            async function fetchWeatherData() {
                const select = document.getElementById('municipalitySelect');
                const municipality = select.value;
                
                console.log('🌤️ Fetching weather for:', municipality);
                
                if (!municipality) {
                    console.log('No municipality selected');
                    return;
                }

                try {
                    const url = `{{ url('/api/weather') }}?municipality=${encodeURIComponent(municipality)}`;
                    console.log('📡 Fetching from:', url);
                    
                    const response = await fetch(url);
                    console.log('📥 Response status:', response.status);
                    
                    const data = await response.json();
                    console.log('📦 Full API Response:', data);
                    
                    if (data.current) {
                        const temp = data.current.temp ? Math.round(data.current.temp) : 0;
                        const humidity = data.current.humidity || 0;
                        const windSpeed = data.current.wind_speed ? data.current.wind_speed.toFixed(1) : 0;
                        const rainfall = data.current.rain || 0;
                        const description = data.current.weather && data.current.weather[0] ? 
                            data.current.weather[0].description.charAt(0).toUpperCase() + 
                            data.current.weather[0].description.slice(1) : 'Unknown';
                        
                        // Update DOM elements
                        document.getElementById('tempValue').textContent = temp + '°C';
                        document.getElementById('humidityValue').textContent = humidity + '%';
                        document.getElementById('windSpeedValue').textContent = windSpeed + ' m/s';
                        document.getElementById('rainfallValue').textContent = rainfall + ' mm';
                        document.getElementById('weatherDescription').textContent = description;
                        
                        console.log('✅ Weather data updated:', { temp, humidity, windSpeed, rainfall, description });
                    } else {
                        console.error('❌ Unexpected API response format:', data);
                    }
                } catch (error) {
                    console.error('❌ Error fetching weather data:', error);
                }
            }
        </script>
    </section>

    <section class="py-16 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-3xl font-semibold mb-2 text-green-700" data-translate data-translate-id="team-title">Meet Our Team</h2>
            <p class="mb-12 text-gray-600" data-translate data-translate-id="team-desc">Our multidisciplinary team brings together expertise in agronomy, climate science, data analytics, and agricultural engineering.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $team = [
                        ['name' => 'Mary Keirstin Marziel Ante', 'role' => 'Project Member', 'title' => 'Frontend Developer', 'desc' => null, 'img' => 'image_dc2a1b.jpg'], // Using a real image placeholder here
                        ['name' => 'Jathniel Rei Carbonell', 'role' => 'Project Member', 'title' => 'Backend Developer', 'desc' => null, 'img' => 'placeholder'],
                        ['name' => 'John Paul Soriano', 'role' => 'Project Member', 'title' => 'Backend Developer', 'desc' => null, 'img' => 'placeholder'],
                        ['name' => 'Anna Rhodora Quitaleg', 'role' => 'Project Lead', 'title' => 'Project Lead', 'desc' => '15 years developing precision farming technologies and irrigation systems', 'img' => 'placeholder'],
                    ];
                @endphp

                @foreach ($team as $member)
                <div class="p-4 bg-white rounded-xl shadow-lg border border-gray-100 text-center">
                    <div class="h-64 mb-4 overflow-hidden rounded-lg">
                        @if ($member['img'] != 'placeholder')
                            <img src="{{ asset('images/mary_ante.jpg') }}" alt="{{ $member['name'] }}" class="object-cover w-full h-full">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-8xl text-gray-400">
                                :)
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="text-lg font-medium text-gray-800">{{ $member['name'] }}</h4>
                    <p class="text-sm font-semibold text-green-600 mb-1">{{ $member['role'] }}</p>
                    <p class="text-sm text-gray-500 mb-3">{{ $member['title'] }}</p>
                    
                    @if ($member['desc'])
                        <p class="text-xs text-gray-500 mb-3">{{ $member['desc'] }}</p>
                    @endif

                    <div class="flex justify-center space-x-3 text-green-600">
                        <a href="#"><svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z"/></svg></a>
                        <a href="#"><svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.64c0-2.09-.76-3.52-2.71-3.52-1.31 0-2.07.74-2.42 1.44V9.5H10V18.5h3v-5c0-.28.02-.56.07-.76.19-.53.76-1.07 1.63-1.07.88 0 1.33.58 1.33 1.43v5.4H19.5zM7.5 7.75c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1 10.75H6.5V9.5h2V18.5z"/></svg></a>
                        <a href="#"><svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22.46 6c-.77.34-1.6.56-2.45.65.88-.53 1.56-1.37 1.88-2.36-.83.49-1.76.85-2.74 1.05C17.75 4.3 16.63 4 15.48 4c-2.35 0-4.27 1.92-4.27 4.27 0 .34.04.67.11.98C8.2 9.07 5.57 7.7 3.73 5.4c-.39.67-.61 1.44-.61 2.26 0 1.48.75 2.8 1.88 3.56-.7-.02-1.35-.21-1.92-.53v.05c0 2.08 1.48 3.82 3.44 4.2-.36.1-.73.15-1.1.15-.27 0-.53-.03-.79-.07.54 1.7 2.12 2.93 3.99 2.97C11.3 19.46 9.77 20 7.85 20c-.3 0-.6-.02-.89-.06 1.93 1.24 4.23 1.97 6.69 1.97 8.03 0 12.42-6.66 12.42-12.43 0-.19-.01-.38-.01-.56.85-.62 1.59-1.39 2.17-2.27z"/></svg></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <footer class="bg-green-800 text-white py-6 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <div class="flex items-center justify-center space-x-2 mb-3">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                <span class="text-base font-semibold">SmartHarvest</span>
            </div>
            <p class="text-sm text-green-300">
                &copy; 2025 SmartHarvest. All rights reserved. Empowering farmers through data-driven agriculture.
            </p>
            
        </div>
    </footer>
</body>
</html>