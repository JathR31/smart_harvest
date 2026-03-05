<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request to the weather dashboard
$request = Illuminate\Http\Request::create('/pagasa-weather', 'GET');
$request->setUserResolver(function () {
    // Create a fake authenticated user
    $user = new App\Models\User();
    $user->id = 1;
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->role = 'farmer';
    return $user;
});

try {
    echo "=== Testing /pagasa-weather Route ===\n\n";
    
    // Get the weather controller
    $service = new App\Services\PagasaWeatherService();
    $controller = new App\Http\Controllers\WeatherController($service);
    
    // Call the index method
    echo "Calling WeatherController@index...\n";
    $response = $controller->index();
    
    echo "Response type: " . get_class($response) . "\n";
    
    if ($response instanceof Illuminate\View\View) {
        echo "View name: " . $response->name() . "\n";
        echo "\nData passed to view:\n";
        $data = $response->getData();
        foreach ($data as $key => $value) {
            if (is_object($value) && method_exists($value, 'count')) {
                echo "  $key: " . $value->count() . " items\n";
            } elseif (is_array($value)) {
                echo "  $key: " . json_encode($value) . "\n";
            } elseif (is_object($value)) {
                echo "  $key: " . get_class($value) . "\n";
            } else {
                echo "  $key: $value\n";
            }
        }
    }
    
    echo "\n=== Test Complete ===\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
