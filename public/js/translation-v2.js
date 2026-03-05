/**
 * SmartHarvest Translation System v2.0
 * Static translation system with pre-defined accurate translations
 * Supports: English, Tagalog, Ilokano
 * 
 * Features:
 * - Static JSON-based translations (no API calls needed)
 * - Language persistence via localStorage
 * - Automatic page translation on load
 * - Graceful fallback to English
 */

const SmartHarvestTranslation = {
    // Configuration
    config: {
        storageKey: 'sh_language',
        defaultLanguage: 'en',
        translationsPath: '/js/translations/',
        debug: false
    },

    // State
    state: {
        currentLanguage: 'en',
        translations: {},
        isLoading: false,
        isInitialized: false
    },

    // Supported languages
    languages: {
        'en': { name: 'English', flag: '🇺🇸', nativeName: 'English' },
        'tl': { name: 'Tagalog', flag: '🇵🇭', nativeName: 'Tagalog' },
        'ilo': { name: 'Ilokano', flag: '🇵🇭', nativeName: 'Ilokano' }
    },

    /**
     * Initialize the translation system
     */
    async init() {
        if (this.state.isInitialized) {
            this.log('Already initialized');
            return;
        }

        this.log('Initializing SmartHarvest Translation System v2.0');
        
        // Detect base path for translation files
        this.detectBasePath();
        
        // Load saved language preference
        const savedLang = localStorage.getItem(this.config.storageKey);
        if (savedLang && this.languages[savedLang]) {
            this.state.currentLanguage = savedLang;
        }
        
        this.log('Current language:', this.state.currentLanguage);
        
        // Load English translations first (as fallback)
        await this.loadTranslations('en');
        
        // Load selected language if not English
        if (this.state.currentLanguage !== 'en') {
            await this.loadTranslations(this.state.currentLanguage);
            this.applyTranslations();
        }
        
        // Update UI
        this.updateLanguageSwitcher();
        
        this.state.isInitialized = true;
        this.log('Initialization complete');
    },

    /**
     * Detect the base path for translation files
     */
    detectBasePath() {
        const path = window.location.pathname;
        let basePath = '';
        
        if (path.includes('/smart_harvest/public')) {
            basePath = path.substring(0, path.indexOf('/smart_harvest/public') + 21);
        } else if (path.includes('/public')) {
            basePath = path.substring(0, path.indexOf('/public') + 7);
        }
        
        this.config.translationsPath = basePath + '/js/translations/';
        this.log('Translations path:', this.config.translationsPath);
    },

    /**
     * Load translations for a specific language
     */
    async loadTranslations(langCode) {
        if (this.state.translations[langCode]) {
            this.log('Translations already loaded for:', langCode);
            return;
        }

        try {
            this.state.isLoading = true;
            const url = this.config.translationsPath + langCode + '.json?v=' + Date.now();
            this.log('Loading translations from:', url);
            
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const data = await response.json();
            this.state.translations[langCode] = data;
            this.log('Loaded', Object.keys(data).length, 'translation categories for:', langCode);
            
        } catch (error) {
            console.error('Failed to load translations for', langCode, ':', error);
            // Fallback: use English
            if (langCode !== 'en' && this.state.translations['en']) {
                this.state.translations[langCode] = this.state.translations['en'];
            }
        } finally {
            this.state.isLoading = false;
        }
    },

    /**
     * Change the current language
     */
    async changeLanguage(langCode) {
        if (!this.languages[langCode]) {
            console.error('Unsupported language:', langCode);
            return;
        }

        if (langCode === this.state.currentLanguage) {
            this.log('Already using language:', langCode);
            return;
        }

        this.log('Changing language to:', langCode);
        
        // Load translations if not already loaded
        await this.loadTranslations(langCode);
        
        // Update state
        this.state.currentLanguage = langCode;
        
        // Save preference
        localStorage.setItem(this.config.storageKey, langCode);
        
        // Apply translations
        this.applyTranslations();
        
        // Update UI
        this.updateLanguageSwitcher();
        
        // Close any open dropdowns
        this.closeDropdowns();
        
        this.log('Language changed to:', langCode);
    },

    /**
     * Apply translations to all elements with data-translate or data-i18n attribute
     */
    applyTranslations() {
        const lang = this.state.currentLanguage;
        const translations = this.state.translations[lang] || this.state.translations['en'];
        
        if (!translations) {
            console.error('No translations available');
            return;
        }

        // Support both data-i18n and legacy data-translate-id attributes
        const elements = document.querySelectorAll('[data-i18n], [data-translate-id]');
        this.log('Translating', elements.length, 'elements');

        elements.forEach(element => {
            const key = element.getAttribute('data-i18n') || element.getAttribute('data-translate-id');
            const translation = this.getNestedValue(translations, key);
            
            if (translation) {
                // Handle different element types
                if (element.tagName === 'INPUT' && element.type === 'placeholder') {
                    element.placeholder = translation;
                } else if (element.hasAttribute('data-i18n-attr')) {
                    const attr = element.getAttribute('data-i18n-attr');
                    element.setAttribute(attr, translation);
                } else {
                    element.textContent = translation;
                }
            }
        });

        // Dispatch custom event for any components that need to react
        window.dispatchEvent(new CustomEvent('languageChanged', {
            detail: { language: lang, translations: translations }
        }));
    },

    /**
     * Get nested value from object using dot notation or flat kebab-case keys
     * e.g., "hero.title" -> translations.hero.title
     * e.g., "hero-title" -> translations["hero-title"] (flat key)
     */
    getNestedValue(obj, path) {
        // First, try direct lookup (for flat kebab-case keys like "hero-title")
        if (obj[path] !== undefined) {
            return obj[path];
        }
        
        // Then try nested lookup (for dot notation like "hero.title")
        if (path.includes('.')) {
            const result = path.split('.').reduce((current, key) => {
                return current && current[key] !== undefined ? current[key] : null;
            }, obj);
            if (result !== null) {
                return result;
            }
        }
        
        // Finally, try converting kebab-case to nested lookup
        // e.g., "hero-title" -> obj.hero.title
        if (path.includes('-')) {
            const parts = path.split('-');
            // Try first part as category, rest joined with camelCase
            if (parts.length >= 2) {
                const category = parts[0];
                const key = parts.slice(1).join('-');
                if (obj[category] && obj[category][key] !== undefined) {
                    return obj[category][key];
                }
            }
        }
        
        return null;
    },

    /**
     * Get translation by key
     */
    t(key, fallback = '') {
        const lang = this.state.currentLanguage;
        const translations = this.state.translations[lang] || this.state.translations['en'];
        return this.getNestedValue(translations, key) || fallback || key;
    },

    /**
     * Update language switcher UI elements
     */
    updateLanguageSwitcher() {
        const lang = this.languages[this.state.currentLanguage];
        if (!lang) return;

        // Update dropdown button text
        const buttons = document.querySelectorAll('[data-lang-button]');
        buttons.forEach(btn => {
            const showFlag = btn.hasAttribute('data-show-flag');
            const showName = btn.hasAttribute('data-show-name');
            
            let content = '';
            if (showFlag) content += lang.flag + ' ';
            if (showName !== false) content += lang.name;
            
            // Keep the dropdown arrow if it exists
            const arrow = btn.querySelector('svg');
            btn.textContent = content.trim();
            if (arrow) btn.appendChild(arrow.cloneNode(true));
        });

        // Update any standalone language display elements
        document.querySelectorAll('[data-current-language]').forEach(el => {
            el.textContent = lang.name;
        });
    },

    /**
     * Close all language dropdowns
     */
    closeDropdowns() {
        document.querySelectorAll('[data-lang-menu]').forEach(menu => {
            menu.classList.add('hidden');
        });
    },

    /**
     * Toggle dropdown visibility
     */
    toggleDropdown(menuId) {
        const menu = document.getElementById(menuId);
        if (menu) {
            menu.classList.toggle('hidden');
        }
    },

    /**
     * Get current language info
     */
    getCurrentLanguage() {
        return {
            code: this.state.currentLanguage,
            ...this.languages[this.state.currentLanguage]
        };
    },

    /**
     * Get all supported languages
     */
    getSupportedLanguages() {
        return Object.entries(this.languages).map(([code, info]) => ({
            code,
            ...info
        }));
    },

    /**
     * Debug logging
     */
    log(...args) {
        if (this.config.debug) {
            console.log('🌐 [Translation]', ...args);
        }
    }
};

