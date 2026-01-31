<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item:hover { background: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background: rgba(255, 255, 255, 0.15); border-left: 3px solid white; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50" x-data="announcementsPage()" x-init="init()">
    <div class="flex h-screen overflow-hidden">
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
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Overview</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">User Management</p>
                    <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Users</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Data Management</p>
                    <a href="{{ route('admin.datasets') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                        <span>Datasets</span>
                    </a>
                    <a href="{{ route('admin.dataimport') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span>Data Import</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Market</p>
                    <a href="{{ route('admin.price-list') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Price List</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">Communication</p>
                    <a href="{{ route('admin.announcements') }}" class="sidebar-item active flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span>Announcements</span>
                    </a>
                </div>
                <div class="mb-4">
                    <p class="text-xs uppercase text-green-300 mb-2 px-4">System</p>
                    <a href="{{ route('admin.monitoring') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Monitoring</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span class="text-lg font-semibold text-gray-800">Announcements & Inbox</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">Communication</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 px-3 py-2 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="text-sm">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Tabs -->
                <div class="flex space-x-1 mb-6 bg-gray-100 p-1 rounded-lg w-fit">
                    <button @click="activeTab = 'announcements'" :class="activeTab === 'announcements' ? 'bg-white shadow-sm' : ''" class="px-4 py-2 rounded-md text-sm font-medium transition">
                        📢 Announcements
                    </button>
                    <button @click="activeTab = 'inbox'" :class="activeTab === 'inbox' ? 'bg-white shadow-sm' : ''" class="px-4 py-2 rounded-md text-sm font-medium transition relative">
                        📥 Inbox
                        <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" x-text="unreadCount"></span>
                    </button>
                    <button @click="activeTab = 'sent'" :class="activeTab === 'sent' ? 'bg-white shadow-sm' : ''" class="px-4 py-2 rounded-md text-sm font-medium transition">
                        📤 Sent
                    </button>
                </div>

                <!-- Announcements Tab -->
                <div x-show="activeTab === 'announcements'" x-cloak>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Announcements</h1>
                            <p class="text-gray-500">Create and manage announcements for farmers</p>
                        </div>
                        <button @click="showAnnouncementModal = true" class="flex items-center space-x-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>New Announcement</span>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="announcement in announcements" :key="announcement.id">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span :class="announcement.priority === 'urgent' ? 'bg-red-100 text-red-700' : announcement.priority === 'important' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700'" class="px-2 py-1 rounded-full text-xs font-medium" x-text="announcement.priority"></span>
                                            <span class="text-gray-400 text-sm" x-text="announcement.date"></span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2" x-text="announcement.title"></h3>
                                        <p class="text-gray-600" x-text="announcement.content"></p>
                                        <div class="flex items-center space-x-4 mt-4 text-sm text-gray-500">
                                            <span>👁️ <span x-text="announcement.views"></span> views</span>
                                            <span>🎯 Audience: <span x-text="announcement.audience"></span></span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Inbox Tab -->
                <div x-show="activeTab === 'inbox'" x-cloak>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Inbox</h1>
                            <p class="text-gray-500">Messages from farmers</p>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full" x-text="unreadCount + ' unread'"></span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <template x-for="message in inbox" :key="message.id">
                            <div @click="openMessage(message)" :class="message.read ? 'bg-white' : 'bg-green-50'" class="border-b border-gray-100 p-4 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-200 rounded-full flex items-center justify-center text-lg" x-text="message.from.charAt(0)"></div>
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <p class="font-medium text-gray-800" :class="!message.read && 'font-bold'" x-text="message.from"></p>
                                                <span x-show="message.municipality" class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded" x-text="message.municipality"></span>
                                                <span x-show="message.category" class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded capitalize" x-text="message.category?.replace('_', ' ')"></span>
                                            </div>
                                            <p class="text-sm text-gray-500" x-text="message.subject"></p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="text-sm text-gray-400" x-text="message.time"></span>
                                        <span x-show="!message.read" class="w-2 h-2 bg-green-500 rounded-full mt-1"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div x-show="inbox.length === 0" class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p>No messages yet</p>
                        </div>
                    </div>
                </div>

                <!-- Sent Tab -->
                <div x-show="activeTab === 'sent'" x-cloak>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Sent Messages</h1>
                            <p class="text-gray-500">Messages you've sent to farmers</p>
                        </div>
                        <button @click="showComposeModal = true" class="flex items-center space-x-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Compose Message</span>
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <template x-for="message in sentMessages" :key="message.id">
                            <div class="border-b border-gray-100 p-4 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center text-lg" x-text="message.to.charAt(0)"></div>
                                        <div>
                                            <p class="font-medium text-gray-800">To: <span x-text="message.to"></span></p>
                                            <p class="text-sm text-gray-500" x-text="message.subject"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span :class="message.status === 'read' ? 'text-green-600' : 'text-gray-400'" class="text-xs" x-text="message.status === 'read' ? '✓ Read' : 'Delivered'"></span>
                                        <span class="text-sm text-gray-400" x-text="message.time"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- New Announcement Modal -->
                <div x-show="showAnnouncementModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4" @click.away="showAnnouncementModal = false">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Create Announcement</h3>
                        <form @submit.prevent="createAnnouncement()">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                    <input type="text" x-model="newAnnouncement.title" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                    <textarea x-model="newAnnouncement.content" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                        <select x-model="newAnnouncement.priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <option value="normal">Normal</option>
                                            <option value="important">Important</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Audience</label>
                                        <select x-model="newAnnouncement.audience" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <option value="All Farmers">All Farmers</option>
                                            <option value="Benguet">Benguet Farmers</option>
                                            <option value="Baguio">Baguio Farmers</option>
                                            <option value="Ifugao">Ifugao Farmers</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" @click="showAnnouncementModal = false" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Publish</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Compose Message Modal -->
                <div x-show="showComposeModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl p-6 w-full max-w-2xl mx-4" @click.away="showComposeModal = false">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Compose Message to Farmers</h3>
                        <form @submit.prevent="sendMessage()">
                            <div class="space-y-4">
                                <!-- Recipient Type Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Send To</label>
                                    <div class="flex space-x-4 mb-3">
                                        <label class="flex items-center">
                                            <input type="radio" x-model="newMessage.recipientType" value="individual" class="mr-2 text-green-600 focus:ring-green-500">
                                            <span class="text-sm">Individual Farmer</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" x-model="newMessage.recipientType" value="municipality" class="mr-2 text-green-600 focus:ring-green-500">
                                            <span class="text-sm">By Municipality</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" x-model="newMessage.recipientType" value="all" class="mr-2 text-green-600 focus:ring-green-500">
                                            <span class="text-sm">All Farmers</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Individual Farmer Selection -->
                                <div x-show="newMessage.recipientType === 'individual'">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Farmer</label>
                                    <select x-model="newMessage.to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">Choose a farmer...</option>
                                        <template x-for="farmer in farmersList" :key="farmer.id">
                                            <option :value="farmer.name" x-text="farmer.name + ' (' + farmer.municipality + ')'"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Municipality Selection -->
                                <div x-show="newMessage.recipientType === 'municipality'">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Municipality</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <template x-for="mun in municipalities" :key="mun">
                                            <label class="flex items-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50" :class="newMessage.municipalities.includes(mun) ? 'border-green-500 bg-green-50' : 'border-gray-200'">
                                                <input type="checkbox" :value="mun" x-model="newMessage.municipalities" class="mr-2 text-green-600 focus:ring-green-500">
                                                <span class="text-sm" x-text="mun"></span>
                                            </label>
                                        </template>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Selected: <span x-text="newMessage.municipalities.length"></span> municipality(ies)</p>
                                </div>

                                <!-- All Farmers Info -->
                                <div x-show="newMessage.recipientType === 'all'" class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <p class="text-sm text-yellow-800">⚠️ This message will be sent to <strong>all registered farmers</strong> in the system.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                    <input type="text" x-model="newMessage.subject" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter subject..." required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                    <textarea x-model="newMessage.content" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Type your message here..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                    <select x-model="newMessage.priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="normal">Normal</option>
                                        <option value="important">Important</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" @click="showComposeModal = false" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    <span>Send Message</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function announcementsPage() {
            return {
                activeTab: 'announcements',
                showAnnouncementModal: false,
                showComposeModal: false,
                unreadCount: 3,
                newAnnouncement: { title: '', content: '', priority: 'normal', audience: 'All Farmers' },
                newMessage: { recipientType: 'individual', to: '', municipalities: [], subject: '', content: '', priority: 'normal' },
                municipalities: [
                    'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon',
                    'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad',
                    'Mankayan', 'Sablan', 'Tuba', 'Tublay'
                ],
                farmersList: [
                    { id: 1, name: 'Juan Dela Cruz', municipality: 'La Trinidad' },
                    { id: 2, name: 'Maria Santos', municipality: 'Atok' },
                    { id: 3, name: 'Pedro Garcia', municipality: 'Buguias' },
                    { id: 4, name: 'Ana Reyes', municipality: 'Kabayan' },
                    { id: 5, name: 'Jose Mendoza', municipality: 'La Trinidad' },
                    { id: 6, name: 'Rosa Fernandez', municipality: 'Bokod' },
                    { id: 7, name: 'Carlos Bautista', municipality: 'Tublay' }
                ],

                announcements: [
                    { id: 1, title: 'New Planting Season Guidelines', content: 'Please follow the new guidelines for the upcoming planting season. Ensure proper soil preparation and seed treatment before planting.', priority: 'important', date: 'Jan 28, 2026', views: 156, audience: 'All Farmers' },
                    { id: 2, title: 'Weather Advisory: Cold Front Expected', content: 'A cold front is expected to arrive next week. Please prepare your crops accordingly and consider protective measures for sensitive plants.', priority: 'urgent', date: 'Jan 27, 2026', views: 243, audience: 'Benguet' },
                    { id: 3, title: 'Free Fertilizer Distribution Schedule', content: 'Free fertilizer will be distributed at La Trinidad Trading Post on February 1-3. Bring your farm ID and land documents.', priority: 'normal', date: 'Jan 25, 2026', views: 89, audience: 'All Farmers' }
                ],

                inbox: [
                    { id: 1, from: 'Juan Dela Cruz', subject: 'Question about crop rotation', time: '2 hours ago', read: false },
                    { id: 2, from: 'Maria Santos', subject: 'Request for farm visit', time: '5 hours ago', read: false },
                    { id: 3, from: 'Pedro Garcia', subject: 'Yield report submission', time: 'Yesterday', read: false },
                    { id: 4, from: 'Ana Reyes', subject: 'Thank you for the assistance', time: '2 days ago', read: true },
                    { id: 5, from: 'Jose Mendoza', subject: 'Pest problem inquiry', time: '3 days ago', read: true }
                ],

                sentMessages: [
                    { id: 1, to: 'Juan Dela Cruz', subject: 'Re: Question about crop rotation', time: 'Today', status: 'read' },
                    { id: 2, to: 'Maria Santos', subject: 'Farm visit confirmed', time: 'Yesterday', status: 'delivered' },
                    { id: 3, to: 'All Farmers', subject: 'Monthly newsletter', time: 'Jan 25', status: 'read' }
                ],

                init() {
                    // Load farmer messages from localStorage
                    this.loadFarmerMessages();
                },
                
                loadFarmerMessages() {
                    const farmerMessages = JSON.parse(localStorage.getItem('smartharvest_da_inbox') || '[]');
                    if (farmerMessages.length > 0) {
                        // Merge with existing inbox, avoiding duplicates
                        const existingIds = this.inbox.map(m => m.id);
                        farmerMessages.forEach(msg => {
                            if (!existingIds.includes(msg.id)) {
                                this.inbox.unshift(msg);
                            }
                        });
                        this.unreadCount = this.inbox.filter(m => !m.read).length;
                    }
                },

                createAnnouncement() {
                    const newId = Math.max(...this.announcements.map(a => a.id)) + 1;
                    this.announcements.unshift({
                        id: newId,
                        title: this.newAnnouncement.title,
                        content: this.newAnnouncement.content,
                        priority: this.newAnnouncement.priority,
                        date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                        views: 0,
                        audience: this.newAnnouncement.audience
                    });
                    this.newAnnouncement = { title: '', content: '', priority: 'normal', audience: 'All Farmers' };
                    this.showAnnouncementModal = false;
                },

                openMessage(message) {
                    if (!message.read) {
                        message.read = true;
                        this.unreadCount--;
                    }
                    // Open message detail
                },

                sendMessage() {
                    const newId = Math.max(...this.sentMessages.map(m => m.id)) + 1;
                    let recipient = '';
                    
                    if (this.newMessage.recipientType === 'individual') {
                        recipient = this.newMessage.to;
                    } else if (this.newMessage.recipientType === 'municipality') {
                        recipient = 'Farmers in: ' + this.newMessage.municipalities.join(', ');
                    } else {
                        recipient = 'All Farmers';
                    }
                    
                    this.sentMessages.unshift({
                        id: newId,
                        to: recipient,
                        subject: this.newMessage.subject,
                        time: 'Just now',
                        status: 'delivered',
                        priority: this.newMessage.priority
                    });
                    
                    // Save to localStorage for farmers to receive
                    this.saveMessageForFarmers(recipient, this.newMessage.subject, this.newMessage.content, this.newMessage.priority);
                    
                    this.newMessage = { recipientType: 'individual', to: '', municipalities: [], subject: '', content: '', priority: 'normal' };
                    this.showComposeModal = false;
                    
                    // Show success notification
                    alert('Message sent successfully to ' + recipient);
                },
                
                saveMessageForFarmers(to, subject, content, priority) {
                    // Get existing messages from localStorage
                    let farmerMessages = JSON.parse(localStorage.getItem('smartharvest_farmer_messages') || '[]');
                    
                    farmerMessages.unshift({
                        id: Date.now(),
                        from: 'DA-CAR Office',
                        to: to,
                        subject: subject,
                        preview: content.substring(0, 100) + '...',
                        content: content,
                        priority: priority,
                        time: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                        read: false
                    });
                    
                    localStorage.setItem('smartharvest_farmer_messages', JSON.stringify(farmerMessages));
                }
            };
        }
    </script>
</body>
</html>
