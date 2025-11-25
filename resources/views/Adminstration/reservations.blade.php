@extends('Adminstration.layout')

@section('title', 'إدارة الحجوزات')

@section('content')
<div class="section-header">
    <h2><i class="fas fa-calendar-check"></i> إدارة الحجوزات</h2>
</div>

<!-- Filters -->
<div class="filters-container">
    <h3><i class="fas fa-filter"></i> تصفية الحجوزات</h3>
    <form method="GET" action="{{ route('filter.reservations') }}">
        <div class="filter-row">
            <div class="form-group">
                <label>من تاريخ</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>إلى تاريخ</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>حالة الحجز</label>
                <select name="status" class="form-control">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>منتهي</option>
                </select>
            </div>
            <div class="form-group">
                <label>بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث بالاسم أو الهاتف">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> تطبيق التصفية
        </button>
        <a href="{{ route('reservations') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> إلغاء
        </a>
    </form>
</div>

<!-- Results Table -->
<div class="table-container">
    @if($bookings->count() > 0)
        <div class="table-info">
            <p>عرض {{ $bookings->count() }} من أصل {{ $bookings->total() }} حجز</p>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>العميل</th>
                    <th>الهاتف</th>
                    <th>نوع التحليل</th>
                    <th>التاريخ</th>
                    <th>الوقت</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $loop->iteration + ($bookings->perPage() * ($bookings->currentPage() - 1)) }}</td>
                    <td>{{ $booking->patient->name }}</td>
                    <td>{{ $booking->patient->phone }}</td>
                    <td>{{ $booking->analyse->name }}</td>
                    <td>{{ $booking->analysis_date }}</td>
                    <td>{{ $booking->time }}</td>
                    <td>
                        <span class="status-badge status-{{ $booking->status }}">
                            @if($booking->status == 'pending')
                                قيد الانتظار
                            @elseif($booking->status == 'confirmed')
                                مؤكد
                            @else
                                منتهي
                            @endif
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="status-select">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>منتهي</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination-container">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    @else
        <div class="no-data">
            <i class="fas fa-calendar-times"></i>
            <p>لا توجد حجوزات تطابق معايير البحث</p>
            <a href="{{ route('reservations') }}" class="btn btn-primary">عرض جميع الحجوزات</a>
        </div>
    @endif
</div>

<style>
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-confirmed {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-select {
        padding: 4px 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 12px;
    }
    
    .no-data {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }
    
    .no-data i {
        font-size: 48px;
        margin-bottom: 16px;
    }
    
    .table-info {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 16px;
    }
</style>
@endsection