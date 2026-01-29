<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    // Translation mappings for common SmartHarvest terms
    private static $translations = [
        'en_to_tl' => [
            'Welcome to SmartHarvest' => 'Maligayang pagdating sa SmartHarvest',
            'Hello, welcome to SmartHarvest' => 'Kamusta, maligayang pagdating sa SmartHarvest',
            'Optimize Your Planting with Data' => 'I-optimize ang Inyong Pagtatanim sa Pamamagitan ng Data',
            'Get Started Today' => 'Magsimula Ngayon',
            'Smart Agriculture Solutions' => 'Mga Matalinong Solusyon sa Agrikultura',
            'Data-Driven Farming' => 'Pagsasaka na Batay sa Data',
            'Yield Prediction' => 'Prediksiyon ng Ani',
            'Weather Monitoring' => 'Pagsusubaybay sa Panahon',
            'Crop Management' => 'Pamamahala ng Pananim',
            'Planting Schedule' => 'Iskedyul ng Pagtatanim',
            'Dashboard' => 'Dashboard',
            'Login' => 'Mag-login',
            'Register' => 'Mag-rehistro',
            'Features' => 'Mga Tampok',
            'About Us' => 'Tungkol Sa Amin',
            'Contact' => 'Makipag-ugnayan',
            'Our Mission' => 'Aming Misyon',
            'Our Vision' => 'Aming Bisyon',
            'Team' => 'Koponan',
            'Settings' => 'Mga Setting',
            'Profile' => 'Profile',
            'Logout' => 'Mag-logout',
            'Home' => 'Tahanan',
            'Navigation' => 'Nabigasyon',
            'Menu' => 'Menu',
            'Close' => 'Isara',
            'Open' => 'Buksan',
            'Language' => 'Wika',
            'English' => 'Ingles',
            'Filipino' => 'Filipino',
            'Tagalog' => 'Tagalog',
            'Select Language' => 'Pumili ng Wika',
            'Change Language' => 'Baguhin ang Wika',
            'Temperature' => 'Temperatura',
            'Humidity' => 'Humidity',
            'Wind Speed' => 'Bilis ng Hangin',
            'Rainfall' => 'Ulan',
            'Current Weather' => 'Kasalukuyang Panahon',
            'Weather Forecast' => 'Hula sa Panahon',
            'Today' => 'Ngayon',
            'Tomorrow' => 'Bukas',
            'This Week' => 'Ngayong Linggo',
            'Next Week' => 'Susunod na Linggo',
            'Loading' => 'Naglo-load',
            'Please wait' => 'Mangyaring maghintay',
            'Success' => 'Tagumpay',
            'Error' => 'Mali',
            'Warning' => 'Babala',
            'Information' => 'Impormasyon',
            'Save' => 'I-save',
            'Cancel' => 'Kanselahin',
            'Delete' => 'Tanggalin',
            'Edit' => 'I-edit',
            'Add' => 'Idagdag',
            'Update' => 'I-update',
            'Submit' => 'Isumite',
            'Reset' => 'I-reset',
            'Search' => 'Maghanap',
            'Filter' => 'Filter',
            'Sort' => 'Ayusin',
            'Previous' => 'Nakaraan',
            'Next' => 'Susunod',
            'First' => 'Una',
            'Last' => 'Huli',
            'Page' => 'Pahina',
            'of' => 'ng',
            'No data available' => 'Walang available na data',
            'No results found' => 'Walang nakitang resulta',
            'All' => 'Lahat',
            'None' => 'Wala',
            'Yes' => 'Oo',
            'No' => 'Hindi',
            'True' => 'Totoo',
            'False' => 'Mali',
            'On' => 'Naka-on',
            'Off' => 'Naka-off',
            'Active' => 'Aktibo',
            'Inactive' => 'Hindi aktibo',
            'Enabled' => 'Naka-enable',
            'Disabled' => 'Naka-disable',
            'Available' => 'Available',
            'Unavailable' => 'Hindi available',
            'Online' => 'Online',
            'Offline' => 'Offline',
            'Connected' => 'Nakakonekta',
            'Disconnected' => 'Hindi nakakonekta'
        ],
        'en_to_ilo' => [
            'Welcome to SmartHarvest' => 'Naragsak nga isasangbay iti SmartHarvest',
            'Hello, welcome to SmartHarvest' => 'Kumusta, naragsak nga isasangbay iti SmartHarvest',
            'Optimize Your Planting with Data' => 'Pasayaten ti Panagmulayo babaen ti Data',
            'Get Started Today' => 'Mangrugi ita',
            'Smart Agriculture Solutions' => 'Nasirib nga Solusion iti Agrikultura',
            'Data-Driven Farming' => 'Panagtalon nga Naibatay iti Data',
            'Yield Prediction' => 'Panagpadto ti Apit',
            'Weather Monitoring' => 'Panagbantay iti Paniempo',
            'Crop Management' => 'Panangiduro kadagiti Mula',
            'Planting Schedule' => 'Eskediul ti Panagmula',
            'Dashboard' => 'Dashboard',
            'Login' => 'Sumrek',
            'Register' => 'Agpalista',
            'Features' => 'Dagiti Tampok',
            'About Us' => 'Maipanggep Kadakami',
            'Contact' => 'Makikadua',
            'Our Mission' => 'Dagiti Panggepmi',
            'Our Vision' => 'Dagiti Sirmataymi',
            'Team' => 'Bunggoy',
            'Settings' => 'Dagiti Setting',
            'Profile' => 'Profile',
            'Logout' => 'Rummuar',
            'Home' => 'Pagtaengan',
            'Navigation' => 'Panagnavig',
            'Menu' => 'Menu',
            'Close' => 'Irikep',
            'Open' => 'Lukat',
            'Language' => 'Pagsasao',
            'English' => 'English',
            'Filipino' => 'Filipino',
            'Tagalog' => 'Tagalog',
            'Ilocano' => 'Ilocano',
            'Select Language' => 'Pilien ti Pagsasao',
            'Change Language' => 'Sukaten ti Pagsasao',
            'Temperature' => 'Temperatura',
            'Humidity' => 'Humidity',
            'Wind Speed' => 'Kapartak ti Angin',
            'Rainfall' => 'Tudo',
            'Current Weather' => 'Agdama a Paniempo',
            'Weather Forecast' => 'Padto ti Paniempo',
            'Today' => 'Ita nga Aldaw',
            'Tomorrow' => 'Inton Bigat',
            'This Week' => 'Ita a Lawas',
            'Next Week' => 'Sumaruno a Lawas',
            'Loading' => 'Agkarkarga',
            'Please wait' => 'Agurayka',
            'Success' => 'Nagballigi',
            'Error' => 'Biddut',
            'Warning' => 'Ballaag',
            'Information' => 'Pakaammo',
            'Save' => 'Idulin',
            'Cancel' => 'Ikansela',
            'Delete' => 'Ikkaten',
            'Edit' => 'Balbaliwan',
            'Add' => 'Nayon',
            'Update' => 'Balbaliwan',
            'Submit' => 'Ipasa',
            'Reset' => 'Isubli',
            'Search' => 'Biroken',
            'Filter' => 'Filter',
            'Sort' => 'Urnosen',
            'Previous' => 'Napalabas',
            'Next' => 'Sumaruno',
            'First' => 'Umuna',
            'Last' => 'Maudi',
            'Page' => 'Panid',
            'of' => 'iti',
            'No data available' => 'Awan ti magun-od a data',
            'No results found' => 'Awan ti nasarakan a resulta'
        ]
    ];

    // Supported languages - English, Tagalog, Ilocano only
    const LANGUAGES = [
        'en' => 'English',
        'tl' => 'Tagalog',
        'ilo' => 'Ilocano'
    ];

    public function __construct()
    {
        // No API key needed - using local translations + Google fallback
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
                'targetLanguage' => $targetLanguage,
                'service' => 'NoChange'
            ];
        }

        // Validate languages are supported
        if (!isset(self::LANGUAGES[$targetLanguage])) {
            return [
                'status' => 'error',
                'message' => 'Target language not supported. Supported: en, tl, ilo'
            ];
        }

        // Check local translations first
        $key = $sourceLanguage . '_to_' . $targetLanguage;
        if (isset(self::$translations[$key][$text])) {
            return [
                'status' => 'success',
                'translatedText' => self::$translations[$key][$text],
                'detectedLanguage' => $sourceLanguage,
                'targetLanguage' => $targetLanguage,
                'service' => 'LocalTranslation'
            ];
        }

        // Check cache
        try {
            $cacheKey = "translation_{$sourceLanguage}_{$targetLanguage}_" . md5($text);
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }
        } catch (\Exception $e) {
            // Cache not available, continue
            Log::warning('Cache not available: ' . $e->getMessage());
        }

        // Try Google Translate as fallback
        try {
            $result = $this->translateWithGoogle($text, $targetLanguage, $sourceLanguage);
            if ($result['status'] === 'success') {
                // Try to cache the result
                try {
                    Cache::put($cacheKey, $result, now()->addHours(24));
                } catch (\Exception $e) {
                    // Cache not available, continue
                }
                return $result;
            }
        } catch (\Exception $e) {
            Log::error('Translation error: ' . $e->getMessage());
        }

        // Fallback to original text
        return [
            'status' => 'fallback',
            'translatedText' => $text,
            'detectedLanguage' => $sourceLanguage,
            'targetLanguage' => $targetLanguage,
            'message' => 'Translation service unavailable, showing original text',
            'service' => 'Fallback'
        ];
    }
    /**
     * Translate using Google Translate (via unofficial API)
     */
    private function translateWithGoogle($text, $targetLanguage, $sourceLanguage)
    {
        try {
            // Use Google Translate's unofficial endpoint
            $url = 'https://translate.googleapis.com/translate_a/single';
            
            $response = Http::timeout(10)->get($url, [
                'client' => 'gtx',
                'sl' => $sourceLanguage,
                'tl' => $targetLanguage,
                'dt' => 't',
                'q' => $text
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Google Translate returns nested arrays, extract translation
                if (isset($data[0][0][0]) && !empty($data[0][0][0])) {
                    Log::info('Google Translate success', [
                        'from' => $sourceLanguage,
                        'to' => $targetLanguage,
                        'text' => substr($text, 0, 50)
                    ]);
                    
                    return [
                        'status' => 'success',
                        'translatedText' => $data[0][0][0],
                        'detectedLanguage' => $sourceLanguage,
                        'targetLanguage' => $targetLanguage,
                        'service' => 'Google Translate'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Google Translate failed: ' . $e->getMessage());
        }

        return ['status' => 'error'];
    }

    /**
     * Detect language of text
     */
    public function detectLanguage($text)
    {
        // Simple fallback detection based on common words
        $tagalogWords = ['ang', 'mga', 'sa', 'na', 'ay', 'ng', 'po'];
        $ilocanoWords = ['ti', 'dagiti', 'iti', 'nga', 'ken', 'amin'];
        
        $lowerText = strtolower($text);
        
        // Check for Tagalog words
        foreach ($tagalogWords as $word) {
            if (strpos($lowerText, ' ' . $word . ' ') !== false) {
                return ['language' => 'tl', 'confidence' => 0.7];
            }
        }
        
        // Check for Ilocano words
        foreach ($ilocanoWords as $word) {
            if (strpos($lowerText, ' ' . $word . ' ') !== false) {
                return ['language' => 'ilo', 'confidence' => 0.7];
            }
        }

        return ['language' => 'en', 'confidence' => 0.5];
    }

    /**
     * Get supported languages (English, Tagalog, Ilocano only)
     */
    public function getSupportedLanguages()
    {
        return self::LANGUAGES;
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
