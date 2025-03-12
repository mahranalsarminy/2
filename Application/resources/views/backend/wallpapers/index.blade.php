@extends('layouts.admin')
@section('title', admin_lang('Wallpapers'))
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ admin_lang('All Wallpapers') }}</h5>
            <a href="{{ route('admin.wallpapers.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-2"></i>{{ admin_lang('Upload New Wallpaper') }}
            </a>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('admin.wallpapers.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="{{ admin_lang('Search...') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">{{ admin_lang('All Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ admin_lang('Newest First') }}</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ admin_lang('Oldest First') }}</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ admin_lang('Most Downloads') }}</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>{{ admin_lang('Most Views') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">{{ admin_lang('Filter') }}</button>
                    </div>
                </div>
            </form>
            
            <!-- Batch Actions -->
            <form action="{{ route('admin.wallpapers.batch') }}" method="POST" id="batch-form">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <select name="action" class="form-select" id="batch-action">
                            <option value="">{{ admin_lang('Bulk Actions') }}</option>
                            <option value="delete">{{ admin_lang('Delete Selected') }}</option>
                            <option value="category">{{ admin_lang('Change Category') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="category-select" style="display: none;">
                        <select name="category_id" class="form-select">
                            <option value="">{{ admin_lang('Select Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary w-100" id="apply-btn" disabled>{{ admin_lang('Apply') }}</button>
                    </div>
                </div>
                
                <!-- Wallpapers Grid -->
                <div class="row g-3">
                    @foreach($wallpapers as $wallpaper)
                        <div class="col-md-3 col-sm-6">
                            <div class="card h-100">
                                <div class="position-relative">
                                    <div class="form-check position-absolute top-0 start-0 m-2">
                                        <input class="form-check-input wallpaper-checkbox" type="checkbox" name="ids[]" value="{{ $wallpaper->id }}">
                                    </div>
                                    <img src="{{ $wallpaper->getThumbnailLink() }}" class="card-img-top" alt="{{ $wallpaper->prompt }}">
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title text-truncate" data-bs-toggle="tooltip" title="{{ $wallpaper->prompt }}">
                                        {{ $wallpaper->prompt }}
                                    </h6>
                                    <div class="d-flex flex-column gap-1 text-muted small">
                                        <div>
                                            <i class="fas fa-download me-1"></i> {{ $wallpaper->downloads }}
                                            <i class="fas fa-eye ms-2 me-1"></i> {{ $wallpaper->views }}
                                        </div>
                                        <div>
                                            <i class="fas fa-th me-1"></i> {{ $wallpaper->resolution }}
                                        </div>
                                        <div>
                                            <i class="fas fa-folder me-1"></i>
                                            @if($wallpaper->category)
                                                {{ $wallpaper->category->name }}
                                            @else
                                                {{ admin_lang('Uncategorized') }}
                                            @endif
                                        </div>
                                        <div>
                                            <i class="fas fa-calendar me-1"></i> {{ $wallpaper->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.wallpapers.edit', $wallpaper->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('wallpapers.show', hashid($wallpaper->id)) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.wallpapers.destroy', $wallpaper->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-wallpaper">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($wallpapers->count() == 0)
                    <div class="text-center my-5">
                        <div class="mb-3">
                            <i class="fas fa-images fa-3x text-muted"></i>
                        </div>
                        <h5>{{ admin_lang('No wallpapers found') }}</h5>
                        <p class="text-muted">{{ admin_lang('Try changing your search criteria or upload a new wallpaper.') }}</p>
                    </div>
                @endif
            </form>
            
            <div class="mt-4">
                {{ $wallpapers->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
            
            // Show category select when bulk action is 'category'
            $('#batch-action').on('change', function() {
                if ($(this).val() === 'category') {
                    $('#category-select').show();
                } else {
                    $('#category-select').hide();
                }
                
                $('#apply-btn').prop('disabled', $(this).val() === '');
            });
            
            // Enable/disable apply button when checkboxes are selected
            $('.wallpaper-checkbox').on('change', function() {
                let atLeastOneChecked = $('.wallpaper-checkbox:checked').length > 0;
                let actionSelected = $('#batch-action').val() !== '';
                
                $('#apply-btn').prop('disabled', !(atLeastOneChecked && actionSelected));
            });
            
            // Select all checkbox
            $('#select-all').on('change', function() {
                $('.wallpaper-checkbox').prop('checked', $(this).prop('checked'));
                let actionSelected = $('#batch-action').val() !== '';
                $('#apply-btn').prop('disabled', !($(this).prop('checked') && actionSelected));
            });
            
            // Confirm delete
            $('.delete-wallpaper').on('click', function(e) {
                e.preventDefault();
                
                if (confirm("{{ admin_lang('Are you sure you want to delete this wallpaper?') }}")) {
                    $(this).closest('form').submit();
                }
            });
            
            // Confirm batch delete
            $('#batch-form').on('submit', function(e) {
                if ($('#batch-action').val() === 'delete') {
                    e.preventDefault();
                    
                    if (confirm("{{ admin_lang('Are you sure you want to delete the selected wallpapers?') }}")) {
                        this.submit();
                    }
                }
            });
        });
    </script>
    @endpush
@endsection