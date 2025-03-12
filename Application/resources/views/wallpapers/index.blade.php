@extends('layouts.front')
@section('title', 'Stunning HD Wallpapers For Your Devices')
@section('content')
    {!! ads_home_page_top() !!}
    
    <!-- Hero Banner -->
    <header class="header my-5">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-4 mb-3">Beautiful HD Wallpapers</h1>
                <p class="lead text-muted">Download free high resolution wallpapers for your desktop, mobile and tablet</p>
            </div>
            <div class="card-v">
                <form action="{{ route('wallpapers.search') }}" method="GET">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-10">
                            <input type="text" name="q" class="form-control form-control-lg"
                                placeholder="Search for wallpapers..." 
                                value="{{ request('q') ?? '' }}" />
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-lg w-100"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </header>
    
    <!-- Categories Section -->
    <div class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Browse Categories</h2>
            </div>
            <div class="section-body">
                <div class="row g-4 justify-content-center">
                    @foreach($categories as $category)
                        <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-duration="1000">
                            <a href="{{ route('wallpapers.category', $category->slug) }}" class="text-decoration-none">
                                <div class="card category-card">
                                    <img class="card-img-top lazy" data-src="{{ $category->thumbnail }}" alt="{{ $category->name }}">
                                    <div class="card-body text-center">
                                        <h5 class="card-title mb-0">{{ $category->name }}</h5>
                                        <p class="text-muted small mb-0">{{ $category->wallpapers->count() }} wallpapers</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    {!! ads_home_page_center() !!}
    
    <!-- Featured Wallpapers -->
    <div class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Wallpapers</h2>
                <div class="section-actions">
                    <a href="{{ route('wallpapers.search') }}?sort=downloads" class="btn btn-outline-primary">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="section-body">
                <div class="row g-3 justify-content-center">
                    @foreach($featuredWallpapers as $wallpaper)
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
                                    @if($wallpaper->category)
                                        <div class="mt-2">
                                            <span class="badge bg-light text-dark">{{ $wallpaper->category->name }}</span>
                                            <span class="badge bg-secondary">{{ $wallpaper->resolution }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Latest Wallpapers -->
    <div class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Latest Wallpapers</h2>
                <div class="section-actions">
                    <a href="{{ route('wallpapers.search') }}?sort=latest" class="btn btn-outline-primary">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="section-body">
                <div class="row g-3 justify-content-center">
                    @foreach($newWallpapers as $wallpaper)
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
                                    @if($wallpaper->category)
                                        <div class="mt-2">
                                            <span class="badge bg-light text-dark">{{ $wallpaper->category->name }}</span>
                                            <span class="badge bg-secondary">{{ $wallpaper->resolution }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Resolution Filters -->
    <div class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Browse by Resolution</h2>
            </div>
            <div class="section-body">
                <div class="row g-2 justify-content-center">
                    <div class="col-6 col-md-3 col-lg-2">
                        <a href="{{ route('wallpapers.resolution', '3840x2160') }}" class="btn btn-outline-secondary w-100 mb-2">4K UHD</a>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <a href="{{ route('wallpapers.resolution', '2560x1440') }}" class="btn btn-outline-secondary w-100 mb-2">QHD</a>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <a href="{{ route('wallpapers.resolution', '1920x1080') }}" class="btn btn-outline-secondary w-100 mb-2">Full HD</a>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <a href="{{ route('wallpapers.resolution', '1366x768') }}" class="btn btn-outline-secondary w-100 mb-2">HD</a>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <a href="{{ route('wallpapers.resolution', '1280x720') }}" class="btn btn-outline-secondary w-100 mb-2">720p</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Popular Tags -->
    <div class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Popular Tags</h2>
            </div>
            <div class="section-body">
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="{{ route('wallpapers.tag', 'nature') }}" class="btn btn-outline-primary">Nature</a>
                    <a href="{{ route('wallpapers.tag', 'landscape') }}" class="btn btn-outline-primary">Landscape</a>
                    <a href="{{ route('wallpapers.tag', 'abstract') }}" class="btn btn-outline-primary">Abstract</a>
                    <a href="{{ route('wallpapers.tag', 'space') }}" class="btn btn-outline-primary">Space</a>
                    <a href="{{ route('wallpapers.tag', 'city') }}" class="btn btn-outline-primary">City</a>
                    <a href="{{ route('wallpapers.tag', 'animals') }}" class="btn btn-outline-primary">Animals</a>
                    <a href="{{ route('wallpapers.tag', 'art') }}" class="btn btn-outline-primary">Art</a>
                    <a href="{{ route('wallpapers.tag', 'minimalist') }}" class="btn btn-outline-primary">Minimalist</a>
                    <a href="{{ route('wallpapers.tag', 'fantasy') }}" class="btn btn-outline-primary">Fantasy</a>
                    <a href="{{ route('wallpapers.tag', 'cars') }}" class="btn btn-outline-primary">Cars</a>
                </div>
            </div>
        </div>
    </div>
    
    @include('includes.faqs')
    {!! ads_home_page_bottom() !!}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/aos/aos.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/aos/aos.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.lazy.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection