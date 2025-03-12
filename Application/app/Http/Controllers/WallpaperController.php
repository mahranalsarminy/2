<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GeneratedImage;
use App\Models\WallpaperCategory;
use Illuminate\Http\Request;

class WallpaperController extends Controller
{
    /**
     * Display the wallpaper home page
     */
    public function index()
    {
        $categories = WallpaperCategory::active()->get();
        $featuredWallpapers = GeneratedImage::public()->notExpired()
                                ->orderByDesc('downloads')
                                ->limit(8)
                                ->get();
        $newWallpapers = GeneratedImage::public()->notExpired()
                                ->orderByDesc('id')
                                ->limit(12)
                                ->get();
                                
        return view('wallpapers.index', compact('categories', 'featuredWallpapers', 'newWallpapers'));
    }
    
    /**
     * Display wallpapers by category
     */
    public function category($slug)
    {
        $category = WallpaperCategory::where('slug', $slug)->firstOrFail();
        $wallpapers = GeneratedImage::byCategory($category->id)->paginate(24);
        
        return view('wallpapers.category', compact('category', 'wallpapers'));
    }
    
    /**
     * Display wallpapers by tag
     */
    public function tag($tag)
    {
        $wallpapers = GeneratedImage::byTag($tag)->paginate(24);
        
        return view('wallpapers.tag', compact('tag', 'wallpapers'));
    }
    
    /**
     * Display wallpapers by resolution
     */
    public function resolution($resolution)
    {
        $wallpapers = GeneratedImage::byResolution($resolution)->paginate(24);
        
        return view('wallpapers.resolution', compact('resolution', 'wallpapers'));
    }
    
    /**
     * Display wallpapers by color
     */
    public function color($color)
    {
        $wallpapers = GeneratedImage::byColor($color)->paginate(24);
        
        return view('wallpapers.color', compact('color', 'wallpapers'));
    }
    
    /**
     * Show wallpaper details
     */
    public function show($id)
    {
        $wallpaper = GeneratedImage::where('id', unhashid($id))->notExpired()->firstOrFail();
        
        if ($wallpaper->isPrivate()) {
            abort_if(auth()->user() && auth()->user()->id != $wallpaper->user_id, 404);
            abort_if(!auth()->user() && $wallpaper->user_id, 404);
            abort_if(!auth()->user() && $wallpaper->ip != ipInfo()->ip, 404);
        }
        
        $wallpaper->increment('views');
        
        // Get related wallpapers
        $relatedWallpapers = GeneratedImage::where('id', '!=', $wallpaper->id)
                                ->when($wallpaper->category_id, function($query) use ($wallpaper) {
                                    return $query->where('category_id', $wallpaper->category_id);
                                })
                                ->public()
                                ->notExpired()
                                ->inRandomOrder()
                                ->limit(6)
                                ->get();
                                
        return view('wallpapers.show', compact('wallpaper', 'relatedWallpapers'));
    }
    
    /**
     * Download wallpaper
     */
    public function download($id)
    {
        $wallpaper = GeneratedImage::where('id', unhashid($id))->notExpired()->firstOrFail();
        
        if ($wallpaper->isPrivate()) {
            abort_if(auth()->user() && auth()->user()->id != $wallpaper->user_id, 404);
            abort_if(!auth()->user() && $wallpaper->user_id, 404);
            abort_if(!auth()->user() && $wallpaper->ip != ipInfo()->ip, 404);
        }
        
        $response = $wallpaper->download();
        if (!$response) {
            toastr()->error(lang('Download Error', 'wallpaper page'));
            return back();
        }
        
        $wallpaper->increment('downloads');
        
        return $response;
    }
    
    /**
     * Search wallpapers
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $wallpapers = GeneratedImage::public()
                        ->notExpired()
                        ->where(function($q) use ($query) {
                            $q->where('prompt', 'like', '%' . $query . '%')
                              ->orWhere('tags', 'like', '%' . $query . '%');
                        })
                        ->orderByDesc('id')
                        ->paginate(24);
                        
        return view('wallpapers.search', compact('wallpapers', 'query'));
    }
}