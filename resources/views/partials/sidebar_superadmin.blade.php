<!-- Sidebar - Purple theme for Superadmin -->
<aside class="w-64 bg-gradient-to-b from-purple-800 to-purple-900 text-white flex-shrink-0 overflow-y-auto">
    <div class="p-6 border-b border-purple-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                <span class="text-xl">👑</span>
            </div>
            <div>
                <h1 class="text-lg font-bold">SmartHarvest</h1>
                <p class="text-xs text-purple-200">Superadmin</p>
            </div>
        </div>
    </div>

    <nav class="p-4 space-y-1">
        <!-- Overview Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-purple-300 mb-2 px-4">Overview</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('superadmin.dashboard') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- User Management Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-purple-300 mb-2 px-4">User Management</p>
            <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.roles') }}" class="sidebar-item {{ request()->routeIs('admin.roles') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span>Roles & Permissions</span>
            </a>
        </div>

        <!-- System Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-purple-300 mb-2 px-4">System</p>
            <a href="{{ route('admin.monitoring') }}" class="sidebar-item {{ request()->routeIs('admin.monitoring') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>System Monitoring</span>
            </a>
            <a href="{{ route('admin.logs') }}" class="sidebar-item {{ request()->routeIs('admin.logs') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Activity Logs</span>
            </a>
        </div>

        <!-- View As Section -->
        <div class="mb-4">
            <p class="text-xs uppercase text-purple-300 mb-2 px-4">View As</p>
            <a href="{{ route('admin.dacar.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>DA Admin View</span>
            </a>
            <a href="{{ route('farmer.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Farmer's View</span>
            </a>
        </div>

        <!-- Logout -->
        <div class="mt-8 pt-4 border-t border-purple-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded w-full text-left hover:bg-purple-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>
