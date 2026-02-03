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

// Create test request
$requestId = Capsule::table('request_reservations')->insertGetId([
    'name' => 'Test User 2',
    'email' => 'test2@example.com',
    'phone' => '0555123456',
    'gender' => 'female',
    'birth_date' => '1995-05-15',
    'analyse_id' => $analysis->id,
    'preferred_date' => date('Y-m-d', strtotime('+2 days')),
    'preferred_time' => '14:30:00',
    'status' => 'pending',
    'created_at' => now(),
    'updated_at' => now()
]);

echo "Created test request with ID: $requestId\n";
echo "Total pending requests: " . Capsule::table('request_reservations')->where('status', 'pending')->count() . "\n";
