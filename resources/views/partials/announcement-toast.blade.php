<!-- SmartHarvest Announcement Toast: floating real-time notification (polls every 4s) -->
<div x-data="announcementToast()" x-init="init()" x-cloak
     style="position: fixed; top: 1rem; left: 50%; transform: translateX(-50%); z-index: 80; width: 100%; max-width: 26rem; padding: 0 1rem;">
    <template x-for="toast in toasts" :key="toast.toastId">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            :class="priorityClasses(toast.priority)"
            class="border-l-4 rounded-lg shadow-lg p-4 mb-2 cursor-pointer"
            @click="viewAnnouncement()"
        >
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-2 min-w-0">
                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="toast.title"></p>
                        <p class="text-xs text-gray-600 mt-0.5 line-clamp-2" x-text="toast.content"></p>
                    </div>
                </div>
                <button type="button" @click.stop="dismiss(toast.toastId)" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </template>
</div>
<script src="{{ asset('js/announcement-toast.js') }}?v={{ filemtime(public_path('js/announcement-toast.js')) }}"></script>
