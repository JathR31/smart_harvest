<!-- SmartHarvest Farmer Chatbot (keyword-based, no external AI API) -->
<div x-data="farmChatbot()" x-init="init()" x-cloak style="position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 60;">
    <button @click="toggle()" class="bg-green-600 hover:bg-green-700 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg transition">
        <svg x-show="!open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <div x-show="open" x-cloak x-transition
         style="position: absolute; bottom: 4.5rem; right: 0; width: 20rem; max-width: calc(100vw - 2rem);"
         class="bg-white rounded-xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden">
        <div class="bg-green-600 text-white px-4 py-3 flex items-center justify-between">
            <span class="font-semibold">SmartHarvest Assistant</span>
            <button @click="toggle()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div x-ref="chatMessages" class="flex-1 overflow-y-auto px-3 py-3 space-y-2" style="height: 20rem;">
            <template x-for="(message, index) in messages" :key="index">
                <div :class="message.from === 'user' ? 'text-right' : 'text-left'">
                    <span
                        :class="message.from === 'user' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-800'"
                        class="inline-block px-3 py-2 rounded-lg text-sm max-w-[85%] text-left"
                        x-text="message.text"
                    ></span>
                </div>
            </template>
            <div x-show="sending" class="text-left">
                <span class="inline-block px-3 py-2 rounded-lg text-sm bg-gray-100 text-gray-400">...</span>
            </div>
        </div>

        <form @submit.prevent="send()" class="border-t border-gray-200 p-2 flex gap-2">
            <input type="text" x-model="input" placeholder="Ask a question..."
                   class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <button type="submit" :disabled="!input.trim()" class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 disabled:opacity-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
    </div>
</div>
<script src="{{ asset('js/chatbot.js') }}?v={{ filemtime(public_path('js/chatbot.js')) }}"></script>
