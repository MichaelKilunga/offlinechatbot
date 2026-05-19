<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$total = \App\Models\LegalAidProvider::count();
$nonEmpty = \App\Models\LegalAidProvider::whereNotNull('location')->where('location', '!=', '')->count();

echo "Total providers: $total\n";
echo "Providers with non-empty location: $nonEmpty\n\n";

if ($nonEmpty > 0) {
    echo "First 10 providers with location:\n";
    $providers = \App\Models\LegalAidProvider::whereNotNull('location')->where('location', '!=', '')->take(10)->get();
    foreach ($providers as $p) {
        echo "- ID {$p->id}: {$p->name} | Region: {$p->region} | District: {$p->district}\n  Location: {$p->location}\n";
    }
}
