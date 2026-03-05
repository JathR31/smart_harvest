{{-- 
    Global Language Selector Component (Sidebar Style)
    Usage: @include('partials.language-selector-sidebar')
    
    This component provides a dropdown for selecting language in sidebar
    Styled for dark/green sidebar backgrounds
    Works with translation-v2.js and stores preference in localStorage
--}}
<div class="mt-auto pt-4 border-t border-green-600">
    <p class="text-xs uppercase text-green-300 mb-3" data-translate data-translate-id="menu-language">Language</p>
    <div class="relative" x-data="{ langOpen: false }" @click.outside="langOpen = false">
        <button 
            @click="langOpen = !langOpen" 
            class="flex items-center justify-between w-full px-4 py-2.5 rounded transition text-white hover:bg-white/10"
            id="sidebarLangBtn"
        >
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
                <span id="sidebarLangText">English</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': langOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div 
            x-show="langOpen" 
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute bottom-full left-0 mb-2 py-2 w-full bg-white rounded-lg shadow-xl z-50"
        >
            <button 
                @click="window.SmartHarvestTranslation.changeLanguage('en'); langOpen = false" 
                class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm text-gray-700 transition"
                :class="{ 'bg-green-50 text-green-700 font-medium': localStorage.getItem('sh_language') === 'en' || !localStorage.getItem('sh_language') }"
            >
                <span class="mr-2">🇺🇸</span> English
            </button>
            <button 
                @click="window.SmartHarvestTranslation.changeLanguage('tl'); langOpen = false" 
                class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm text-gray-700 transition"
                :class="{ 'bg-green-50 text-green-700 font-medium': localStorage.getItem('sh_language') === 'tl' }"
            >
                <span class="mr-2">🇵🇭</span> Tagalog
            </button>
            <button 
                @click="window.SmartHarvestTranslation.changeLanguage('ilo'); langOpen = false" 
                class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm text-gray-700 transition"
                :class="{ 'bg-green-50 text-green-700 font-medium': localStorage.getItem('sh_language') === 'ilo' }"
            >
                <span class="mr-2">🇵🇭</span> Ilokano
            </button>
        </div>
    </div>
</div>

<script>
// Update sidebar button text when language changes
document.addEventListener('DOMContentLoaded', function() {
    function updateSidebarLangBtn() {
        const langText = document.getElementById('sidebarLangText');
        if (!langText) return;
        
        const currentLang = localStorage.getItem('sh_language') || 'en';
        const langNames = { 'en': 'English', 'tl': 'Tagalog', 'ilo': 'Ilokano' };
        langText.textContent = langNames[currentLang] || 'English';
    }
    
    // Update on load
    updateSidebarLangBtn();
    
    // Listen for language changes
    window.addEventListener('languageChanged', updateSidebarLangBtn);
});
</script>
