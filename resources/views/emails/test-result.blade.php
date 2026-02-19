<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتيجة التحليل</title>
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
        .result-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .analysis-details {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .notes {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-right: 4px solid #ffc107;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('messages.lab_team') }}</h1>
            <h2>{{ __('messages.test_result_title') }}</h2>
        </div>
        
        <div class="content">
            <div class="result-info">
                <p><strong>{{ __('messages.patient_name') }}:</strong> {{ $patient->name }}</p>
                <p><strong>{{ __('messages.reservation_date') }}:</strong> {{ $reservation->analysis_date }}</p>
                <p><strong>{{ __('messages.reservation_time') }}:</strong> {{ $reservation->time }}</p>
            </div>
            
            <div class="analysis-details">
                <h3>{{ __('messages.included_analyses') }}:</h3>
                <ul>
                    @foreach($reservation->reservationAnalyses as $ra)
                        <li>
                            <strong>{{ $ra->analyse->name }}</strong>
                            @if($ra->analyse->normal_range)
                                <br><small>{{ __('messages.normal_range') }}: {{ $ra->analyse->normal_range }}</small>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            
            @if($additional_notes)
            <div class="notes">
                <h3>{{ __('messages.additional_notes') }}:</h3>
                <p>{{ $additional_notes }}</p>
            </div>
            @endif
            
            <div class="instructions">
                <h3>{{ __('messages.recommendations') }}:</h3>
                <p>• {{ __('messages.consult_doctor') }}</p>
                <p>• {{ __('messages.maintain_follow_up') }}</p>
                <p>• {{ __('messages.follow_doctor_instructions') }}</p>
            </div>
        </div>
        
        <div class="footer">
            <p>{{ __('messages.best_regards') }}<br>{{ __('messages.lab_team') }}</p>
            <p>{{ __('messages.auto_generated_email') }}</p>
        </div>
    </div>
</body>
</html>