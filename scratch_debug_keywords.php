<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Str;

$question = "Kwa huduma za msaada wa kisheria katika Kata ya Makongo, Dar es Salaam";

$stopwords = [
    'huru', 'naomba', 'namba', 'simu', 'ngazi', 'kata', 'wilaya', 'mkoa', 'msaada', 'kisheria', 
    'nipo', 'tafadhali', 'kama', 'jinsi', 'habari', 'huduma', 'watoa', 'karibu', 'kupata',
    'ambao', 'ambayo', 'ambaye', 'yoyote', 'mwenye', 'kuwa', 'hiyo', 'hili', 'hili,',
    'mwananchi', 'wananchi', 'msalac', 'mama', 'samia', 'legal', 'aid', 'campaign',
    'kampeni', 'misaada', 'ofisi', 'ofisini', 'anwani', 'barua', 'pepe', 'email', 
    'phone', 'address', 'location', 'district', 'region', 'ward', 'street', 'please', 
    'find', 'get', 'near', 'nearby', 'help', 'support', 'organization', 'organisation',
    'foundation', 'centre', 'center', 'society', 'association', 'unit', 'paralegal', 'paralegals',
    'shukrani', 'asante', 'ndugu', 'mambo', 'vipi', 'salama', 'gani', 'upande', 'kupitia', 
    'njia', 'zifuatazo', 'mawasiliano', 'mwanasheria', 'mkuu', 'serikali', 'ofisi ya', 'ofisi za'
];

$words = array_filter(
    explode(' ', Str::lower(preg_replace('/[^a-z0-9 ]/i', '', $question))),
    fn($word) => strlen($word) > 2 && !in_array($word, $stopwords)
);

echo "All words: " . implode(', ', $words) . "\n";

$regions = [
    'dar', 'es', 'salaam', 'arusha', 'mwanza', 'dodoma', 'mbeya', 'tanga', 
    'morogoro', 'pwani', 'kigoma', 'shinyanga', 'singida', 'kilimanjaro', 
    'tanzania', 'zanzibar', 'pemba', 'unguja'
];

$specificWords = array_filter($words, fn($w) => !in_array($w, $regions));
echo "Specific words: " . implode(', ', $specificWords) . "\n";

$searchWords = !empty($specificWords) ? $specificWords : $words;
echo "Search words: " . implode(', ', $searchWords) . "\n";
