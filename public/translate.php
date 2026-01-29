<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Translation mappings for SmartHarvest
$translations = [
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
        // Homepage specific content
        'Empower farmers with data-driven insights for optimal crop yields and sustainable farming practices.' => 'Bigyang kapangyarihan ang mga magsasaka gamit ang mga insight na batay sa data para sa pinakamataas na ani at sustainable na mga pamamaraan sa pagsasaka.',
        'Smart Crop Planning' => 'Matalinong Pagpaplano ng Pananim',
        'AI-powered recommendations for optimal planting schedules and crop selection based on weather patterns and soil conditions.' => 'Mga rekomendasyon na pinapagana ng AI para sa pinakamabuting iskedyul ng pagtatanim at pagpili ng pananim batay sa mga pattern ng panahon at kondisyon ng lupa.',
        'Real-time Weather Monitoring' => 'Real-time na Pagsusubaybay sa Panahon',
        'Access accurate weather forecasts and alerts to make informed decisions about farming activities.' => 'Mag-access ng mga tumpak na hula sa panahon at mga alerto upang makagawa ng mga informed na desisyon tungkol sa mga aktibidad sa pagsasaka.',
        'Yield Analytics' => 'Analytics ng Ani',
        'Track and analyze crop performance with detailed reports and predictive insights for better planning.' => 'Subaybayan at suriin ang pagganap ng pananim gamit ang mga detalyadong ulat at predictive insights para sa mas magandang pagpaplano.',
        'We believe in revolutionizing agriculture through technology, empowering farmers with the tools they need to maximize productivity while maintaining environmental sustainability.' => 'Naniniwala kami sa pag-revolusyon ng agrikultura sa pamamagitan ng teknolohiya, pagbibigay ng kapangyarihan sa mga magsasaka ng mga tool na kailangan nila para ma-maximize ang produktibidad habang nagpapanatili ng environmental sustainability.',
        'We envision a future where every farmer has access to smart farming solutions that optimize crop yields, reduce environmental impact, and ensure food security for generations to come.' => 'Nakikita namin ang isang kinabukasan kung saan ang bawat magsasaka ay may access sa mga matalinong solusyon sa pagsasaka na nag-o-optimize ng ani ng pananim, nagbabawas ng environmental impact, at nagsisiguro ng food security para sa mga susunod na henerasyon.',
        'SmartHarvest was founded by a team of agricultural experts and technology enthusiasts who recognized the need for data-driven farming solutions. Our diverse background in agriculture, data science, and software development allows us to create innovative tools that address real-world farming challenges.' => 'Ang SmartHarvest ay itinatag ng isang koponan ng mga eksperto sa agrikultura at mga enthusiast sa teknolohiya na nakilala ang pangangailangan para sa mga solusyon sa pagsasaka na batay sa data. Ang aming iba\'t ibang background sa agrikultura, data science, at software development ay nagbibigay-daan sa amin na lumikha ng mga innovative na tools na tumutugon sa mga tunay na hamon sa pagsasaka.',
        'Get started with SmartHarvest today and transform your farming practices with our intelligent agricultural solutions.' => 'Magsimula sa SmartHarvest ngayon at baguhin ang inyong mga pamamaraan sa pagsasaka gamit ang aming mga matalinong solusyon sa agrikultura.',
        'Join thousands of farmers already using SmartHarvest' => 'Sumali sa libu-libong magsasaka na gumagamit na ng SmartHarvest',
        // Homepage data-translate-id mappings
        'Welcome, {{ Auth::user()->name }}' => 'Maligayang pagdating, {{ Auth::user()->name }}',
        'DASHBOARD' => 'DASHBOARD',
        'Profile Settings' => 'Mga Setting ng Profile',
        'Dashboard' => 'Dashboard',
        'Logout' => 'Mag-logout',
        'LOGIN' => 'MAG-LOGIN',
        'Optimize Your Planting with Data' => 'I-optimize ang Inyong Pagtatanim sa Pamamagitan ng Data',
        'SmartHarvest uses historical yield and climate patterns to help farmers make informed planting decisions for maximum productivity.' => 'Ginagamit ng SmartHarvest ang mga historical yield at climate patterns upang makatulong sa mga magsasaka na gumawa ng informed planting decisions para sa maximum productivity.',
        'Go to Dashboard' => 'Pumunta sa Dashboard',
        'Get Started' => 'Magsimula',
        'Planting Schedule' => 'Iskedyul ng Pagtatanim',
        'Optimize planting times for maximum yield' => 'I-optimize ang mga oras ng pagtatanim para sa maximum yield',
        'Weather Insights' => 'Mga Insight sa Panahon',
        'Real-time weather data and forecasts' => 'Real-time na data sa panahon at mga hula',
        'Yield Analysis' => 'Pagsusuri ng Ani',
        'Track and analyze crop yields over time' => 'Subaybayan at suriin ang mga ani ng pananim sa paglipas ng panahon',
        'Our Mission' => 'Aming Misyon',
        'To empower farmers with data-driven insights and predictive analytics that optimize crop yields, reduce risks, and promote sustainable agricultural practices.' => 'Upang bigyan ng kapangyarihan ang mga magsasaka sa pamamagitan ng data-driven insights at predictive analytics na nag-o-optimize sa mga ani ng pananim, nagbabawas ng mga panganib, at nagpo-promote ng sustainable agricultural practices.',
        'About SmartHarvest' => 'Tungkol sa SmartHarvest',
        'Who We Are' => 'Kung Sino Kami',
        'SmartHarvest is a dedicated web-based Decision Support System (DSS) designed to empower farmers across the municipalities of Benguet. Our core mission is to promote sustainable farming methods and drastically improve farm decision-making in the face of persistent economic challenges.' => 'Ang SmartHarvest ay isang dedicated web-based Decision Support System (DSS) na idinisenyo upang bigyan ng kapangyarihan ang mga magsasaka sa buong municipalities ng Benguet. Ang aming pangunahing misyon ay i-promote ang mga sustainable farming methods at lubhang pagbutihin ang farm decision-making sa harap ng patuloy na mga economic challenges.',
        'We achieve this by utilizing gathered historical data on local crop yields and climate patterns. Through rigorous analysis, SmartHarvest identifies crucial correlations and trends between historical weather events and farm outcomes.' => 'Nakakamit namin ito sa pamamagitan ng paggamit ng mga nakolektang historical data sa local crop yields at climate patterns. Sa pamamagitan ng masusing pagsusuri, kinikilala ng SmartHarvest ang mga mahalagang correlations at trends sa pagitan ng mga historical weather events at farm outcomes.',
        'This process allows us to generate data-driven recommendations, providing farmers with a comprehensive report on the optimal planting times to maximize yield, guidance for better resource utilization, and actionable insights to reduce climate risks to their livelihoods and the regional economy. We\'re here to turn data into smarter harvests.' => 'Ang prosesong ito ay nagbibigay-daan sa amin na makabuo ng data-driven recommendations, na nagbibigay sa mga magsasaka ng comprehensive report sa optimal planting times upang ma-maximize ang yield, gabay para sa mas magandang resource utilization, at actionable insights upang mabawasan ang climate risks sa kanilang kabuhayan at regional economy. Narito kami upang gawing mas matalinong ani ang data.',
        'Empower Farmers' => 'Bigyang Kapangyarihan ang mga Magsasaka',
        'Provide accessible tools and insights to help farmers make informed decisions about their crops and maximize productivity.' => 'Magbigay ng mga accessible tools at insights upang makatulong sa mga magsasaka na gumawa ng informed decisions tungkol sa kanilang mga pananim at ma-maximize ang productivity.',
        'Sustainable Agriculture' => 'Sustainable na Agrikultura',
        'Promote environmentally responsible farming practices through optimized resource utilization and climate-aware planning.' => 'I-promote ang mga environmentally responsible farming practices sa pamamagitan ng optimized resource utilization at climate-aware planning.',
        'Data-Driven Solutions' => 'Mga Solusyong Batay sa Data',
        'Leverage historical data, climate patterns, and advanced analytics to predict outcomes and recommend optimal strategies.' => 'Gamitin ang historical data, climate patterns, at advanced analytics upang mahulaan ang mga resulta at mag-recommend ng optimal strategies.',
        'Live Weather Insights' => 'Live Weather Insights',
        'Real-time weather monitoring for informed agricultural decisions' => 'Real-time weather monitoring para sa informed agricultural decisions',
        'Select Municipality' => 'Piliin ang Municipality',
        'Temperature' => 'Temperatura',
        'Humidity' => 'Humidity',
        'Wind Speed' => 'Bilis ng Hangin',
        'Rainfall (24h)' => 'Ulan (24h)',
        'Current Conditions' => 'Kasalukuyang Kondisyon',
        'Select a municipality' => 'Pumili ng municipality',
        'Weather data updates every 5 seconds. Our system integrates real-time weather information with historical patterns to provide accurate planting recommendations.' => 'Naa-update ang weather data bawat 5 segundo. Pinagsamasama ng aming system ang real-time weather information sa historical patterns upang magbigay ng mga tumpak na planting recommendations.',
        'View Detailed Weather Dashboard' => 'Tingnan ang Detalyadong Weather Dashboard'
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
        'Disconnected' => 'Saan a nakasilpo',
        // Homepage specific content
        'Empower farmers with data-driven insights for optimal crop yields and sustainable farming practices.' => 'Palbiagek dagiti mannalon babaen kadagiti insights nga naibatay iti data para iti kasayaatan nga apit ken sustainable nga aramid iti panagtalon.',
        'Smart Crop Planning' => 'Nasirib a Panagplano ti Mula',
        'AI-powered recommendations for optimal planting schedules and crop selection based on weather patterns and soil conditions.' => 'Dagiti rekomendasion nga napapigsaan ti AI para iti kasayaatan nga eskediul ti panagmula ken panagpili ti mula nga naibatay kadagiti padron ti paniempo ken kondision ti daga.',
        'Real-time Weather Monitoring' => 'Real-time a Panagbantay iti Paniempo',
        'Access accurate weather forecasts and alerts to make informed decisions about farming activities.' => 'Makastrek kadagiti nalinteg a padto ti paniempo ken ballaag tapno makaaramid kadagiti nasayaat a pangngeddeng maipanggep kadagiti aramid iti panagtalon.',
        'Yield Analytics' => 'Analytics ti Apit',
        'Track and analyze crop performance with detailed reports and predictive insights for better planning.' => 'Bantayan ken analisaren ti panagaramid dagiti mula babaen kadagiti detalyado nga ulat ken dagiti predictive insights para iti nasayaat a panagplano.',
        'We believe in revolutionizing agriculture through technology, empowering farmers with the tools they need to maximize productivity while maintaining environmental sustainability.' => 'Patpatienmi ti panag-revolusion ti agrikultura babaen ti teknolohiya, panangpapigsaen kadagiti mannalon babaen kadagiti ramramit nga kasapulanda tapno mapadakkel ti produktibidad bayat a panagtaginayonda iti environmental sustainability.',
        'We envision a future where every farmer has access to smart farming solutions that optimize crop yields, reduce environmental impact, and ensure food security for generations to come.' => 'Makitaenmi ti masakbayan a sadiay tunggal mannalon ket addaan iti panggedgeddanan kadagiti nasirib nga solusion iti panagtalon a mangpadakkel iti apit dagiti mula, mangkissay iti environmental impact, ken mangsigurado iti food security para kadagiti masakbayan nga henerasion.',
        'SmartHarvest was founded by a team of agricultural experts and technology enthusiasts who recognized the need for data-driven farming solutions. Our diverse background in agriculture, data science, and software development allows us to create innovative tools that address real-world farming challenges.' => 'Ti SmartHarvest ket naipatakder babaen ti maysa a bunggoy dagiti eksperto iti agrikultura ken dagiti enthusiast iti teknolohiya a nangbigbig iti kasapulan para kadagiti solusion iti panagtalon nga naibatay iti data. Ti nadumaduma a backgroundmi iti agrikultura, data science, ken software development ket mangipalpalubos kadakami a mangpartuat kadagiti innovative nga ramramit a mangtaming kadagiti pudno nga karit iti panagtalon.',
        'Get started with SmartHarvest today and transform your farming practices with our intelligent agricultural solutions.' => 'Mangrugi iti SmartHarvest ita ken sukaten ti panag-aratinganmo iti panagtalon babaen kadagiti nanakem nga agrikultura a solusionmi.',
        'Join thousands of farmers already using SmartHarvest' => 'Makikadua kadagiti rinibu nga mannalon nga agusar itian iti SmartHarvest',
        // Homepage data-translate-id mappings
        'Welcome, {{ Auth::user()->name }}' => 'Naragsak nga isasangbay, {{ Auth::user()->name }}',
        'DASHBOARD' => 'DASHBOARD',
        'Profile Settings' => 'Dagiti Setting ti Profile',
        'Dashboard' => 'Dashboard',
        'Logout' => 'Rummuar',
        'LOGIN' => 'SUMREK',
        'Optimize Your Planting with Data' => 'Pasayaten ti Panagmulayo babaen ti Data',
        'SmartHarvest uses historical yield and climate patterns to help farmers make informed planting decisions for maximum productivity.' => 'Usaren ti SmartHarvest dagiti historical yield ken climate patterns tapno tumulong kadagiti mannalon a makaaramid kadagiti informed planting decisions para iti maximum productivity.',
        'Go to Dashboard' => 'Mapan iti Dashboard',
        'Get Started' => 'Mangrugi',
        'Planting Schedule' => 'Eskediul ti Panagmula',
        'Optimize planting times for maximum yield' => 'Pasayaten dagiti tiempo ti panagmula para iti maximum yield',
        'Weather Insights' => 'Dagiti Insight iti Paniempo',
        'Real-time weather data and forecasts' => 'Real-time nga data iti paniempo ken dagiti padto',
        'Yield Analysis' => 'Panaganalisar ti Apit',
        'Track and analyze crop yields over time' => 'Bantayan ken analisaren dagiti apit dagiti mula iti panaglabas ti tiempo',
        'Our Mission' => 'Dagiti Panggepmi',
        'To empower farmers with data-driven insights and predictive analytics that optimize crop yields, reduce risks, and promote sustainable agricultural practices.' => 'Tapno palbiagek dagiti mannalon babaen kadagiti data-driven insights ken predictive analytics a mangpasayaat kadagiti apit dagiti mula, mangkissay kadagiti peggad, ken mangitantan kadagiti sustainable agricultural practices.',
        'About SmartHarvest' => 'Maipanggep iti SmartHarvest',
        'Who We Are' => 'Sino Kami',
        'SmartHarvest is a dedicated web-based Decision Support System (DSS) designed to empower farmers across the municipalities of Benguet. Our core mission is to promote sustainable farming methods and drastically improve farm decision-making in the face of persistent economic challenges.' => 'Ti SmartHarvest ket maysa a dedicated web-based Decision Support System (DSS) a nadisenio tapno palbiagek dagiti mannalon iti entero nga municipalities ti Benguet. Ti kangrunaan a panggepmi ket itantan dagiti sustainable farming methods ken dakkel a pasayaaten ti farm decision-making iti sangoanan dagiti agtultuloy nga economic challenges.',
        'We achieve this by utilizing gathered historical data on local crop yields and climate patterns. Through rigorous analysis, SmartHarvest identifies crucial correlations and trends between historical weather events and farm outcomes.' => 'Magun-odmi daytoy babaen ti panagusar kadagiti naurnong nga historical data kadagiti lokal nga apit dagiti mula ken climate patterns. Babaen ti narigat nga panaganalisar, mailasin ti SmartHarvest dagiti napateg nga correlations ken trends iti nagbaetan dagiti historical weather events ken farm outcomes.',
        'This process allows us to generate data-driven recommendations, providing farmers with a comprehensive report on the optimal planting times to maximize yield, guidance for better resource utilization, and actionable insights to reduce climate risks to their livelihoods and the regional economy. We\'re here to turn data into smarter harvests.' => 'Ti daytoy nga proseso ket mangipalubos kadakami a mangpataud kadagiti data-driven recommendations, a mangted kadagiti mannalon iti comprehensive report kadagiti optimal planting times tapno mapadakkel ti apit, pagturongan para iti nasayaat nga resource utilization, ken actionable insights tapno makissay dagiti climate risks kadagiti biagda ken regional economy. Adda kami ditoy tapno pagbalinen dagiti data nga nasirsirib nga ani.',
        'Empower Farmers' => 'Palbiagek dagiti Mannalon',
        'Provide accessible tools and insights to help farmers make informed decisions about their crops and maximize productivity.' => 'Mangipaay kadagiti accessible tools ken insights tapno tumulong kadagiti mannalon a makaaramid kadagiti informed decisions maipanggep kadagiti mulada ken mapadakkel ti produktibidad.',
        'Sustainable Agriculture' => 'Sustainable nga Agrikultura',
        'Promote environmentally responsible farming practices through optimized resource utilization and climate-aware planning.' => 'Itantan dagiti environmentally responsible farming practices babaen ti optimized resource utilization ken climate-aware planning.',
        'Data-Driven Solutions' => 'Dagiti Solusion nga Naibatay iti Data',
        'Leverage historical data, climate patterns, and advanced analytics to predict outcomes and recommend optimal strategies.' => 'Usaren ti historical data, climate patterns, ken advanced analytics tapno maipadto dagiti resulta ken mairekomendar dagiti optimal strategies.',
        'Live Weather Insights' => 'Live Weather Insights',
        'Real-time weather monitoring for informed agricultural decisions' => 'Real-time weather monitoring para kadagiti informed agricultural decisions',
        'Select Municipality' => 'Pilien ti Municipality',
        'Temperature' => 'Temperatura',
        'Humidity' => 'Humidity',
        'Wind Speed' => 'Kapartak ti Angin',
        'Rainfall (24h)' => 'Tudo (24h)',
        'Current Conditions' => 'Agdama nga Kondision',
        'Select a municipality' => 'Pilien ti municipality',
        'Weather data updates every 5 seconds. Our system integrates real-time weather information with historical patterns to provide accurate planting recommendations.' => 'Ma-update ti weather data binukel 5 segundos. Ti sistemami ket mangkadkadua iti real-time weather information kadagiti historical patterns tapno mangipaay kadagiti nalinteg nga planting recommendations.',
        'View Detailed Weather Dashboard' => 'Kitaen ti Detalyado nga Weather Dashboard'
    ]
];