/**
 * Language Switcher Component
 * Renders a dropdown language selector
 */
const LanguageSwitcher = {
    /**
     * Render the language switcher HTML
     */
    render(containerId, options = {}) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const {
            showFlags = true,
            dropdownClass = 'bg-white rounded-md shadow-xl border border-gray-200',
            buttonClass = 'flex items-center px-3 py-2 bg-white text-gray-700 rounded-lg border shadow-sm hover:bg-gray-50 transition',
            menuAlign = 'right'
        } = options;

        const currentLang = SmartHarvestTranslation.getCurrentLanguage();
        const languages = SmartHarvestTranslation.getSupportedLanguages();
        const menuId = containerId + '-menu';

        container.innerHTML = `
            <div class="relative">
                <button 
                    onclick="LanguageSwitcher.toggle('${menuId}')"
                    class="${buttonClass}"
                    data-lang-button
                    data-show-flag="${showFlags}"
                >
                    ${showFlags ? currentLang.flag + ' ' : ''}${currentLang.name}
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div 
                    id="${menuId}" 
                    data-lang-menu
                    class="hidden absolute ${menuAlign === 'right' ? 'right-0' : 'left-0'} mt-2 py-2 w-40 ${dropdownClass} z-50"
                >
                    ${languages.map(lang => `
                        <button 
                            onclick="LanguageSwitcher.select('${lang.code}')"
                            class="block w-full text-left px-4 py-2 hover:bg-gray-100 transition text-gray-800 ${lang.code === currentLang.code ? 'bg-green-50 text-green-700 font-semibold' : ''}"
                        >
                            ${showFlags ? lang.flag + ' ' : ''}${lang.name}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const menu = document.getElementById(menuId);
            if (menu && !container.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    },

    /**
     * Toggle dropdown menu
     */
    toggle(menuId) {
        const menu = document.getElementById(menuId);
        if (menu) {
            menu.classList.toggle('hidden');
        }
    },

    /**
     * Select a language
     */
    async select(langCode) {
        await SmartHarvestTranslation.changeLanguage(langCode);
        
        // Re-render all language switchers to update selected state
        document.querySelectorAll('[data-lang-switcher]').forEach(container => {
            const options = JSON.parse(container.getAttribute('data-lang-options') || '{}');
            this.render(container.id, options);
        });
    }
};

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    SmartHarvestTranslation.init();
});

// Export for use in other modules
if (typeof window !== 'undefined') {
    window.SmartHarvestTranslation = SmartHarvestTranslation;
    window.LanguageSwitcher = LanguageSwitcher;
}
