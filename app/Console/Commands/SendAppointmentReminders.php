<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\Reminder;
use App\Models\Patient;
use App\Models\Analyse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send appointment reminders to patients 24 hours before their analysis';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting appointment reminders process...');

        // Set locale to ensure translations are correct in emails
        app()->setLocale('fr');

        // Get reservations for tomorrow that haven't had a reminder sent yet
        $tomorrow = Carbon::tomorrow()->toDateString();
        $appointments = Reservation::with(['patient', 'analyses', 'reminders'])
            ->where('status', 'booked')
            ->where('analysis_date', $tomorrow)
            ->whereDoesntHave('reminders', function ($query) {
                $query->where('is_sent', true);
            })
            ->get();

        $this->info("Found {$appointments->count()} appointments within 24 hours");

        $sentCount = 0;
        $errorCount = 0;

        foreach ($appointments as $appointment) {
            try {
                // Check if reminder already exists
                $existingReminder = Reminder::where('reservation_id', $appointment->id)
                    ->where('patient_id', $appointment->patient_id)
                    ->first();

                if ($existingReminder && $existingReminder->is_sent) {
                    $this->info("Reminder already sent for reservation ID: {$appointment->id}");
                    continue;
                }

                // Create or update reminder record
                if (!$existingReminder) {
                    $reminder = Reminder::create([
                        'reservation_id' => $appointment->id,
                        'patient_id' => $appointment->patient_id,
                        'analyse_id' => $appointment->analyses->first()->id ?? 0, // Better fallback using relationship
                        'scheduled_for' => Carbon::parse($appointment->analysis_date)->subDay(),
                        'history_id' => null,
                        'is_sent' => false,
                    ]);
                } else {
                    $reminder = $existingReminder;
                }

                // Send email
                if ($appointment->patient->email) {
                    $analyses = $appointment->analyses;
                    
                    if ($analyses->isEmpty()) {
                        $this->warn("No analyses for reservation ID: {$appointment->id}. Skipping email.");
                        continue;
                    }

                    Mail::send('emails.appointment-reminder', [
                        'patient' => $appointment->patient,
                        'analyses' => $analyses,
                        'appointment_date' => Carbon::parse($appointment->analysis_date)->format('d/m/Y'),
                        'appointment_time' => $appointment->time,
                    ], function ($message) use ($appointment) {
                        $message->to($appointment->patient->email)
                                ->subject(__('messages.appointment_reminder') . ' - Labo.dz');
                    });

                    // Update reminder as sent
                    $reminder->update([
                        'is_sent' => true,
                        'sent_at' => now()
                    ]);

                    $sentCount++;
                    $this->info("Grouped reminder sent to: {$appointment->patient->name} ({$appointment->patient->email})");
                } else {
                    $this->warn("No email for patient: {$appointment->patient->name}");
                }

            } catch (Exception $e) {
                $errorCount++;
                Log::error('Failed to send reminder: ' . $e->getMessage(), [
                    'appointment_id' => $appointment->id,
                    'patient_id' => $appointment->patient_id
                ]);
                $this->error("Error sending reminder for appointment ID {$appointment->id}: " . $e->getMessage());

                // Update reminder with error
                if (isset($reminder)) {
                    $reminder->update([
                        'error_message' => $e->getMessage()
                    ]);
                }
            }
        }

        $this->info("Process completed. Sent: {$sentCount}, Errors: {$errorCount}");

        return Command::SUCCESS;
    }
}