function translateText($text, $targetLanguage = 'tl', $sourceLanguage = 'en') {
    global $translations;
    
    if ($sourceLanguage === $targetLanguage) {
        return [
            'status' => 'success',
            'translatedText' => $text,
            'detectedLanguage' => $sourceLanguage,
            'targetLanguage' => $targetLanguage,
            'service' => 'NoChange'
        ];
    }
    
    $key = $sourceLanguage . '_to_' . $targetLanguage;
    
    if (isset($translations[$key][$text])) {
        return [
            'status' => 'success',
            'translatedText' => $translations[$key][$text],
            'detectedLanguage' => $sourceLanguage,
            'targetLanguage' => $targetLanguage,
            'service' => 'LocalTranslation'
        ];
    }
    
    // Try Google Translate as fallback
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
                'timeout' => 5,
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
                    'service' => 'GoogleTranslate'
                ];
            }
        }
    } catch (Exception $e) {
        // Continue to fallback
    }
    
    // Fallback
    return [
        'status' => 'fallback',
        'translatedText' => $text,
        'detectedLanguage' => $sourceLanguage,
        'targetLanguage' => $targetLanguage,
        'message' => 'Translation service unavailable, showing original text',
        'service' => 'Fallback'
    ];
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
        exit();
    }
    
    $requestUri = $_SERVER['REQUEST_URI'];
    
    if (strpos($requestUri, '/batch') !== false) {
        // Batch translation
        $texts = $input['texts'] ?? [];
        $targetLanguage = $input['target_language'] ?? 'tl';
        
        if (empty($texts) || !is_array($texts)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Texts array is required']);
            exit();
        }
        
        $results = [];
        foreach ($texts as $key => $text) {
            $results[$key] = translateText($text, $targetLanguage);
        }
        
        echo json_encode([
            'status' => 'success',
            'translations' => $results
        ]);
        
    } else {
        // Single translation
        $text = $input['text'] ?? '';
        $targetLanguage = $input['target_language'] ?? 'tl';
        $sourceLanguage = $input['source_language'] ?? 'en';
        
        if (empty($text)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Text is required']);
            exit();
        }
        
        echo json_encode(translateText($text, $targetLanguage, $sourceLanguage));
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $requestUri = $_SERVER['REQUEST_URI'];
    
    if (strpos($requestUri, '/languages') !== false) {
        // Get supported languages
        echo json_encode([
            'status' => 'success',
            'languages' => [
                'en' => 'English',
                'tl' => 'Tagalog',
                'ilo' => 'Ilocano'
            ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
    }
    
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>