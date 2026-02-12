<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>تأكيد حجز موعد - مخبر المنيعة</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 12pt;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 24pt;
            margin: 0 0 10px 0;
        }

        .header p {
            color: #7f8c8d;
            font-size: 11pt;
            margin: 5px 0;
        }

        .document-title {
            background-color: #3498db;
            color: white;
            padding: 12px;
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 20px 0;
            border-radius: 5px;
        }

        .section {
            margin: 25px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-right: 4px solid #3498db;
        }

        .section-title {
            color: #2c3e50;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e0e0;
        }

        .info-row {
            margin: 10px 0;
            padding: 8px;
            background-color: white;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            width: 150px;
        }

        .info-value {
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background-color: white;
        }

        table th {
            background-color: #3498db;
            color: white;
            padding: 12px;
            text-align: right;
            font-weight: bold;
            font-size: 11pt;
        }

        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
            text-align: right;
        }

        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .preparation {
            background-color: #fff3cd;
            padding: 10px;
            border-right: 3px solid #ffc107;
            margin: 8px 0;
            font-size: 10pt;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            font-size: 10pt;
            color: #7f8c8d;
        }

        .footer-info {
            margin: 5px 0;
        }

        .important-note {
            background-color: #e8f5e9;
            border: 2px solid #4caf50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .important-note strong {
            color: #2e7d32;
            font-size: 11pt;
        }

        .price {
            color: #27ae60;
            font-weight: bold;
        }

        .total-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #2c3e50;
            color: white;
            text-align: center;
            border-radius: 5px;
        }

        .total-section .total-label {
            font-size: 14pt;
            margin-bottom: 5px;
        }

        .total-section .total-amount {
            font-size: 18pt;
            font-weight: bold;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <h1>مخبر المنيعة</h1>
        <p>مخبر تحليلات طبية متخصص</p>
        <p>Labo_dz - Laboratory of Medical Analysis</p>
    </div>

    {{-- Document Title --}}
    <div class="document-title">
        تأكيد طلب حجز موعد
    </div>

    {{-- Patient Information --}}
    <div class="section">
        <div class="section-title">معلومات المريض</div>
        <div class="info-row">
            <span class="info-label">الاسم الكامل:</span>
            <span class="info-value">{{ $reservation->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">رقم الهاتف:</span>
            <span class="info-value">{{ $reservation->phone }}</span>
        </div>
        @if($reservation->email)
        <div class="info-row">
            <span class="info-label">البريد الإلكتروني:</span>
            <span class="info-value">{{ $reservation->email }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">الجنس:</span>
            <span class="info-value">{{ $reservation->gender == 'male' ? 'ذكر' : 'أنثى' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">تاريخ الميلاد:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($reservation->birth_date)->format('Y/m/d') }}</span>
        </div>
    </div>

    {{-- Appointment Information --}}
    <div class="section">
        <div class="section-title">معلومات الموعد المفضل</div>
        <div class="info-row">
            <span class="info-label">التاريخ المفضل:</span>
            <span class="info-value">{{ $reservation->preferred_date ? \Carbon\Carbon::parse($reservation->preferred_date)->format('Y/m/d') : 'لم يحدد' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">الوقت المفضل:</span>
            <span class="info-value">{{ $reservation->preferred_time ? \Carbon\Carbon::parse($reservation->preferred_time)->format('H:i') : 'لم يحدد' }}</span>
        </div>
    </div>

    {{-- Analysis Information --}}
    <div class="section">
        <div class="section-title">التحاليل المطلوبة</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 30%">اسم التحليل</th>
                    <th style="width: 40%">الوصف</th>
                    <th style="width: 15%">المدة</th>
                    <th style="width: 10%">السعر</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($reservation->analyses as $index => $analysis)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $analysis->name }}</strong></td>
                    <td>{{ $analysis->description ?? 'لا يوجد وصف' }}</td>
                    <td>{{ $analysis->duration ?? 'غير محدد' }}</td>
                    <td class="price">{{ number_format($analysis->price, 2) }} دج</td>
                </tr>
                @php $total += $analysis->price; @endphp
                @endforeach
            </tbody>
        </table>

        {{-- Total Price --}}
        @if(count($reservation->analyses) > 1)
        <div class="total-section">
            <div class="total-label">المجموع الإجمالي</div>
            <div class="total-amount">{{ number_format($total, 2) }} دج</div>
        </div>
        @endif
    </div>

    {{-- Preparation Instructions --}}
    @php
    $hasPreparation = $reservation->analyses->filter(function($analysis) {
    return !empty($analysis->preparation_instructions);
    })->count() > 0;
    @endphp

    @if($hasPreparation)
    <div class="section">
        <div class="section-title">تعليمات التحضير للتحاليل</div>
        <div class="important-note">
            <strong>مهم جداً:</strong> يرجى اتباع التعليمات التالية بدقة للحصول على نتائج دقيقة
        </div>

        @foreach($reservation->analyses as $analysis)
        @if($analysis->preparation_instructions)
        <div class="preparation">
            <strong>{{ $analysis->name }}:</strong><br>
            {{ $analysis->preparation_instructions }}
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- Important Notes --}}
    <div class="section">
        <div class="section-title">ملاحظات هامة</div>
        <div style="padding: 10px; line-height: 1.8;">
            <p>• هذا الطلب في حالة <strong>قيد الانتظار</strong> وسيتم الاتصال بك لتأكيد الموعد النهائي</p>
            <p>• يرجى إحضار هذه الوثيقة عند الحضور إلى المخبر</p>
            <p>• في حالة وجود أي استفسارات، يرجى الاتصال بنا على الأرقام الموضحة أدناه</p>
            <p>• يرجى الحضور قبل 15 دقيقة من الموعد المحدد</p>
            <p>• في حالة الرغبة في إلغاء أو تعديل الموعد، يرجى الاتصال بنا قبل 24 ساعة على الأقل</p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-info"><strong>العنوان:</strong> شارع الاستقلال، المنيعة</div>
        <div class="footer-info"><strong>الهاتف:</strong> 0550123456</div>
        <div class="footer-info"><strong>البريد الإلكتروني:</strong> info@labo-dz.com</div>
        <div class="footer-info"><strong>أوقات العمل:</strong> 8:00 صباحاً - 6:00 مساءً</div>
        <div style="margin-top: 15px; color: #95a5a6; font-size: 9pt;">
            تم إصدار هذه الوثيقة بتاريخ {{ \Carbon\Carbon::now()->format('Y/m/d H:i') }}
        </div>
    </div>
</body>

</html>