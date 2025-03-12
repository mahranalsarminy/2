@extends('layouts.admin')
@section('title', admin_lang('Edit Wallpaper'))
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ admin_lang('Edit Wallpaper') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.wallpapers.update', $wallpaper->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Title/Prompt') }} <span class="text-danger">*</span></label>
                            <input type="text" name="prompt" class="form-control" required value="{{ old('prompt', $wallpaper->prompt) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Tags') }}</label>
                            <input type="text" name="tags" class="form-control" value="{{ old('tags', $wallpaper->tags) }}">
                            <small class="text-muted">{{ admin_lang('Comma separated tags (e.g. nature, landscape, mountains)') }}</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Category') }}</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">{{ admin_lang('Select Category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (old('category_id', $wallpaper->category_id) == $category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Resolution') }} <span class="text-danger">*</span></label>
                                    <select name="resolution" class="form-select" required>
                                        <option value="3840x2160" {{ old('resolution', $wallpaper->resolution) == '3840x2160' ? 'selected' : '' }}>4K UHD (3840x2160)</option>
                                        <option value="2560x1440" {{ old('resolution', $wallpaper->resolution) == '2560x1440' ? 'selected' : '' }}>QHD (2560x1440)</option>
                                        <option value="1920x1080" {{ old('resolution', $wallpaper->resolution) == '1920x1080' ? 'selected' : '' }}>Full HD (1920x1080)</option>
                                        <option value="1366x768" {{ old('resolution', $wallpaper->resolution) == '1366x768' ? 'selected' : '' }}>HD (1366x768)</option>
                                        <option value="1280x720" {{ old('resolution', $wallpaper->resolution) == '1280x720' ? 'selected' : '' }}>HD (1280x720)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Visibility') }} <span class="text-danger">*</span></label>
                                    <select name="visibility" class="form-select" required>
                                        <option value="1" {{ old('visibility', $wallpaper->visibility) == '1' ? 'selected' : '' }}>{{ admin_lang('Public') }}</option>
                                        <option value="0" {{ old('visibility', $wallpaper->visibility) == '0' ? 'selected' : '' }}>{{ admin_lang('Private') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Color') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <input type="color" id="color-picker" value="{{ old('color', $wallpaper->color ?? '#ffffff') }}">
                                        </span>
                                        <input type="text" name="color" id="color-text" class="form-control" value="{{ old('color', $wallpaper->color ?? '#ffffff') }}">
                                    </div>
                                    <small class="text-muted">{{ admin_lang('Main color of the wallpaper (for color filtering)') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">{{ admin_lang('Wallpaper Preview') }}</h6>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ $wallpaper->getThumbnailLink() }}" alt="{{ $wallpaper->prompt }}" class="img-fluid img-thumbnail mb-3">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('wallpapers.show', hashid($wallpaper->id)) }}" target="_blank" class="btn btn-info">
                                        <i class="fas fa-eye me-1"></i> {{ admin_lang('View On Site') }}
                                    </a>
                                    <a href="{{ route('wallpapers.download', [hashid($wallpaper->id), $wallpaper->getMainImageName()]) }}" class="btn btn-success">
                                        <i class="fas fa-download me-1"></i> {{ admin_lang('Download') }}
                                    </a>
                                </div>
                                <div class="mt-3 text-muted small">
                                    <div><i class="fas fa-download me-1"></i> {{ admin_lang('Downloads') }}: {{ $wallpaper->downloads }}</div>
                                    <div><i class="fas fa-eye me-1"></i> {{ admin_lang('Views') }}: {{ $wallpaper->views }}</div>
                                    <div><i class="fas fa-calendar me-1"></i> {{ admin_lang('Added') }}: {{ $wallpaper->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary px-5">{{ admin_lang('Update Wallpaper') }}</button>
                    <a href="{{ route('admin.wallpapers.index') }}" class="btn btn-secondary px-5 ms-2">{{ admin_lang('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Color picker synchronization
            $('#color-picker').on('input', function() {
                $('#color-text').val($(this).val());
            });
            
            $('#color-text').on('input', function() {
                $('#color-picker').val($(this).val());
            });
        });
    </script>
    @endpush
@endsection