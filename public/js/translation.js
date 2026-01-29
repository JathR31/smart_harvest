/**
 * SmartHarvest Translation System
 * Reusable translation manager for multi-language support
 * Supports: English, Tagalog, Ilocano, Kankanaey, Ibaloi
 */

const SmartHarvestTranslation = {
    // Configuration
    config: {
        apiUrl: null, // Will be set dynamically
        storageKey: 'preferredLanguage',
        storageNameKey: 'preferredLanguageName',
        defaultLanguage: 'en',
        defaultLanguageName: 'English',
        cacheEnabled: true,
        cacheDuration: 24 * 60 * 60 * 1000 // 24 hours in milliseconds
    },

    // State
    state: {
        selectedLanguage: null,
        selectedLanguageName: null,
        originalTexts: {},
        translationCache: {},
        isTranslating: false
    },

    // Supported languages - English, Tagalog, Ilocano
    languages: {
        'en': 'English',
        'tl': 'Tagalog',
        'ilo': 'Ilocano'
    },

    /**
     * Initialize the translation system
     */
    init() {
        console.log('🌐 SmartHarvest Translation System initialized');
        
        // Detect the base path from current location
        const path = window.location.pathname;
        let basePath = '';
        
        // If we're in the public folder structure, extract the base
        if (path.includes('/dashboard/smart_harvest/public')) {
            basePath = '/dashboard/smart_harvest/public';
        } else if (path.includes('/public')) {
            basePath = path.substring(0, path.indexOf('/public') + 7);
        } else {
            // Default fallback
            basePath = '';
        }
        
        // Use the simple PHP translation endpoint instead of Laravel API
        this.config.apiUrl = basePath + '/translate.php/batch';
        console.log('🔗 API URL:', this.config.apiUrl);
        
        // Load saved language preference
        this.state.selectedLanguage = localStorage.getItem(this.config.storageKey) || this.config.defaultLanguage;
        this.state.selectedLanguageName = localStorage.getItem(this.config.storageNameKey) || this.config.defaultLanguageName;

        // Load cached translations
        if (this.config.cacheEnabled) {
            this.loadCache();
        }

        // Update UI to show current language
        this.updateLanguageDisplay();

        // Auto-translate if not English
        if (this.state.selectedLanguage !== 'en') {
            console.log('🔄 Auto-translating to:', this.state.selectedLanguageName);
            this.translatePage(this.state.selectedLanguage);
        }
    },

    /**
     * Change the current language
     */
    async changeLanguage(code, name) {
        console.log('🌐 Changing language to:', name, '(' + code + ')');
        alert('Changing language to: ' + name); // DEBUG
        
        // Close dropdown menu if it exists
        const dropdownMenu = document.getElementById('languageDropdownMenu');
        if (dropdownMenu) {
            dropdownMenu.classList.add('hidden');
        }
        
        if (code === this.state.selectedLanguage) {
            console.log('ℹ️ Already in', name);
            alert('Already in ' + name); // DEBUG
            return;
        }

        this.state.selectedLanguage = code;
        this.state.selectedLanguageName = name;
        
        // Save preference
        localStorage.setItem(this.config.storageKey, code);
        localStorage.setItem(this.config.storageNameKey, name);

        // Update UI
        this.updateLanguageDisplay();

        // Translate or reload
        if (code !== 'en') {
            alert('About to translate to: ' + code); // DEBUG
            await this.translatePage(code);
        } else {
            // Restore original texts
            this.restoreOriginalTexts();
        }
    },

    /**
     * Restore original English texts
     */
    restoreOriginalTexts() {
        const elements = document.querySelectorAll('[data-translate]');
        elements.forEach(el => {
            const id = el.getAttribute('data-translate-id');
            if (this.state.originalTexts[id]) {
                el.textContent = this.state.originalTexts[id];
            }
        });
    },
        }
    },

    /**
     * Translate the entire page
     */
    async translatePage(targetLang) {
        alert('translatePage called for: ' + targetLang); // DEBUG
        
        if (this.state.isTranslating) {
            console.log('⚠️ Translation already in progress');
            return;
        }

        this.state.isTranslating = true;
        console.log('🔄 Starting translation to:', targetLang);

        try {
            // Get all translatable elements
            const elements = document.querySelectorAll('[data-translate]');
            console.log('📝 Found', elements.length, 'translatable elements');
            alert('Found ' + elements.length + ' translatable elements'); // DEBUG

            if (elements.length === 0) {
                console.warn('⚠️ No translatable elements found!');
                this.state.isTranslating = false;
                return;
            }

            // Collect texts and save originals
            const texts = Array.from(elements).map(el => {
                const id = el.getAttribute('data-translate-id');
                if (!this.state.originalTexts[id]) {
                    this.state.originalTexts[id] = el.textContent.trim();
                }
                return this.state.originalTexts[id];
            });

            // Check cache first
            const cacheKey = targetLang + '_batch';
            let translations = null;

            if (this.config.cacheEnabled && this.state.translationCache[cacheKey]) {
                console.log('📦 Using cached translations for', targetLang);
                translations = this.state.translationCache[cacheKey];
            } else {
                console.log('📤 Sending', texts.length, 'texts for translation');
                console.log('🌐 API URL:', this.config.apiUrl);

                // Call translation API
                const response = await fetch(this.config.apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        texts: texts,
                        target_language: targetLang
                    })
                });

                console.log('📥 Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('📦 Response data:', data);

                if (data.status === 'success') {
                    translations = data.translations;
                    
                    // Cache the results
                    if (this.config.cacheEnabled) {
                        this.state.translationCache[cacheKey] = translations;
                        this.saveCache();
                    }
                } else {
                    throw new Error(data.message || 'Translation failed');
                }
            }

            // Apply translations
            if (translations) {
                console.log('✅ Applying', translations.length, 'translations');
                let successCount = 0;

                elements.forEach((el, index) => {
                    if (translations[index]?.translatedText) {
                        const original = el.textContent.trim();
                        const translated = translations[index].translatedText;
                        
                        if (original !== translated) {
                            el.textContent = translated;
                            successCount++;
                            console.log(`${index + 1}. "${original}" → "${translated}"`);
                        }
                    }
                });

                console.log('🎉 Translation complete!', successCount, 'elements translated');
            }

        } catch (error) {
            console.error('💥 Translation error:', error);
            this.showError('Translation failed. Please try again.');
        } finally {
            this.state.isTranslating = false;
        }
    },

    /**
     * Update language display in UI
     */
    updateLanguageDisplay() {
        const dropdownBtn = document.getElementById('languageDropdownBtn');
        if (dropdownBtn) {
            const svgIcon = '<svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
            dropdownBtn.innerHTML = this.state.selectedLanguageName + svgIcon;
        }
    },

    /**
     * Show error message to user
     */
    showError(message) {
        console.error('❌', message);
        // You can customize this to show a toast or alert
        // For now, just log to console
    },

    /**
     * Load cached translations from localStorage
     */
    loadCache() {
        try {
            const cached = localStorage.getItem('translationCache');
            if (cached) {
                const data = JSON.parse(cached);
                const now = Date.now();
                
                // Check if cache is still valid
                if (data.timestamp && (now - data.timestamp) < this.config.cacheDuration) {
                    this.state.translationCache = data.cache || {};
                    console.log('📦 Loaded cached translations');
                } else {
                    console.log('🗑️ Cache expired, clearing');
                    localStorage.removeItem('translationCache');
                }
            }
        } catch (error) {
            console.error('Error loading cache:', error);
        }
    },

    /**
     * Save translations to cache
     */
    saveCache() {
        try {
            const data = {
                cache: this.state.translationCache,
                timestamp: Date.now()
            };
            localStorage.setItem('translationCache', JSON.stringify(data));
            console.log('💾 Saved translations to cache');
        } catch (error) {
            console.error('Error saving cache:', error);
        }
    },

    /**
     * Clear translation cache
     */
    clearCache() {
        this.state.translationCache = {};
        localStorage.removeItem('translationCache');
        console.log('🗑️ Translation cache cleared');
    },

    /**
     * Get supported languages
     */
    getSupportedLanguages() {
        return this.languages;
    },

    /**
     * Check if a language is supported
     */
    isLanguageSupported(code) {
        return code in this.languages;
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => SmartHarvestTranslation.init());
} else {
    SmartHarvestTranslation.init();
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SmartHarvestTranslation;
}
