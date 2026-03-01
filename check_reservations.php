<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;
use Carbon\Carbon;

$tomorrow = Carbon::tomorrow()->toDateString();
$count = Reservation::where('analysis_date', $tomorrow)->count();
$all_count = Reservation::count();
$booked_count = Reservation::where('status', 'booked')->count();

echo "Tomorrow: $tomorrow\n";
echo "Total Reservations: $all_count\n";
echo "Booked Reservations: $booked_count\n";
echo "Reservations for tomorrow: $count\n";

if ($count > 0) {
    $res = Reservation::where('analysis_date', $tomorrow)->with('patient')->first();
    echo "First reservation for tomorrow: " . json_encode($res) . "\n";
}
