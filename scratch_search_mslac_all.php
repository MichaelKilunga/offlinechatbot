<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$keywords = ['saranga', 'makongo', 'madale', 'ubungo', 'kinondoni', 'temeke', 'ilala', 'kigamboni', 'dar es salaam'];
$matches = [];

echo "Searching all 16 pages for keywords: " . implode(', ', $keywords) . "\n\n";

for ($page = 0; $page < 16; $page++) {
    $url = "https://www.mslac.or.tz/en/legal-aid-providers?page={$page}";
    echo "Fetching page " . ($page + 1) . "...\n";
    try {
        $response = Http::withoutVerifying()->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ])->get($url);
        
        if (!$response->successful()) continue;
        
        $html = $response->body();
        $chunks = explode('class="item-columns"', $html);
        array_shift($chunks);
        
        foreach ($chunks as $chunk) {
            preg_match('/views-field-title.*?<strong class="field-content">(.*?)<\/strong>/s', $chunk, $nameMatch);
            $name = isset($nameMatch[1]) ? trim(strip_tags($nameMatch[1])) : "Unknown";

            preg_match('/views-field-field-address.*?<div class="field-content">(.*?)<\/div>/s', $chunk, $addressMatch);
            $address = isset($addressMatch[1]) ? trim(strip_tags($addressMatch[1])) : "None";

            preg_match('/views-field-field-region.*?<div class="field-content">(.*?)<\/div>/s', $chunk, $regionMatch);
            $region = isset($regionMatch[1]) ? trim(strip_tags($regionMatch[1])) : "None";

            $fullText = strtolower($name . ' ' . $address . ' ' . $region);
            
            foreach ($keywords as $keyword) {
                if (str_contains($fullText, $keyword)) {
                    $matches[$keyword][] = [
                        'name' => $name,
                        'region' => $region,
                        'address' => $address,
                        'page' => $page + 1
                    ];
                }
            }
        }
    } catch (\Throwable $e) {
        echo "Error on page " . ($page + 1) . ": " . $e->getMessage() . "\n";
    }
}

echo "\n--- SEARCH RESULTS ---\n";
foreach ($keywords as $keyword) {
    $count = isset($matches[$keyword]) ? count($matches[$keyword]) : 0;
    echo "Keyword '$keyword': found $count matches\n";
    if ($count > 0) {
        foreach ($matches[$keyword] as $m) {
            echo "  - [Page {$m['page']}] {$m['name']} (Mkoa: {$m['region']})\n    Address: {$m['address']}\n";
        }
    }
    echo "\n";
}
