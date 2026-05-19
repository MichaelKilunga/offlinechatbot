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

echo "First 5 card HTML:\n";
for ($i = 0; $i < min(5, count($chunks)); $i++) {
    echo "--- CARD " . ($i + 1) . " ---\n";
    // Find the end of the card div. Usually it's balanced, but let's take a reasonable portion.
    echo trim(substr($chunks[$i], 0, 1000)) . "\n\n";
}
