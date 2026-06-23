<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get a locataire user with token
$user = \App\Models\User::where('account_type', 'Locataire')->first();

if (!$user) {
    file_put_contents('api_output.txt', "No locataire user found");
    exit;
}

// Create a token for testing
$token = $user->createToken('test')->plainTextToken;

// Make a request to the dashboard endpoint
$request = \Illuminate\Http\Request::create('/api/locataire/dashboard', 'GET');
$request->headers->set('Authorization', 'Bearer ' . $token);
$request->headers->set('Accept', 'application/json');

$response = $app->handle($request);

$output = "Status: " . $response->getStatusCode() . "\r\n\r\n";
$output .= $response->getContent();

file_put_contents('api_output.txt', $output);
echo "Output saved to api_output.txt\r\n";


