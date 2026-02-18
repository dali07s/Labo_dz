@extends('Adminstration.layout')

@section('title', __('messages.dashboard'))

@section('content')
<div class="section-header">
    <h2><i class="fas fa-tachometer-alt"></i> {{ __('messages.overview') }}</h2>
    <span>{{ \Carbon\Carbon::now()->locale(app()->getLocale())->translatedFormat('l j F Y') }}</span>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <i class="fas fa-calendar-check"></i>
        <span class="stat-number">{{ $totalBookings }}</span>
        <span class="stat-label">{{ __('messages.total_bookings') }}</span>
    </div>
    <div class="stat-card">
        <i class="fas fa-user-clock"></i>
        <span class="stat-number">{{ $pendingBookings }}</span>
        <span class="stat-label">{{ __('messages.pending_bookings') }}</span>
    </div>
    <div class="stat-card">
        <i class="fas fa-flask"></i>
        <span class="stat-number">{{ $availableAnalyses }}</span>
        <span class="stat-label">{{ __('messages.available_analyses') }}</span>
    </div>
    <div class="stat-card">
        <i class="fas fa-users"></i>
        <span class="stat-number">{{ $totalPatients }}</span>
        <span class="stat-label">{{ __('messages.total_patients') }}</span>
    </div>
</div>

{{-- <div class="section-header">
    <h2><i class="fas fa-history"></i> أحدث الحجوزات</h2>
    <a href="{{ route('reservations') }}" class="btn btn-primary">
<i class="fas fa-list"></i> عرض الكل
</a>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>العميل</th>
                <th>الهاتف</th>
                <th>نوع التحليل</th>
                <th>التاريخ</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBookings as $booking)
            <tr>
                <td>{{ $booking->patient->name }}</td>
                <td>{{ $booking->patient->phone }}</td>
                <td>{{ $booking->analyse->name }}</td>
                <td>{{ $booking->analysis_date }}</td>
                <td>
                    <span class="status-badge status-{{ $booking->status }}">
                        {{ $booking->status == 'pending' ? 'قيد الانتظار' : 
                           ($booking->status == 'confirmed' ? 'مؤكد' : 
                           ($booking->status == 'completed' ? 'منتهي' : 'ملغي')) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">لا توجد حجوزات حديثة</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div> --}}
@endsection