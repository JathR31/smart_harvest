# Translation API - Quick Start Guide

## Quick Test

### Test in Browser
1. Open: http://localhost/dashboard/smart_harvest/public/test_translate.html
2. Select a language (Tagalog, Ilocano, Kankanaey, or Ibaloi)
3. Click "Translate" button
4. See the translation in real-time!

### Test via Command Line
```bash
cd c:\xampp\htdocs\dashboard\smart_harvest
php test_translation_api.php
```

## Using the API in JavaScript

### Single Translation
```javascript
const response = await fetch('/api/translate', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        text: 'Hello, welcome!',
        target_language: 'tl',
        source_language: 'en'
    })
});

const data = await response.json();
console.log(data.translatedText); // "Kamusta, maligayang pagdating!"
```

### Batch Translation (Multiple Texts)
```javascript
const response = await fetch('/api/translate/batch', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        texts: [
            'Welcome',
            'Get Started',
            'Learn More'
        ],
        target_language: 'tl'
    })
});

const data = await response.json();
data.translations.forEach((translation, index) => {
    console.log(translation.translatedText);
});
```

## Using in PHP/Laravel

```php
use App\Services\TranslationService;

$translationService = new TranslationService();

// Single translation
$result = $translationService->translate('Hello', 'tl', 'en');
echo $result['translatedText']; // Kamusta

// Batch translation
$results = $translationService->batchTranslate([
    'Welcome',
    'Get Started',
    'Learn More'
], 'tl');

foreach ($results as $result) {
    echo $result['translatedText'] . "\n";
}

// Get supported languages
$languages = $translationService->getSupportedLanguages();
print_r($languages);
```

## Language Codes

| Code | Language | Status |
|------|----------|--------|
| en | English | ✅ Native |
| tl | Tagalog | ✅ API |
| ilo | Ilocano | ✅ API |
| pam | Kapampangan | ✅ API |
| ceb | Cebuano | ✅ API |
| kan | Kankanaey | ✅ Dictionary |
| ibl | Ibaloi | ✅ Dictionary |

## How to Make Elements Translatable on Your Page

Add `data-translate` and `data-translate-id` attributes:

```html
<h1 data-translate data-translate-id="title1">
    Welcome to SmartHarvest
</h1>

<p data-translate data-translate-id="desc1">
    Optimize your planting with data-driven insights
</p>

<button data-translate data-translate-id="btn1">
    Get Started
</button>
```

Then use the translation manager:

```javascript
// Translate the entire page
await TranslationManager.translatePage('tl'); // Tagalog
await TranslationManager.translatePage('ilo'); // Ilocano
await TranslationManager.translatePage('kan'); // Kankanaey
```

## Troubleshooting

### Translation not working?
1. Check browser console for errors (F12)
2. Verify API endpoint is accessible: `/api/translate/batch`
3. Make sure XAMPP Apache is running
4. Check that `data-translate` attributes are present

### Getting original text back?
This means the translation service couldn't translate. The API automatically falls back to showing the original text to avoid breaking the page.

### Want to test a specific language?
Use the test pages:
- http://localhost/dashboard/smart_harvest/public/test_translate.html
- http://localhost/dashboard/smart_harvest/public/test_translation_live.html

## API Response Formats

### Success Response
```json
{
    "status": "success",
    "translatedText": "Maligayang pagdating",
    "detectedLanguage": "en",
    "targetLanguage": "tl",
    "service": "MyMemory"
}
```

### Fallback Response (when translation fails)
```json
{
    "status": "fallback",
    "translatedText": "Welcome",
    "detectedLanguage": "en",
    "targetLanguage": "tl",
    "message": "Translation service unavailable, showing original text"
}
```

### Error Response
```json
{
    "status": "error",
    "message": "Text is required"
}
```

## Tips

1. **Batch translations are more efficient** - Use `/api/translate/batch` when translating multiple texts
2. **Translations are cached** - Same text won't be translated twice (cached for 24 hours)
3. **No API keys needed** - All translation services are free and don't require API keys
4. **Automatic fallback** - If one service fails, it tries another automatically
5. **Always shows something** - Even if translation fails, original text is shown

---

🎉 **Your translation API is ready to use!**
