<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Analyse;
use App\Models\Patient;
use App\Models\Reminder;
use App\Models\Reservation;
use Carbon\Carbon;

$logFile = 'reminder_debug.log';
file_put_contents($logFile, 'Debug started at: '.date('Y-m-d H:i:s')."\n");

try {
    // 1. Setup Test Data (if not exists)
    $patient = Patient::firstOrCreate(
        ['email' => 'test_reminder@example.com'],
        [
            'name' => 'Test Reminder Patient',
            'phone' => '123456789',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
        ]
    );
    file_put_contents($logFile, "Patient ID: {$patient->id}\n", FILE_APPEND);

    $tomorrow = Carbon::tomorrow()->toDateString();
    $reservation = Reservation::firstOrCreate(
        [
            'patient_id' => $patient->id,
            'analysis_date' => $tomorrow,
        ],
        [
            'time' => '09:00',
            'status' => 'booked',
        ]
    );
    file_put_contents($logFile, "Reservation ID: {$reservation->id}\n", FILE_APPEND);

    // Attach analysis if not attached
    if ($reservation->analyses()->count() == 0) {
        $analysis = Analyse::first();
        if ($analysis) {
            $reservation->analyses()->attach($analysis->id, ['status' => 'booked']);
            file_put_contents($logFile, "Attached analysis ID: {$analysis->id}\n", FILE_APPEND);
        }
    }

    // 2. Trigger Send Logic (Simulated)
    app()->setLocale('fr');
    $appointments = Reservation::with(['patient', 'analyses', 'reminders'])
        ->where('id', $reservation->id)
        ->get();

    file_put_contents($logFile, 'Found '.$appointments->count()." appointments for testing.\n", FILE_APPEND);

    foreach ($appointments as $appointment) {
        $analyses = $appointment->analyses;
        file_put_contents($logFile, "Processing appointment {$appointment->id} with ".$analyses->count()." analyses.\n", FILE_APPEND);

        // We won't actually send email (to avoid hanging on SMTP),
        // but we will check if the subject translation works.
        $subject = __('messages.appointment_reminder').' - Labo.dz';
        file_put_contents($logFile, "Translated Subject: {$subject}\n", FILE_APPEND);

        // Create reminder if not exists
        $reminder = Reminder::updateOrCreate(
            ['reservation_id' => $appointment->id],
            [
                'patient_id' => $appointment->patient_id,
                'analyse_id' => $analyses->first()->id ?? 0,
                'scheduled_for' => Carbon::parse($appointment->analysis_date)->subDay(),
                'is_sent' => true,
                'sent_at' => now(),
            ]
        );
        file_put_contents($logFile, "Reminder record updated/created ID: {$reminder->id}\n", FILE_APPEND);
    }

    file_put_contents($logFile, "Debug completed successfully.\n", FILE_APPEND);
    echo 'SUCCESS';
} catch (Exception $e) {
    file_put_contents($logFile, 'ERROR: '.$e->getMessage()."\n", FILE_APPEND);
    echo 'ERROR';
}
