@extends('Adminstration.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>طلبات الحجز</h2>
        <a href="{{ route('reservations') }}" class="btn btn-secondary">الحجوزات المؤكدة</a>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>تصفية الطلبات</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reservation.requests') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">الحالة</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">البحث بالاسم أو الهاتف</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="أدخل الاسم أو رقم الهاتف">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">تصفية</button>
                    <a href="{{ route('reservation.requests') }}" class="btn btn-outline-secondary">إعادة تعيين</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card">
        <div class="card-header">
            <h5>قائمة طلبات الحجز ({{ $requests->total() }} طلب)</h5>
        </div>
        <div class="card-body">
            @if($requests->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الهاتف</th>
                            <th>البريد الإلكتروني</th>
                            <th>الجنس</th>
                            <th>تاريخ الميلاد</th>
                            <th>نوع التحليل</th>
                            <th>التاريخ المفضل</th>
                            <th>الوقت المفضل</th>
                            <th>الحالة</th>
                            <th>تاريخ الطلب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $requestItem)
                        <tr>
                            <td>{{ $requestItem->name }}</td>
                            <td>{{ $requestItem->phone }}</td>
                            <td>{{ $requestItem->email ?? 'غير متوفر' }}</td>
                            <td>{{ $requestItem->gender == 'male' ? 'ذكر' : ($requestItem->gender == 'female' ? 'أنثى' : 'غير محدد') }}</td>
                            <td>{{ $requestItem->birth_date ? \Carbon\Carbon::parse($requestItem->birth_date)->format('Y-m-d') : 'غير محدد' }}</td>
                            <td>{{ $requestItem->analyse->name ?? 'غير متوفر' }}</td>
                            <td>{{ $requestItem->preferred_date ? \Carbon\Carbon::parse($requestItem->preferred_date)->format('Y-m-d') : 'غير محدد' }}</td>
                            <td>{{ $requestItem->preferred_time ? substr($requestItem->preferred_time, 0, 5) : 'غير محدد' }}</td>
                            <td>
                                @if($requestItem->status == 'pending')
                                <span class="badge bg-warning">معلق</span>
                                @elseif($requestItem->status == 'confirmed')
                                <span class="badge bg-success">مؤكد</span>
                                @elseif($requestItem->status == 'rejected')
                                <span class="badge bg-danger">مرفوض</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($requestItem->created_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                @if($requestItem->status == 'pending')
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal{{ $requestItem->id }}">
                                    تأكيد
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $requestItem->id }}">
                                    رفض
                                </button>
                                @else
                                @if($requestItem->admin_notes)
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#notesModal{{ $requestItem->id }}">
                                    ملاحظات المشرف
                                </button>
                                @endif
                                @if($requestItem->patient_id)
                                <a href="{{ route('reservations') }}?search={{ $requestItem->name }}" class="btn btn-primary btn-sm">
                                    عرض الحجز
                                </a>
                                @endif
                                @endif
                            </td>
                        </tr>

                        <!-- Confirm Modal -->
                        @if($requestItem->status == 'pending')
                        <div class="modal fade" id="confirmModal{{ $requestItem->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">تأكيد طلب الحجز</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('reservation.requests.confirm', $requestItem->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">اسم المريض: {{ $requestItem->name }}</label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">نوع التحليل: {{ $requestItem->analyse->name ?? 'غير متوفر' }}</label>
                                            </div>
                                            <div class="mb-3">
                                                <label for="analysis_date{{ $requestItem->id }}" class="form-label">تاريخ التحليل *</label>
                                                <input type="date" name="analysis_date" id="analysis_date{{ $requestItem->id }}" class="form-control"
                                                    value="{{ $requestItem->preferred_date ? \Carbon\Carbon::parse($requestItem->preferred_date)->format('Y-m-d') : date('Y-m-d') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="time{{ $requestItem->id }}" class="form-label">وقت التحليل *</label>
                                                <input type="time" name="time" id="time{{ $requestItem->id }}" class="form-control"
                                                    value="{{ $requestItem->preferred_time ? substr($requestItem->preferred_time, 0, 5) : '09:00' }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="admin_notes{{ $requestItem->id }}" class="form-label">ملاحظات المشرف (اختياري)</label>
                                                <textarea name="admin_notes" id="admin_notes{{ $requestItem->id }}" class="form-control" rows="3" placeholder="ملاحظات إضافية..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                            <button type="submit" class="btn btn-success">تأكيد الحجز</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $requestItem->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">رفض طلب الحجز</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('reservation.requests.reject', $requestItem->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">هل أنت متأكد من رفض طلب الحجز لـ {{ $requestItem->name }}؟</label>
                                            </div>
                                            <div class="mb-3">
                                                <label for="reject_notes{{ $requestItem->id }}" class="form-label">سبب الرفض *</label>
                                                <textarea name="admin_notes" id="reject_notes{{ $requestItem->id }}" class="form-control" rows="3" required
                                                    placeholder="يرجى ذكر سبب الرفض"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                            <button type="submit" class="btn btn-danger">رفض الطلب</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Notes Modal -->
                        @if($requestItem->admin_notes)
                        <div class="modal fade" id="notesModal{{ $requestItem->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">ملاحظات المشرف</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ $requestItem->admin_notes }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $requests->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <h4>لا توجد طلبات حجز</h4>
                <p class="text-muted">لم يتم تقديم أي طلبات حجز بعد.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection