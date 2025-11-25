@extends('Adminstration.layout')

@section('title', 'إدارة التحاليل')

@section('content')
<div class="section-header">
    <h2><i class="fas fa-flask"></i> إدارة التحاليل</h2>
    <a href="{{ route('analyses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة تحليل جديد
    </a>
</div>

<!-- Statistics Cards -->
<div class="stats-cards">
    <div class="analyses-stat-card">
        <div class="stat-icon">
            <i class="fas fa-flask"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $analyses->total() }}</h3>
            <p>إجمالي التحاليل</p>
        </div>
    </div>
    <div class="analyses-stat-card">
        <div class="stat-icon available">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>{{ App\Models\Analyse::where('availability', 1)->count() }}</h3>
            <p>التحاليل المتاحة</p>
        </div>
    </div>
    <div class="analyses-stat-card">
        <div class="stat-icon unavailable">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-info">
            <h3>{{ App\Models\Analyse::where('availability', 0)->count() }}</h3>
            <p>التحاليل غير المتاحة</p>
        </div>
    </div>
</div>

<!-- Analyses Table -->
<div class="table-container">
    @if($analyses->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الصورة</th>
                    <th>اسم التحليل</th>
                    <th>الوصف</th>
                    <th>السعر (دج)</th>
                    <th>المدة</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analyses as $analysis)
                <tr>
                    <td>{{ $loop->iteration + ($analyses->perPage() * ($analyses->currentPage() - 1)) }}</td>
                    <td>
                        @if($analysis->image)
                            <img src="{{ Storage::disk('public')->url($analysis->image) }}"
                                 alt="{{ $analysis->name }}"
                                 class="analysis-image">
                        @else
                            <div class="no-image">
                                <i class="fas fa-flask"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $analysis->name }}</td>
                    <td class="description-cell">
                        <span class="description-text">{{ Str::limit($analysis->description, 50) }}</span>
                        @if(strlen($analysis->description) > 50)
                            <button class="btn-show-more" onclick="toggleDescription(this)">عرض المزيد</button>
                        @endif
                    </td>
                    <td>{{ number_format($analysis->price, 0, ',', '.') }}</td>
                    <td>{{ $analysis->duration }}</td>
                    <td>
                        <form action="{{ route('analyses.toggle-availability', $analysis->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="status-toggle {{ $analysis->availability ? 'available' : 'unavailable' }}">
                                <i class="fas {{ $analysis->availability ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>

                                {{ $analysis->availability ? 'متاح' : 'غير متاح' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('analyses.edit', $analysis->id) }}" class="btn btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('analyses.destroy', $analysis->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا التحليل؟')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-container">
            {{ $analyses->links() }}
        </div>
    @else
        <div class="no-data">
            <i class="fas fa-flask"></i>
            <p>لا توجد تحاليل مضافة حتى الآن</p>
        </div>
    @endif
</div>

@endsection
