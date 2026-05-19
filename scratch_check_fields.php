<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$url = "https://www.mslac.or.tz/en/legal-aid-providers?page=0";
$response = Http::withoutVerifying()->withHeaders([
    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
])->get($url);

$html = $response->body();
$chunks = explode('class="item-columns"', $html);
array_shift($chunks);

echo "Checking classes/fields in first page cards:\n";
foreach ($chunks as $idx => $chunk) {
    preg_match_all('/class="([^"]+)"/', $chunk, $matches);
    $classes = array_unique($matches[1]);
    $viewsFields = array_filter($classes, fn($c) => str_contains($c, 'views-field'));
    echo "Card " . ($idx + 1) . " fields: " . implode(', ', $viewsFields) . "\n";
}
