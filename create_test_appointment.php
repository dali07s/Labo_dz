<?php
// Test reminder system
require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Setup Laravel components
$app = new Container();
$app->instance('app', $app);

$capsule = new Capsule($app);
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'labo.dz',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setEventDispatcher(new Dispatcher($app));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Create test data
echo "Creating test appointment...\n";

// Check if test patient already exists
$testPatient = Capsule::table('patients')->where('email', 'test@example.com')->first();

if (!$testPatient) {
    $patientId = Capsule::table('patients')->insertGetId([
        'name' => 'Test Patient',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'gender' => 'male',
        'birth_date' => '1990-01-01',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "Created test patient with ID: $patientId\n";
} else {
    $patientId = $testPatient->id;
    echo "Using existing test patient with ID: $patientId\n";
}

// Get first analysis
$analysis = Capsule::table('analyses')->first();
if (!$analysis) {
    die("No analyses found in database\n");
}

echo "Using analysis: {$analysis->name}\n";

// Create appointment for tomorrow (within 24 hours)
$appointmentDate = date('Y-m-d H:i:s', strtotime('+20 hours'));
$historyId = Capsule::table('histories')->insertGetId([
    'patient_id' => $patientId,
    'analyse_id' => $analysis->id,
    'analysis_date' => $appointmentDate,
    'time' => '10:00:00',
    'status' => 'confirmed',
    'result' => null,
    'created_at' => now(),
    'updated_at' => now()
]);

echo "Created test appointment with ID: $historyId\n";
echo "Appointment date: $appointmentDate\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";

// Create reminder record
$reminderDate = date('Y-m-d H:i:s', strtotime($appointmentDate . ' -1 day'));
$reminderId = Capsule::table('reminders')->insertGetId([
    'history_id' => $historyId,
    'patient_id' => $patientId,
    'analyse_id' => $analysis->id,
    'scheduled_for' => $reminderDate,
    'is_sent' => false,
    'created_at' => now(),
    'updated_at' => now()
]);

echo "Created reminder record with ID: $reminderId\n";
echo "Reminder scheduled for: $reminderDate\n";

echo "\n=== Test Setup Complete ===\n";
echo "Now run: php artisan reminders:send\n";
echo "Check if email is sent to: test@example.com\n";
