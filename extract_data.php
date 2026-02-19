<?php

use App\Models\Analyse;
use App\Models\Option;
use App\Models\Question;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = [
    'analyses' => Analyse::all()->map(function ($a) {
        return [
            'id' => $a->id,
            'name' => $a->getRawOriginal('name'),
            'description' => $a->getRawOriginal('description'),
            'preparation_instructions' => $a->getRawOriginal('preparation_instructions'),
            'duration' => $a->getRawOriginal('duration'),
        ];
    }),
    'questions' => Question::all()->map(function ($q) {
        return [
            'id' => $q->id,
            'question' => $q->getRawOriginal('question'),
        ];
    }),
    'options' => Option::all()->map(function ($o) {
        return [
            'id' => $o->id,
            'text' => $o->getRawOriginal('text'),
        ];
    }),
];

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
