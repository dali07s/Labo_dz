@extends('Adminstration.layout')

@section('title', __('messages.edit_analysis'))

@section('content')
<div class="section-header">
    <h2><i class="fas fa-edit"></i> {{ __('messages.edit_analysis') }}</h2>
    <a href="{{ route('analyses') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right"></i> {{ __('messages.back') }}
    </a>
</div>

<div class="form-container">
    <form action="{{ route('analyses.update', $analysis->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group">
                <label>{{ __('messages.analysis_name') }} *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $analysis->getRawOriginal('name')) }}" required>
            </div>
            
            <div class="form-group">
                <label>{{ __('messages.analysis_name_fr') }}</label>
                <input type="text" name="name_fr" class="form-control" value="{{ old('name_fr', $analysis->name_fr) }}">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>{{ __('messages.price') }} ({{ __('messages.price_unit') ?? 'دج' }}) *</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $analysis->price) }}" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.normal_range') }}</label>
                <input type="text" name="normal_range" class="form-control" value="{{ old('normal_range', $analysis->normal_range) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>{{ __('messages.description') }} *</label>
                <textarea name="description" class="form-control" rows="3" required>{{ old('description', $analysis->getRawOriginal('description')) }}</textarea>
            </div>

            <div class="form-group">
                <label>{{ __('messages.description_fr') }}</label>
                <textarea name="description_fr" class="form-control" rows="3">{{ old('description_fr', $analysis->description_fr) }}</textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>{{ __('messages.duration') }} *</label>
                <input type="text" name="duration" class="form-control" value="{{ old('duration', $analysis->getRawOriginal('duration')) }}" placeholder="{{ __('messages.duration_placeholder') }}" required>
                <small class="form-text text-muted">{{ __('messages.duration_help') }}</small>
            </div>

            <div class="form-group">
                <label>{{ __('messages.duration_fr') }}</label>
                <input type="text" name="duration_fr" class="form-control" value="{{ old('duration_fr', $analysis->duration_fr) }}" placeholder="{{ __('messages.duration_placeholder') }}">
            </div>
        </div>

            <div class="form-group">
                <label>{{ __('messages.analysis_image') }}</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if($analysis->image)
                    <div class="current-image">
                        <img src="{{ Storage::disk('public')->url($analysis->image) }}" 
                             alt="{{ $analysis->name }}" 
                             style="width: 100px; height: 100px; object-fit: cover; margin-top: 10px; border-radius: 4px;">
                        <p class="image-note">{{ __('messages.current_image') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>{{ __('messages.preparation_instructions') }}</label>
                <textarea name="preparation_instructions" class="form-control" rows="3">{{ old('preparation_instructions', $analysis->getRawOriginal('preparation_instructions')) }}</textarea>
            </div>

            <div class="form-group">
                <label>{{ __('messages.preparation_instructions_fr') }}</label>
                <textarea name="preparation_instructions_fr" class="form-control" rows="3">{{ old('preparation_instructions_fr', $analysis->preparation_instructions_fr) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ __('messages.save_changes') }}
            </button>
            <a href="{{ route('analyses') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>


@endsection