@extends('Adminstration.layout')

@section('title', 'تعديل التحليل')

@section('content')
<div class="section-header">
    <h2><i class="fas fa-edit"></i> تعديل التحليل</h2>
    <a href="{{ route('analyses') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<div class="form-container">
    <form action="{{ route('analyses.update', $analysis->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group">
                <label>اسم التحليل *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $analysis->name) }}" required>
            </div>
            
            <div class="form-group">
                <label>السعر (دج) *</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $analysis->price) }}" min="0" step="0.01" required>
            </div>
        </div>

        <div class="form-group">
            <label>الوصف *</label>
            <textarea name="description" class="form-control" rows="3" required>{{ old('description', $analysis->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>المعدل الطبيعي</label>
            <input type="text" name="normal_range" class="form-control" value="{{ old('normal_range', $analysis->normal_range) }}">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>مدة الانتظار *</label>
                <input type="text" name="duration" class="form-control" value="{{ old('duration', $analysis->duration) }}" placeholder="مثال: 2 أيام، 3 ساعات، 30 دقيقة" required>
                <small class="form-text text-muted">أدخل المدة بالشكل الحر (أيام، ساعات، دقائق)</small>
            </div>

            <div class="form-group">
                <label>صورة التحليل</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if($analysis->image)
                    <div class="current-image">
                        <img src="{{ Storage::disk('public')->url($analysis->image) }}" 
                             alt="{{ $analysis->name }}" 
                             style="width: 100px; height: 100px; object-fit: cover; margin-top: 10px; border-radius: 4px;">
                        <p class="image-note">الصورة الحالية</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label>تعليمات التحضير</label>
            <textarea name="preparation_instructions" class="form-control" rows="3">{{ old('preparation_instructions', $analysis->preparation_instructions) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> حفظ التغييرات
            </button>
            <a href="{{ route('analyses') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>


@endsection