<?php

namespace App\Services;

class SimpleTranslationService
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
            'No results found' => 'Awan ti nasarakan a resulta',
            'All' => 'Amin',
            'None' => 'Awan',
            'Yes' => 'Wen',
            'No' => 'Saan',
            'True' => 'Pudno',
            'False' => 'Ulbod',
            'On' => 'Nakasilsil',
            'Off' => 'Naikkat',
            'Active' => 'Aktibo',
            'Inactive' => 'Saan nga aktibo',
            'Enabled' => 'Napabalin',
            'Disabled' => 'Nailibes',
            'Available' => 'Magun-od',
            'Unavailable' => 'Saan a magun-od',
            'Online' => 'Online',
            'Offline' => 'Offline',
            'Connected' => 'Nakasilpo',
            'Disconnected' => 'Saan a nakasilpo'
        ]
    ];
    
    public static function translate($text, $targetLanguage = 'tl', $sourceLanguage = 'en')
    {
        // Direct translation key
        $key = $sourceLanguage . '_to_' . $targetLanguage;
        
        if ($sourceLanguage === $targetLanguage) {
            return [
                'status' => 'success',
                'translatedText' => $text,
                'detectedLanguage' => $sourceLanguage,
                'targetLanguage' => $targetLanguage,
                'service' => 'SimpleTranslation-NoChange'
            ];
        }
        
        if ($targetLanguage === 'tl' && $sourceLanguage === 'en') {
            if (isset(self::$translations['en_to_tl'][$text])) {
                return [
                    'status' => 'success',
                    'translatedText' => self::$translations['en_to_tl'][$text],
                    'detectedLanguage' => $sourceLanguage,
                    'targetLanguage' => $targetLanguage,
                    'service' => 'SimpleTranslation-Local'
                ];
            }
        }
        
        // Fallback to simple Google Translate API call without Laravel
        try {
            $url = 'https://translate.googleapis.com/translate_a/single?' . http_build_query([
                'client' => 'gtx',
                'sl' => $sourceLanguage,
                'tl' => $targetLanguage,
                'dt' => 't',
                'q' => $text
            ]);
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 10,
                    'header' => 'User-Agent: Mozilla/5.0'
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response !== false) {
                $data = json_decode($response, true);
                
                if (isset($data[0][0][0]) && !empty($data[0][0][0])) {
                    return [
                        'status' => 'success',
                        'translatedText' => $data[0][0][0],
                        'detectedLanguage' => $sourceLanguage,
                        'targetLanguage' => $targetLanguage,
                        'service' => 'GoogleTranslate-Simple'
                    ];
                }
            }
        } catch (Exception $e) {
            // Continue to fallback
        }
        
        // Ultimate fallback
        return [
            'status' => 'fallback',
            'translatedText' => $text,
            'detectedLanguage' => $sourceLanguage,
            'targetLanguage' => $targetLanguage,
            'message' => 'Translation service unavailable, showing original text',
            'service' => 'SimpleTranslation-Fallback'
        ];
    }
    
    public static function batchTranslate(array $texts, $targetLanguage = 'tl')
    {
        $results = [];
        foreach ($texts as $key => $text) {
            $results[$key] = self::translate($text, $targetLanguage);
        }
        return $results;
    }
    
    public static function getSupportedLanguages()
    {
        return [
            'en' => 'English',
            'tl' => 'Tagalog',
            'ilo' => 'Ilocano'
        ];
    }
}