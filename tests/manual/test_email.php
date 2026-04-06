<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $user = App\Models\User::where('email', 'jath.carbonell@gmail.com')->first();
    
    if (!$user) {
        echo "User not found!\n";
        exit;
    }
    
    echo "Found user: {$user->email}\n";
    echo "Email verified: " . ($user->hasVerifiedEmail() ? 'Yes' : 'No') . "\n";
    
    if (!$user->hasVerifiedEmail()) {
        echo "Sending verification email...\n";
        $user->sendEmailVerificationNotification();
        echo "✓ Email sent successfully!\n";
        echo "Check your inbox and spam folder at: jath.carbonell@gmail.com\n";
    } else {
        echo "Email already verified!\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
