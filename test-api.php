<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = config('services.anthropic.key');
$response = Illuminate\Support\Facades\Http::withHeaders([
    'x-api-key' => $apiKey,
    'anthropic-version' => '2023-06-01',
    'content-type' => 'application/json',
])->post('https://api.anthropic.com/v1/messages', [
    'model' => 'claude-sonnet-4-6',
    'max_tokens' => 10,
    'messages' => [
        [
            'role' => 'user',
            'content' => 'Hola',
        ],
    ],
]);

echo 'Status: ' . $response->status() . PHP_EOL;
echo 'Body: ' . $response->body() . PHP_EOL;
