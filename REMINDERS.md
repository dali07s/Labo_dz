# Appointment Reminder System

## Overview
This system automatically sends email reminders to patients 24 hours before their medical analysis appointments.

## Features
- Automatic email reminders sent 24 hours before appointments
- Tracks sent reminders to prevent duplicates
- Scheduled execution via Laravel's task scheduler
- Comprehensive logging and error handling
- Arabic email templates with RTL support

## Components

### 1. Database Migration
- **Table**: `reminders`
- **Fields**: 
  - `history_id` (foreign key to histories table)
  - `patient_id` (foreign key to patients table)
  - `analyse_id` (foreign key to analyses table)
  - `scheduled_for` (when to send the reminder)
  - `sent_at` (when the reminder was sent)
  - `is_sent` (boolean flag)
  - `error_message` (for failed attempts)

### 2. Models
- **Reminder Model**: Handles reminder records and relationships
- **Scopes**: `pending()` and `readyToSend()` for easy querying

### 3. Console Command
- **Command**: `php artisan reminders:send`
- **Schedule**: Runs hourly via Laravel scheduler
- **Logic**: 
  - Finds confirmed appointments within 24 hours
  - Checks if reminder already exists/sent
  - Sends email if patient has email address
  - Updates reminder record with status

### 4. Email Template
- **File**: `resources/views/emails/appointment-reminder.blade.php`
- **Features**: 
  - Arabic RTL design
  - Patient appointment details
  - Important notes and instructions
  - Professional styling

## Setup Instructions

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Configure Email Settings
Update your `.env` file with proper email configuration:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@labo-dz.com
MAIL_FROM_NAME="مخبر ورقلة"
```

### 3. Configure Task Scheduling
The command is automatically scheduled to run hourly. Make sure your server's cron is configured:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Usage

### Manual Testing
```bash
# Test the reminder system
php artisan reminders:send

# Test with verbose output
php artisan reminders:send -v
```

### Automatic Operation
The system runs automatically every hour and:
1. Finds all confirmed appointments within 24 hours
2. Checks if reminders have already been sent
3. Sends emails to patients with valid email addresses
4. Updates reminder records with sent status
5. Logs any errors for troubleshooting

## Customization

### Change Reminder Timing
In `app/Console/Commands/SendAppointmentReminders.php`, modify:
```php
// Current: 24 hours before
'scheduled_for' => $appointment->analysis_date->subDay()

// Example: 48 hours before
'scheduled_for' => $appointment->analysis_date->subDays(2)
```

### Change Schedule Frequency
In `app/Console/Kernel.php`:
```php
// Hourly (current)
$schedule->command('reminders:send')->hourly();

// Twice daily
$schedule->command('reminders:send')->twiceDaily(9, 17);

// Every 30 minutes
$schedule->command('reminders:send')->everyThirtyMinutes();
```

### Email Template Customization
Edit `resources/views/emails/appointment-reminder.blade.php` to:
- Modify the design and styling
- Change the content and language
- Add/remove sections
- Update the footer information

## Monitoring

### Check Reminder Status
```sql
-- View pending reminders
SELECT * FROM reminders WHERE is_sent = 0;

-- View recently sent reminders
SELECT * FROM reminders WHERE is_sent = 1 ORDER BY sent_at DESC LIMIT 10;

-- View failed reminders
SELECT * FROM reminders WHERE error_message IS NOT NULL;
```

### Logs
Check Laravel logs for any errors:
- `storage/logs/laravel.log`
- Look for entries containing "Failed to send reminder"

## Troubleshooting

### Common Issues

1. **Emails not sending**
   - Check email configuration in `.env`
   - Verify SMTP credentials
   - Check if patients have email addresses

2. **Reminders sending multiple times**
   - Check the `is_sent` flag in reminders table
   - Verify the duplicate check logic in the command

3. **Scheduling not working**
   - Ensure cron is configured properly
   - Check `php artisan schedule:list` to verify scheduling
   - Test with `php artisan schedule:run` manually

4. **Timezone issues**
   - Ensure your application timezone is correct
   - Check that appointment dates are stored properly

## Security Notes
- Patient email addresses are handled securely
- Reminder records don't store sensitive medical information
- All email sending is logged for audit purposes
- The system respects patient privacy and data protection

## Future Enhancements
- SMS reminders in addition to email
- Customizable reminder timing per patient
- Reminder templates in multiple languages
- Integration with calendar systems
- Patient preference settings for communication methods
