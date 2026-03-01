<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.appointment_reminder') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 20px -30px;
        }
        .content {
            padding: 20px 0;
        }
        .appointment-details {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .important-note {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ffeaa7;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .highlight {
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('messages.lab_team') }}</h1>
            <h2>{{ __('messages.appointment_reminder') }}</h2>
        </div>

        <div class="content">
            <p>{{ __('messages.mr_mrs') }} <span class="highlight">{{ $patient->name }}</span>،</p>

            <p>{{ __('messages.appointment_scheduled_tomorrow') }}</p>

            <div class="appointment-details">
                <h3>{{ __('messages.appointment_details') }}:</h3>
                <p><strong>{{ __('messages.requested_analyses_list') }}:</strong></p>
                <ul>
                    @foreach($analyses as $analysis)
                        <li>{{ $analysis->name }}</li>
                    @endforeach
                </ul>
                <p><strong>{{ __('messages.date') }}:</strong> {{ $appointment_date }}</p>
                <p><strong>{{ __('messages.time') }}:</strong> {{ $appointment_time }}</p>
                @if($patient->phone)
                <p><strong>{{ __('messages.phone') }}:</strong> {{ $patient->phone }}</p>
                @endif
            </div>

            <div class="important-note">
                <h4>{{ __('messages.important_notes') }}:</h4>
                <ul>
                    <li>{{ __('messages.arrive_15_mins_early') }}</li>
                    <li>{{ __('messages.bring_id') }}</li>
                    <li>{{ __('messages.follow_prep_instructions') }}</li>
                    <li>{{ __('messages.inform_if_absent') }}</li>
                </ul>
            </div>

            <p>{{ __('messages.any_questions_contact_us') }}</p>
        </div>

        <div class="footer">
            <p>{{ __('messages.best_regards') }}<br>Labo.dz</p>
            <p>{{ __('messages.auto_generated_email') }}</p>
            <p>{{ __('messages.contact_us_at') }} {{ config('mail.from.address') }}</p>
        </div>
    </div>
</body>
</html>
