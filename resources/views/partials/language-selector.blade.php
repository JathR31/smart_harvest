{{-- 
    Global Language Selector Component (Navbar Style)
    Usage: @include('partials.language-selector')
    
    This component provides a dropdown for selecting language (English, Tagalog, Ilokano)
    Works with translation-v2.js and stores preference in localStorage
--}}
<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button 
        @click="open = !open" 
        class="flex items-center px-3 py-1.5 bg-white text-gray-700 rounded-md border-none shadow-sm text-sm hover:bg-gray-100 transition duration-150 cursor-pointer"
        id="globalLangBtn"
    >
        <span id="globalLangText">English</span>
        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 py-2 w-40 bg-white rounded-lg shadow-xl z-50 border border-gray-200"
    >
        <button 
            @click="window.SmartHarvestTranslation.changeLanguage('en'); open = false" 
            class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm text-gray-700 transition"
            :class="{ 'bg-green-50 text-green-700 font-medium': localStorage.getItem('sh_language') === 'en' || !localStorage.getItem('sh_language') }"
        >
            <span class="mr-2">🇺🇸</span> English
        </button>
        <button 
            @click="window.SmartHarvestTranslation.changeLanguage('tl'); open = false" 
            class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm text-gray-700 transition"
            :class="{ 'bg-green-50 text-green-700 font-medium': localStorage.getItem('sh_language') === 'tl' }"
        >
            <span class="mr-2">🇵🇭</span> Tagalog
        </button>
        <button 
            @click="window.SmartHarvestTranslation.changeLanguage('ilo'); open = false" 
            class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm text-gray-700 transition"
            :class="{ 'bg-green-50 text-green-700 font-medium': localStorage.getItem('sh_language') === 'ilo' }"
        >
            <span class="mr-2">🇵🇭</span> Ilokano
        </button>
    </div>
</div>

<script>
// Update button text when language changes
document.addEventListener('DOMContentLoaded', function() {
    function updateGlobalLangBtn() {
        const langText = document.getElementById('globalLangText');
        if (!langText) return;
        
        const currentLang = localStorage.getItem('sh_language') || 'en';
        const langNames = { 'en': 'English', 'tl': 'Tagalog', 'ilo': 'Ilokano' };
        langText.textContent = langNames[currentLang] || 'English';
    }
    
    // Update on load
    updateGlobalLangBtn();
    
    // Listen for language changes
    window.addEventListener('languageChanged', updateGlobalLangBtn);
});
</script>
