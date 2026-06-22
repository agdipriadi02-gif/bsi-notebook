<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('GEMINI_API_KEY');
echo "API Key: " . $apiKey . "\n";

$response = Http::withHeaders([
    'Content-Type' => 'application/json',
])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
    'contents' => [
        [
            'parts' => [
                ['text' => 'Halo, siapa kamu?']
            ]
        ]
    ]
]);

echo "Status: " . $response->status() . "\n";
echo "Response: " . $response->body() . "\n";
