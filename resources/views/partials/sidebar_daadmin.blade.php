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
        <!-- Overview Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-green-300 mb-2 px-4">Overview</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.dacar.dashboard') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- User Management Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-green-300 mb-2 px-4">User Management</p>
            <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Users</span>
            </a>
        </div>

        <!-- Data Management Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-green-300 mb-2 px-4">Data Management</p>
            <a href="{{ route('admin.datasets') }}" class="sidebar-item {{ request()->routeIs('admin.datasets') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                <span>Datasets</span>
            </a>
            <a href="{{ route('admin.dataimport') }}" class="sidebar-item {{ request()->routeIs('admin.dataimport') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <span>Data Import</span>
            </a>
        </div>

        <!-- Market Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-green-300 mb-2 px-4">Market</p>
            <a href="{{ route('admin.price-list') }}" class="sidebar-item {{ request()->routeIs('admin.price-list') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Price List</span>
            </a>
        </div>

        <!-- Communication Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-green-300 mb-2 px-4">Communication</p>
            <a href="{{ route('admin.announcements') }}" class="sidebar-item {{ request()->routeIs('admin.announcements') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <span>Announcements</span>
            </a>
        </div>

        <!-- System Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-green-300 mb-2 px-4">System</p>
            <a href="{{ route('admin.monitoring') }}" class="sidebar-item {{ request()->routeIs('admin.monitoring') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Monitoring</span>
            </a>
        </div>

        <!-- Farmer View Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-green-300 mb-2 px-4">View As</p>
            <a href="{{ route('farmer.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>Farmer's View</span>
            </a>
        </div>

        <!-- Logout -->
        <div class="mt-8 pt-4 border-t border-green-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded w-full text-left hover:bg-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>
