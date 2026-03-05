<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║                   ENVIRONMENT VARIABLE CHECK                              ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

echo "Checking SMS_SIMULATION_MODE setting...\n\n";

$envValue = env('SMS_SIMULATION_MODE');
$configValue = config('services.sms.simulation_mode', env('SMS_SIMULATION_MODE'));

echo "From env():\n";
echo "  Value: ";
var_dump($envValue);
echo "  Type: " . gettype($envValue) . "\n";
echo "  Boolean: " . ($envValue ? 'true' : 'false') . "\n\n";

echo "String comparison:\n";
echo "  === 'true': " . ($envValue === 'true' ? 'YES' : 'NO') . "\n";
echo "  === true: " . ($envValue === true ? 'YES' : 'NO') . "\n";
echo "  === 'false': " . ($envValue === 'false' ? 'YES' : 'NO') . "\n";
echo "  === false: " . ($envValue === false ? 'YES' : 'NO') . "\n\n";

echo ".env file content:\n";
$envFile = file_get_contents(__DIR__ . '/.env');
$lines = explode("\n", $envFile);
foreach ($lines as $line) {
    if (stripos($line, 'SMS_SIMULATION') !== false) {
        echo "  " . trim($line) . "\n";
    }
}
echo "\n";

echo "Service class check:\n";
try {
    $service = app(\App\Services\SMSApiPhilippinesService::class);
    $reflection = new ReflectionClass($service);
    $property = $reflection->getProperty('simulationMode');
    $property->setAccessible(true);
    $simMode = $property->getValue($service);
    
    echo "  Service simulationMode: ";
    var_dump($simMode);
    echo "  Type: " . gettype($simMode) . "\n";
    echo "  Boolean: " . ($simMode ? 'true' : 'false') . "\n\n";
    
} catch (\Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n\n";
}

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║                            RECOMMENDATION                                 ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

if ($envValue === 'false' || $envValue === false) {
    echo "✓ SMS_SIMULATION_MODE is set to false\n";
    echo "  Real SMS should be sent (if SSL is configured correctly)\n\n";
} elseif ($envValue === 'true' || $envValue === true) {
    echo "⚠ SMS_SIMULATION_MODE is set to true\n";
    echo "  Only simulated SMS will be sent\n";
    echo "  To fix: Change SMS_SIMULATION_MODE=false in .env file\n\n";
} else {
    echo "? SMS_SIMULATION_MODE value is unclear: " . var_export($envValue, true) . "\n";
    echo "  Recommend setting explicitly: SMS_SIMULATION_MODE=false\n\n";
}
