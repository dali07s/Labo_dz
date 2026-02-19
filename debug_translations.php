<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Analyse;
use App\Models\Question;

echo "--- ANALYSES ---\n";
foreach (Analyse::all() as $a) {
    echo "ID: {$a->id} | AR: {$a->getRawOriginal('name')} | FR: {$a->name_fr}\n";
}

echo "\n--- QUESTIONS ---\n";
foreach (Question::all() as $q) {
    echo "ID: {$q->id} | AR: {$q->getRawOriginal('question')} | FR: {$q->question_fr}\n";
}

echo "\n--- LOCALE CHECK ---\n";
echo "Current Locale: " . app()->getLocale() . "\n";

app()->setLocale('fr');
echo "Locale set to 'fr'. Testing Analysis 2 name accessor...\n";
$analysis = Analyse::find(2);
if ($analysis) {
    echo "Analysis 2 name: " . $analysis->name . "\n";
}
