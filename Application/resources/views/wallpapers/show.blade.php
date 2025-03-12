@extends('layouts.front')
@section('title', 'Search Results: ' . $query)
@section('content')
    {!! ads_home_page_top() !!}
    
    <!-- Header -->
    <header class="header my-5">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="mb-3">Search Results: "{{ $query }}"</h1>
                <p class="text-muted">Found {{ $wallpapers->total() }} wallpapers</p>
            </div>
            <div class="card-v">
                <form action="{{ route('wallpapers.search') }}" method="GET">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-10">
                            <input type="text" name="q" class="form-control form-control-lg"
                                placeholder="Search for wallpapers..." 
                                value="{{ $query ?? '' }}" />
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-lg w-100"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </header>
    
    <!-- Filter Options -->
    <div class="section pt-0">
        <div class="container">
            <div class="card-v">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label">Sort By</label>
                        <select class="form-select" id="sortFilter">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Most Downloads</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Views</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Resolution</label>
                        <select class="form-select" id="resolutionFilter">
                            <option value="">All Resolutions</option>
                            <option value="3840x2160" {{ request('resolution') == '3840x2160' ? 'selected' : '' }}>4K UHD (3840x2160)</option>
                            <option value="2560x1440" {{ request('resolution') == '2560x1440' ? 'selected' : '' }}>QHD (2560x1440)</option>
                            <option value="1920x1080" {{ request('resolution') == '1920x1080' ? 'selected' : '' }}>Full HD (1920x1080)</option>
                            <option value="1366x768" {{ request('resolution') == '1366x768' ? 'selected' : '' }}>HD (1366x768)</option>
                            <option value="1280x720" {{ request('resolution') == '1280x720' ? 'selected' : '' }}>720p (1280x720)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select class="form-select" id="categoryFilter">
                            <option value="">All Categories</option>
                            @php
                                $categories = \App\Models\WallpaperCategory::where('is_active', true)->get();
                            @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {!! ads_home_page_center() !!}
    
    <!-- Search Results -->
    <div class="section pt-0">
        <div class="container">
            <div class="section-inner">
                <div class="section-body">
                    @if($wallpapers->count() > 0)
                        <div class="row g-3 justify-content-center">
                            @foreach($wallpapers as $wallpaper)
                                <div class="col-6 col-md-4 col-lg-3" data-aos="zoom-in" data-aos-duration="1000">
                                    <div class="ai-image">
                                        <img class="lazy" data-src="{{ $wallpaper->getThumbnailLink() }}" 
                                             alt="{{ $wallpaper->prompt }}" />
                                        <div class="spinner-border"></div>
                                        <div class="ai-image-hover">
                                            <p class="mb-0">{{ $wallpaper->prompt }}</p>
                                            <div class="d-flex gap-2 mt-2">
                                                <a href="{{ route('wallpapers.show', hashid($wallpaper->id)) }}" 
                                                   class="btn btn-primary btn-sm flex-grow-1">View</a>
                                                <a href="{{ route('wallpapers.download', [hashid($wallpaper->id), $wallpaper->getMainImageName()]) }}" 
                                                   class="btn btn-light btn-sm"><i class="fas fa-download"></i></a>
                                            </div>
                                            <div class="mt-2">
                                                @if($wallpaper->category)
                                                    <span class="badge bg-light text-dark">{{ $wallpaper->category->name }}</span>
                                                @endif
                                                <span class="badge bg-secondary">{{ $wallpaper->resolution }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-5">
                            {{ $wallpapers->appends(request()->input())->links() }}
                        </div>
                    @else
                        <div class="card-v text-center">
                            <p class="text-muted mb-0">No wallpapers found matching your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {!! ads_home_page_bottom() !!}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/aos/aos.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/aos/aos.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.lazy.min.js') }}"></script>
    @endpush
    @push('scripts')
    <script>
        $(document).ready(function() {
            // Handle filter changes
            $('#sortFilter, #resolutionFilter, #categoryFilter').on('change', function() {
                applyFilters();
            });
            
            function applyFilters() {
                let currentUrl = new URL(window.location.href);
                let params = new URLSearchParams(currentUrl.search);
                
                // Update sort
                const sortValue = $('#sortFilter').val();
                if (sortValue) {
                    params.set('sort', sortValue);
                } else {
                    params.delete('sort');
                }
                
                // Update resolution
                const resolutionValue = $('#resolutionFilter').val();
                if (resolutionValue) {
                    params.set('resolution', resolutionValue);
                } else {
                    params.delete('resolution');
                }
                
                // Update category
                const categoryValue = $('#categoryFilter').val();
                if (categoryValue) {
                    params.set('category', categoryValue);
                } else {
                    params.delete('category');
                }
                
                // Navigate to filtered URL
                window.location.href = `${currentUrl.pathname}?${params.toString()}`;
            }
        });
    </script>
    @endpush
@endsection