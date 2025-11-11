<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartHarvest</title>

    <script src="https://cdn.tailwindcss.com"></script>
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
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                <span class="text-xl font-semibold">SmartHarvest</span>
            </div>
            
            <nav class="flex items-center space-x-4">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center px-3 py-1 bg-white text-gray-700 rounded-sm border-none shadow-sm text-sm hover:bg-gray-100 transition duration-150">
                        English
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 py-2 w-40 bg-white rounded-md shadow-xl custom-dropdown-menu text-gray-800">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 font-bold text-green-700">English</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Tagalog</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Ilocano</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Kankanaey</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Ibaloi</a>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="px-4 py-2 font-bold hover:text-green-100 transition duration-150">LOGIN</a>
                <a href="{{ route('admin.login') }}" class="px-4 py-2 bg-green-500 rounded hover:bg-green-400 transition duration-150 font-bold">ADMIN</a>
            </nav>
        </header>

        <main class="relative z-10 flex flex-col items-center justify-center flex-grow text-center text-white">
            <h1 class="text-5xl md:text-6xl font-bold mb-4 drop-shadow-lg">
                Optimize Your Planting with Data
            </h1>
            <p class="max-w-xl text-lg mb-8 font-normal drop-shadow-md">
                SmartHarvest uses historical yield and climate patterns to help farmers make informed planting decisions for maximum productivity.
            </p>
            <a href="{{ route('register') }}" class="px-6 py-3 text-base bg-green-500 rounded hover:bg-green-400 transition duration-300 shadow-xl font-semibold">
                Get Started
            </a>
        </main>
    </div>

    <section class="py-16 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-3xl font-semibold mb-10 text-green-700">Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $features = [
                        ['icon' => 'calendar', 'title' => 'Planting Schedule', 'description' => 'Optimize planting times for maximum yield'],
                        ['icon' => 'cloud', 'title' => 'Weather Insights', 'description' => 'Real-time weather data and forecasts'],
                        ['icon' => 'chart-line', 'title' => 'Yield Analysis', 'description' => 'Track and analyze crop yields over time'],
                    ];
                @endphp
                
                @foreach ($features as $feature)
                <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-100">
                    <div class="mx-auto mb-4 w-12 h-12 flex items-center justify-center bg-green-100 rounded-lg text-green-500">
                        <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    <h3 class="text-xl font-medium text-green-700 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-sm text-gray-500">{{ $feature['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-12 px-4 bg-white">
        <div class="max-w-3xl mx-auto text-center">
            <div class="mx-auto mb-4 w-12 h-12 flex items-center justify-center bg-green-100 rounded-full text-green-500 border border-green-500">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14v2h2v-2h-2zm-2 12h4v-2h-4v2zm0-4h4v-2h-4v2z"/></svg>
            </div>
            <h3 class="text-lg font-medium text-green-700 mb-4">Our Mission</h3>
            <p class="text-base text-gray-600">
                To empower farmers with data-driven insights and predictive analytics that optimize crop yields, reduce risks, and promote sustainable agricultural practices.
            </p>
        </div>
    </section>

    <section class="py-16 px-4 bg-gray-50">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <h2 class="text-3xl font-semibold mb-6 text-green-700">About SmartHarvest</h2>
                <h3 class="text-xl font-medium text-green-700 mb-4">Who We Are</h3>
                <p class="mb-4 text-gray-600">
                    SmartHarvest is a dedicated web-based Decision Support System (DSS) designed to empower farmers across the Cordillera Administrative Region (CAR). Our core mission is to promote sustainable farming methods and drastically improve farm decision-making in the face of persistent economic challenges.
                </p>
                <p class="mb-4 text-gray-600">
                    We achieve this by utilizing gathered historical data on local crop yields and climate patterns. Through rigorous analysis, SmartHarvest identifies crucial correlations and trends between historical weather events and farm outcomes.
                </p>
                <p class="text-gray-600">
                    This process allows us to generate data-driven recommendations, providing farmers with a comprehensive report on the optimal planting times to maximize yield, guidance for better resource utilization, and actionable insights to reduce climate risks to their livelihoods and the regional economy. We're here to turn data into smarter harvests.
                </p>
            </div>

            <div class="space-y-6">
                @php
                    $stats = [
                        ['icon' => 'users', 'value' => '5,000+', 'label' => 'Farmers Served'],
                        ['icon' => 'trend', 'value' => '23%', 'label' => 'Average Yield Increase'],
                        ['icon' => 'water-drop', 'value' => '15% Reduction', 'label' => 'Water Conservation'],
                    ];
                @endphp

                @foreach ($stats as $stat)
                <div class="p-6 bg-white rounded-xl shadow-md flex items-center space-x-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-green-100 rounded-lg text-green-500">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14v2h2v-2h-2zm-2 12h4v-2h-4v2zm0-4h4v-2h-4v2z"/></svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold">{{ $stat['value'] }}</p>
                        <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                    </div>
                </div>
                @endforeach
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

            @foreach ($values as $value)
            <div class="p-8 bg-white rounded-xl shadow-lg border border-gray-100">
                <h3 class="text-xl font-medium text-green-700 mb-4">{{ $value['title'] }}</h3>
                <p class="text-sm text-gray-600">{{ $value['text'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-16 px-4 bg-custom-light">
        <div class="max-w-6xl mx-auto text-center">
            <div class="mx-auto mb-4 w-12 h-12 flex items-center justify-center text-green-500">
                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4c-3.72 0-6.83 2.82-7.18 6.41C2.85 10.73 2 11.96 2 13.5c0 2.21 1.79 4 4 4h13c2.76 0 5-2.24 5-5 0-2.64-2.07-4.83-4.65-4.96z"/></svg>
            </div>
            <h3 class="text-xl font-medium text-green-700 mb-1">Live Weather Insights</h3>
            <p class="mb-10 text-gray-600">Real-time weather monitoring for informed agricultural decisions</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                @php
                    $metrics = [
                        ['icon' => 'thermometer', 'title' => 'Temperature', 'value' => '34°C', 'color' => 'text-red-500'],
                        ['icon' => 'humidity', 'title' => 'Humidity', 'value' => '69%', 'color' => 'text-blue-500'],
                        ['icon' => 'wind', 'title' => 'Wind Speed', 'value' => '17.4 km/h', 'color' => 'text-sky-500'],
                        ['icon' => 'rain', 'title' => 'Rainfall (24h)', 'value' => '2.9 mm', 'color' => 'text-indigo-500'],
                    ];
                @endphp

                @foreach ($metrics as $metric)
                <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100 text-left">
                    <div class="flex justify-between items-center mb-2">
                        <svg class="w-6 h-6 fill-current {{ $metric['color'] }}" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14v2h2v-2h-2zm-2 12h4v-2h-4v2zm0-4h4v-2h-4v2z"/></svg>
                        <span class="text-xs text-red-500 font-semibold">Live</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-1">{{ $metric['title'] }}</p>
                    <p class="text-xl font-semibold">{{ $metric['value'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="max-w-xl mx-auto p-8 bg-white rounded-xl shadow-lg">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 flex items-center justify-center text-green-500 mb-2">
                        <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4c-3.72 0-6.83 2.82-7.18 6.41C2.85 10.73 2 11.96 2 13.5c0 2.21 1.79 4 4 4h13c2.76 0 5-2.24 5-5 0-2.64-2.07-4.83-4.65-4.96z"/></svg>
                    </div>
                    <h4 class="text-lg font-medium text-green-700 mb-1">Current Conditions</h4>
                    <p class="text-2xl font-semibold mb-4">Partly Cloudy</p>
                    <p class="text-xs text-gray-500 mb-6">Weather data updates every 5 seconds. Our system integrates real-time weather information with historical patterns to provide accurate planting recommendations.</p>
                    <a href="{{ route('register') }}" class="px-8 py-3 bg-green-600 text-white rounded-full hover:bg-green-500 transition duration-300 font-semibold">
                        View Detailed Weather Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-3xl font-semibold mb-2 text-green-700">Meet Our Team</h2>
            <p class="mb-12 text-gray-600">Our multidisciplinary team brings together expertise in agronomy, climate science, data analytics, and agricultural engineering.</p>
            
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
            <div class="mt-3">
                <a href="{{ route('notebook') }}" class="text-xs text-green-200 hover:text-white">View Notebook (SmartHarvest.ipynb)</a>
            </div>
        </div>
    </footer>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>