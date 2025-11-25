@extends('Adminstration.layout')

@section('title', 'إضافة تحليل جديد')

@section('content')
<div class="section-header">
    <h2><i class="fas fa-plus-circle"></i> إضافة تحليل جديد</h2>
    <a href="{{ route('analyses') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<div class="form-container">
    <form action="{{ route('analyses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>اسم التحليل *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>السعر (دج) *</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" min="0" step="0.01" required>
                @error('price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>الوصف *</label>
            <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>المعدل الطبيعي</label>
            <input type="text" name="normal_range" class="form-control" value="{{ old('normal_range') }}">
            @error('normal_range')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>مدة الانتظار *</label>
                <input type="text" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="مثال: 2 أيام، 3 ساعات، 30 دقيقة" required>
                <small class="form-text text-muted">أدخل المدة بالشكل الحر (أيام، ساعات، دقائق)</small>
                @error('duration')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>صورة التحليل</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>تعليمات التحضير</label>
            <textarea name="preparation_instructions" class="form-control" rows="3">{{ old('preparation_instructions') }}</textarea>
            @error('preparation_instructions')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> حفظ التحليل
            </button>
            <a href="{{ route('analyses') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>

@endsection
