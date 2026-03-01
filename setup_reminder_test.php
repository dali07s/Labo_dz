<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Analyse;
use App\Models\Patient;
use App\Models\Reservation;
use Carbon\Carbon;

try {
    // 1. Create Test Patient
    $patient = Patient::create([
        'name' => 'Test Reminder Patient',
        'email' => 'labo.dz@gmail.com',
        'phone' => '123456789',
        'gender' => 'male',
        'birth_date' => '1990-01-01',
    ]);
    echo "Created Patient ID: {$patient->id}\n";

    // 2. Get an analysis
    $analysis = Analyse::first();
    if (! $analysis) {
        throw new Exception('No analyses found in database.');
    }

    // 3. Create Reservation for tomorrow
    $tomorrow = Carbon::tomorrow();
    $reservation = Reservation::create([
        'patient_id' => $patient->id,
        'analysis_date' => $tomorrow->toDateString(),
        'time' => '09:00',
        'status' => 'booked',
    ]);
    echo "Created Reservation ID: {$reservation->id} for date: {$reservation->analysis_date}\n";

    // 4. Attach analysis
    $reservation->analyses()->attach($analysis->id, ['status' => 'booked']);
    echo "Attached Analysis ID: {$analysis->id} to Reservation.\n";

    echo "TEST_DATA_SETUP_SUCCESS:{$patient->id}:{$reservation->id}\n";
} catch (Exception $e) {
    echo 'TEST_DATA_SETUP_ERROR: '.$e->getMessage()."\n";
    exit(1);
}
