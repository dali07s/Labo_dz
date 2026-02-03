<?php
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

// Get first analysis
$analysis = Capsule::table('analyses')->first();
if (!$analysis) {
    die("No analyses found\n");
}

echo "Using analysis: {$analysis->name}\n";

// Create test patient with the new email
$patientId = Capsule::table('patients')->insertGetId([
    'name' => 'Test Patient',
    'email' => 'pocomerzoug@gmail.com',
    'phone' => '0555987654',
    'gender' => 'male',
    'birth_date' => '1985-03-20',
    'created_at' => now(),
    'updated_at' => now()
]);

// Create appointment for tomorrow (within 24 hours)
$appointmentDate = date('Y-m-d H:i:s', strtotime('+1 day'));
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

// Create reminder record for today (should trigger immediately)
$reminderId = Capsule::table('reminders')->insertGetId([
    'history_id' => $historyId,
    'patient_id' => $patientId,
    'analyse_id' => $analysis->id,
    'scheduled_for' => now(),
    'is_sent' => false,
    'created_at' => now(),
    'updated_at' => now()
]);

echo "Created test appointment for pocomerzoug@gmail.com\n";
echo "Patient ID: $patientId\n";
echo "History ID: $historyId\n";
echo "Reminder ID: $reminderId\n";
echo "Appointment date: $appointmentDate\n";
