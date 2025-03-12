@extends('layouts.front')
@section('title', $category->name . ' Wallpapers')
@section('content')
    {!! ads_home_page_top() !!}
    
    <!-- Header -->
    <header class="header my-5">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="mb-3">{{ $category->name }} Wallpapers</h1>
                @if($category->description)
                    <p class="lead text-muted">{{ $category->description }}</p>
                @endif
            </div>
            <div class="card-v">
                <form action="{{ route('wallpapers.search') }}" method="GET">
                    <input type="hidden" name="category" value="{{ $category->id }}">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-10">
                            <input type="text" name="q" class="form-control form-control-lg"
                                placeholder="Search {{ $category->name }} wallpapers..." />
                        </div>
                        <div class="col">
                            <button class="btn btn-primary btn-lg w-100"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </header>
    
    {!! ads_home_page_center() !!}
    
    <!-- Wallpapers Grid -->
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
                            <p class="text-muted mb-0">No wallpapers found in this category.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Other Categories -->
    <div class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Other Categories</h2>
            </div>
            <div class="section-body">
                <div class="row g-3 justify-content-center">
                    @php
                        $otherCategories = \App\Models\WallpaperCategory::where('id', '!=', $category->id)
                            ->where('is_active', true)
                            ->inRandomOrder()
                            ->limit(4)
                            ->get();
                    @endphp
                    
                    @foreach($otherCategories as $otherCategory)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('wallpapers.category', $otherCategory->slug) }}" class="text-decoration-none">
                                <div class="card category-card">
                                    <img class="card-img-top lazy" data-src="{{ $otherCategory->thumbnail }}" alt="{{ $otherCategory->name }}">
                                    <div class="card-body text-center">
                                        <h5 class="card-title mb-0">{{ $otherCategory->name }}</h5>
                                        <p class="text-muted small mb-0">{{ $otherCategory->wallpapers->count() }} wallpapers</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
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
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection