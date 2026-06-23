<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Simulate API request
$email = 'dongmoduval269@icloud.cmo';
$password = 'password123';
$device_name = 'mobile_app';

echo "Simulating API login request\n";
echo "Email: $email\n";
echo "Password: $password\n";
echo "Device: $device_name\n\n";

// Create a mock request with JSON body
$request = \Illuminate\Http\Request::create('/api/login', 'POST', [], [], [], [
    'CONTENT_TYPE' => 'application/json',
    'HTTP_ACCEPT' => 'application/json',
], json_encode([
    'email' => $email,
    'password' => $password,
    'device_name' => $device_name,
]));

try {
    $controller = new \App\Http\Controllers\Api\AuthController();
    $response = $controller->login($request);
    
    echo "Response Status: " . $response->status() . "\n";
    $content = $response->getContent();
    echo "Response Content: $content\n";
    
    // Parse and pretty print JSON
    $data = json_decode($content, true);
    if ($data) {
        echo "\nPretty JSON:\n";
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Error: " . $e->getMessage() . "\n";
    echo "Errors: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
