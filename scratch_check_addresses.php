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

echo "Extracted titles and addresses:\n";
foreach ($chunks as $idx => $chunk) {
    preg_match('/views-field-title.*?<strong class="field-content">(.*?)<\/strong>/s', $chunk, $nameMatch);
    $name = isset($nameMatch[1]) ? trim(strip_tags($nameMatch[1])) : "Unknown";

    preg_match('/views-field-field-address.*?<div class="field-content">(.*?)<\/div>/s', $chunk, $addressMatch);
    $address = isset($addressMatch[1]) ? trim(strip_tags($addressMatch[1])) : "None";

    echo ($idx + 1) . ". Name: $name\n   Address: $address\n\n";
}
