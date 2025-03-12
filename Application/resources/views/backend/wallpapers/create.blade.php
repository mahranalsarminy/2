@extends('layouts.admin')
@section('title', admin_lang('Upload Wallpaper'))
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ admin_lang('Upload New Wallpaper') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.wallpapers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Title/Prompt') }} <span class="text-danger">*</span></label>
                            <input type="text" name="prompt" class="form-control" required value="{{ old('prompt') }}">
                            <small class="text-muted">{{ admin_lang('This will be displayed as the wallpaper title.') }}</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Tags') }}</label>
                            <input type="text" name="tags" class="form-control" value="{{ old('tags') }}">
                            <small class="text-muted">{{ admin_lang('Comma separated tags (e.g. nature, landscape, mountains)') }}</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Category') }}</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">{{ admin_lang('Select Category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                        <option value="">{{ admin_lang('Select Resolution') }}</option>
                                        <option value="3840x2160" {{ old('resolution') == '3840x2160' ? 'selected' : '' }}>4K UHD (3840x2160)</option>
                                        <option value="2560x1440" {{ old('resolution') == '2560x1440' ? 'selected' : '' }}>QHD (2560x1440)</option>
                                        <option value="1920x1080" {{ old('resolution') == '1920x1080' ? 'selected' : '' }}>Full HD (1920x1080)</option>
                                        <option value="1366x768" {{ old('resolution') == '1366x768' ? 'selected' : '' }}>HD (1366x768)</option>
                                        <option value="1280x720" {{ old('resolution') == '1280x720' ? 'selected' : '' }}>HD (1280x720)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Storage Provider') }} <span class="text-danger">*</span></label>
                                    <select name="storage_provider_id" class="form-select" required>
                                        @foreach($storageProviders as $provider)
                                            <option value="{{ $provider->id }}" {{ old('storage_provider_id') == $provider->id ? 'selected' : '' }}>
                                                {{ $provider->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Visibility') }} <span class="text-danger">*</span></label>
                                    <select name="visibility" class="form-select" required>
                                        <option value="1" {{ old('visibility') == '1' ? 'selected' : '' }}>{{ admin_lang('Public') }}</option>
                                        <option value="0" {{ old('visibility') == '0' ? 'selected' : '' }}>{{ admin_lang('Private') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ admin_lang('Color') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <input type="color" id="color-picker" value="#ffffff">
                                </span>
                                <input type="text" name="color" id="color-text" class="form-control" value="{{ old('color', '#ffffff') }}">
                            </div>
                            <small class="text-muted">{{ admin_lang('Main color of the wallpaper (for color filtering)') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">{{ admin_lang('Wallpaper Image') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ admin_lang('Upload Image') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="wallpaper" class="form-control" required accept="image/*">
                                    <small class="text-muted">{{ admin_lang('Max file size: 10MB. Supported formats: JPG, PNG, WebP.') }}</small>
                                </div>
                                <div id="image-preview-container" class="text-center mt-3" style="display: none;">
                                    <img id="image-preview" src="#" alt="Preview" class="img-thumbnail">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary px-5">{{ admin_lang('Upload Wallpaper') }}</button>
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
            
            // Image preview
            $('input[name="wallpaper"]').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result);
                        $('#image-preview-container').show();
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    @endpush
@endsection