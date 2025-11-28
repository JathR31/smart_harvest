<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    // FREE Translation APIs - No API key required!
    private $primaryUrl = 'https://libretranslate.com/translate';
    private $fallbackUrl = 'https://translate.argosopentech.com/translate';
    private $myMemoryUrl = 'https://api.mymemory.translated.net/get';
    
    // Language codes
    const LANGUAGES = [
        'en' => 'English',
        'tl' => 'Tagalog',
        'ilo' => 'Ilocano',
        'pam' => 'Kapampangan',
        'ceb' => 'Cebuano'
    ];
    
    // For languages not supported by free APIs, we'll use custom translations
    private $customTranslations = [
        'kankanaey' => 'kan',
        'ibaloi' => 'ibl'
    ];

    public function __construct()
    {
        // No API key needed - using free services!
    }

    /**
     * Translate text to specified language
     */
    public function translate($text, $targetLanguage = 'tl', $sourceLanguage = 'en')
    {
        // Check if translation is needed
        if ($sourceLanguage === $targetLanguage) {
            return [
                'status' => 'success',
                'translatedText' => $text,
                'detectedLanguage' => $sourceLanguage,
                'targetLanguage' => $targetLanguage
            ];
        }

        // Check cache first
        $cacheKey = "translation_{$sourceLanguage}_{$targetLanguage}_" . md5($text);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Handle custom languages (Kankanaey, Ibaloi)
        if (in_array($targetLanguage, ['kan', 'ibl'])) {
            return $this->translateToCustomLanguage($text, $targetLanguage);
        }

        // Try multiple FREE translation APIs
        try {
            // Method 1: MyMemory (Primary - Free, unlimited with fair use)
            $result = $this->translateWithMyMemory($text, $targetLanguage, $sourceLanguage);
            if ($result['status'] === 'success') {
                Cache::put($cacheKey, $result, now()->addHours(24));
                return $result;
            }

            // Method 2: LibreTranslate (Fallback - may require API key)
            $result = $this->translateWithLibreTranslate($text, $targetLanguage, $sourceLanguage);
            if ($result['status'] === 'success') {
                Cache::put($cacheKey, $result, now()->addHours(24));
                return $result;
            }

            // If all APIs fail, return original text
            return $this->getFallbackResponse($text, $targetLanguage);
            
        } catch (\Exception $e) {
            Log::error('Translation error: ' . $e->getMessage());
            return $this->getFallbackResponse($text, $targetLanguage);
        }
    }

    /**
     * Translate using LibreTranslate (Free & Open Source)
     */
    private function translateWithLibreTranslate($text, $targetLanguage, $sourceLanguage)
    {
        try {
            $response = Http::timeout(10)->post($this->primaryUrl, [
                'q' => $text,
                'source' => $sourceLanguage,
                'target' => $targetLanguage,
                'format' => 'text'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => 'success',
                    'translatedText' => $data['translatedText'] ?? $text,
                    'detectedLanguage' => $sourceLanguage,
                    'targetLanguage' => $targetLanguage,
                    'service' => 'LibreTranslate'
                ];
            }

            // Try fallback LibreTranslate instance
            $response = Http::timeout(10)->post($this->fallbackUrl, [
                'q' => $text,
                'source' => $sourceLanguage,
                'target' => $targetLanguage,
                'format' => 'text'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => 'success',
                    'translatedText' => $data['translatedText'] ?? $text,
                    'detectedLanguage' => $sourceLanguage,
                    'targetLanguage' => $targetLanguage,
                    'service' => 'LibreTranslate-Fallback'
                ];
            }
        } catch (\Exception $e) {
            Log::warning('LibreTranslate failed: ' . $e->getMessage());
        }

        return ['status' => 'error'];
    }

    /**
     * Translate using MyMemory Translation API (Free & Unlimited with fair use)
     */
    private function translateWithMyMemory($text, $targetLanguage, $sourceLanguage)
    {
        try {
            $langPair = "{$sourceLanguage}|{$targetLanguage}";
            $response = Http::timeout(15)->get($this->myMemoryUrl, [
                'q' => $text,
                'langpair' => $langPair
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['responseData']['translatedText']) && !empty($data['responseData']['translatedText'])) {
                    Log::info('MyMemory translation success', [
                        'from' => $sourceLanguage,
                        'to' => $targetLanguage,
                        'original' => substr($text, 0, 50),
                        'translated' => substr($data['responseData']['translatedText'], 0, 50)
                    ]);
                    
                    return [
                        'status' => 'success',
                        'translatedText' => $data['responseData']['translatedText'],
                        'detectedLanguage' => $sourceLanguage,
                        'targetLanguage' => $targetLanguage,
                        'service' => 'MyMemory'
                    ];
                }
            }
            
            Log::warning('MyMemory translation failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('MyMemory translation error: ' . $e->getMessage());
        }

        return ['status' => 'error'];
    }

    /**
     * Translate to custom languages (Kankanaey, Ibaloi)
     * Uses basic dictionary mapping for common agricultural terms
     */
    private function translateToCustomLanguage($text, $language)
    {
        // Common agricultural terms dictionary
        $dictionary = $this->getCustomDictionary($language);
        
        $translatedText = $text;
        foreach ($dictionary as $english => $translated) {
            $translatedText = preg_replace('/\b' . preg_quote($english, '/') . '\b/i', $translated, $translatedText);
        }
        
        return [
            'status' => 'success',
            'translatedText' => $translatedText,
            'detectedLanguage' => 'en',
            'targetLanguage' => $language,
            'method' => 'dictionary'
        ];
    }

    /**
     * Get custom dictionary for Cordillera languages
     */
    private function getCustomDictionary($language)
    {
        $dictionaries = [
            'kan' => [ // Kankanaey
                'rain' => 'udan',
                'temperature' => 'bakes',
                'crop' => 'mula',
                'harvest' => 'gapas',
                'plant' => 'patanum',
                'week' => 'lawas',
                'month' => 'bulan',
                'yield' => 'bunga',
                'weather' => 'panawen',
                'forecast' => 'padamag',
                'cabbage' => 'repolio',
                'carrot' => 'karot',
                'potato' => 'patatas',
                'farm' => 'uma',
                'farmer' => 'mannalon',
                'irrigation' => 'patubig',
                'soil' => 'daga'
            ],
            'ibl' => [ // Ibaloi
                'rain' => 'uran',
                'temperature' => 'pudot',
                'crop' => 'bunga',
                'harvest' => 'api',
                'plant' => 'tanem',
                'week' => 'dominggo',
                'month' => 'bulan',
                'yield' => 'ani',
                'weather' => 'tiempo',
                'forecast' => 'balwarte',
                'cabbage' => 'repolyo',
                'carrot' => 'karot',
                'potato' => 'patatas',
                'farm' => 'payew',
                'farmer' => 'managtanem',
                'irrigation' => 'tubig',
                'soil' => 'luta'
            ]
        ];

        return $dictionaries[$language] ?? [];
    }

    /**
     * Get fallback response when translation fails
     */
    private function getFallbackResponse($text, $targetLanguage)
    {
        return [
            'status' => 'fallback',
            'translatedText' => $text,
            'detectedLanguage' => 'en',
            'targetLanguage' => $targetLanguage,
            'message' => 'Translation service unavailable, showing original text'
        ];
    }

    /**
     * Detect language of text
     */
    public function detectLanguage($text)
    {
        if (empty($this->apiKey)) {
            return ['language' => 'unknown', 'confidence' => 0];
        }

        try {
            $response = Http::timeout(10)->post($this->baseUrl . '/detect', [
                'q' => $text,
                'key' => $this->apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'language' => $data['data']['detections'][0][0]['language'] ?? 'unknown',
                    'confidence' => $data['data']['detections'][0][0]['confidence'] ?? 0
                ];
            }
        } catch (\Exception $e) {
            Log::error('Language detection error: ' . $e->getMessage());
        }

        return ['language' => 'unknown', 'confidence' => 0];
    }

    /**
     * Get supported languages
     */
    public function getSupportedLanguages()
    {
        return array_merge(self::LANGUAGES, [
            'kan' => 'Kankanaey',
            'ibl' => 'Ibaloi'
        ]);
    }

    /**
     * Batch translate multiple texts
     */
    public function batchTranslate(array $texts, $targetLanguage = 'tl')
    {
        $results = [];
        foreach ($texts as $key => $text) {
            $results[$key] = $this->translate($text, $targetLanguage);
        }
        return $results;
    }
}
