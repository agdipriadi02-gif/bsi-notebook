<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('GEMINI_API_KEY');

$models = ['gemini-1.5-flash', 'gemini-1.5-pro', 'gemini-pro'];

foreach ($models as $model) {
    echo "Testing model: $model\n";
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
        'contents' => [
            [
                'parts' => [
                    ['text' => 'Hi']
                ]
            ]
        ]
    ]);

    echo "Status: " . $response->status() . "\n";
    if ($response->successful()) {
        echo "SUCCESS!\n";
        break;
    } else {
        echo "Error: " . $response->body() . "\n\n";
    }
}
