<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('GEMINI_API_KEY');

$response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");

$data = $response->json();
foreach ($data['models'] as $m) {
    if (strpos($m['name'], '1.5-flash') !== false) {
        echo $m['name'] . "\n";
    }
}
