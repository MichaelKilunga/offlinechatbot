<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$names = ['BODABODA', 'ENVIROCARE'];

foreach ($names as $name) {
    echo "Searching for name containing '$name':\n";
    $providers = \App\Models\LegalAidProvider::where('name', 'LIKE', '%' . $name . '%')->get();
    foreach ($providers as $p) {
        echo "- ID {$p->id}: {$p->name}\n  Region: {$p->region} | District: {$p->district}\n  Location: {$p->location}\n";
    }
    echo "\n";
}
