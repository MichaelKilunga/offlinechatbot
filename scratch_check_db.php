<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$count = \App\Models\LegalAidProvider::count();
echo "Total providers: $count\n";

if ($count > 0) {
    echo "First 10 providers in DB:\n";
    $providers = \App\Models\LegalAidProvider::take(10)->get();
    foreach ($providers as $provider) {
        echo "- ID {$provider->id}: {$provider->name} | Region: {$provider->region} | District: {$provider->district} | Location: {$provider->location} | Phone: {$provider->phone}\n";
    }
} else {
    echo "No providers found in the database.\n";
}
