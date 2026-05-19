<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\PromptEngine;

$engine = new PromptEngine();

$queries = [
    // 1. Makongo
    "Kwa huduma za msaada wa kisheria katika Kata ya Makongo, Dar es Salaam",
    
    // 2. Saranga (should match Ubungo/Goba since Saranga is in Ubungo, or return general if Ubungo is not in query)
    "HURU naomba namba za simu samia Legal Aid ngazi ya kata ya Saranga",
    
    // 3. Goba
    "naomba msaada wa kisheria nipo Goba",
    
    // 4. Madale (should not match exactly, falls back nicely)
    "HURU nimedhurumiwa ardhi naomba msaada wa kisheria nipo Madale"
];

echo "=== VERIFYING DYNAMIC LOCAL PROVIDERS LOOKUP ===\n\n";

foreach ($queries as $idx => $query) {
    echo "Query " . ($idx + 1) . ": \"$query\"\n";
    
    // Call the private findLocalProviders via Reflection to see what DB rows it returned
    $reflector = new ReflectionClass(PromptEngine::class);
    $method = $reflector->getMethod('findLocalProviders');
    $method->setAccessible(true);
    
    $providers = $method->invoke($engine, $query);
    
    echo "Matched " . $providers->count() . " providers in DB:\n";
    if ($providers->isNotEmpty()) {
        foreach ($providers as $p) {
            echo "  - ID {$p->id}: {$p->name}\n";
            echo "    Region: {$p->region} | District: {$p->district}\n";
            echo "    Location: {$p->location}\n";
        }
    } else {
        echo "  (No local providers matched - will fallback to national LHRC/TLS)\n";
    }
    echo "----------------------------------------\n\n";
}
