<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneratedImage;
use App\Models\WallpaperCategory;
use App\Models\StorageProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class WallpaperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = GeneratedImage::query();
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by resolution
        if ($request->filled('resolution')) {
            $query->where('resolution', $request->resolution);
        }
        
        // Search by prompt or tags
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('prompt', 'like', $searchTerm)
                  ->orWhere('tags', 'like', $searchTerm);
            });
        }
        
        // Sort results
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderByDesc('id');
                    break;
                case 'oldest':
                    $query->orderBy('id');
                    break;
                case 'popular':
                    $query->orderByDesc('downloads');
                    break;
                case 'views':
                    $query->orderByDesc('views');
                    break;
                default:
                    $query->orderByDesc('id');
            }
        } else {
            $query->orderByDesc('id');
        }
        
        $wallpapers = $query->paginate(24);
        $categories = WallpaperCategory::orderBy('name')->get();
        
        return view('backend.wallpapers.index', compact('wallpapers', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = WallpaperCategory::where('is_active', true)
                        ->orderBy('name')
                        ->get();
        
        $storageProviders = StorageProvider::all();
        
        return view('backend.wallpapers.create', compact('categories', 'storageProviders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
            'wallpaper' => 'required|image|max:10240',
            'category_id' => 'nullable|exists:wallpaper_categories,id',
            'resolution' => 'required|string',
            'storage_provider_id' => 'required|exists:storage_providers,id',
            'tags' => 'nullable|string',
            'visibility' => 'required|in:0,1',
            'color' => 'nullable|string|max:7',
        ]);
        
        $storageProvider = StorageProvider::find($request->storage_provider_id);
        
        if (!$storageProvider) {
            toastr()->error(admin_lang('Storage provider not found.'));
            return back()->withInput();
        }
        
        try {
            // Upload the wallpaper
            $storage = Storage::disk($storageProvider->alias);
            
            // Generate unique name
            $filename = Str::random(30) . '.' . $request->file('wallpaper')->getClientOriginalExtension();
            $path = 'wallpapers/' . $filename;
            
            // Create thumbnail
            $img = Image::make($request->file('wallpaper')->getRealPath());
            
            // Save original
            $storage->put($path, (string) $request->file('wallpaper')->get());
            
            // Create and save thumbnail
            $thumbnailPath = 'wallpapers/thumbnails/' . $filename;
            $thumbnail = Image::make($request->file('wallpaper')->getRealPath());
            $thumbnail->fit(600, 400, function ($constraint) {
                $constraint->aspectRatio();
            });
            $storage->put($thumbnailPath, (string) $thumbnail->encode());
            
            // Create the wallpaper record
            $wallpaper = new GeneratedImage();
            $wallpaper->user_id = auth()->id();
            $wallpaper->storage_provider_id = $storageProvider->id;
            $wallpaper->category_id = $request->category_id;
            $wallpaper->ip_address = request()->ip();
            $wallpaper->prompt = $request->prompt;
            $wallpaper->resolution = $request->resolution;
            $wallpaper->main = $path;
            $wallpaper->thumbnail = $thumbnailPath;
            $wallpaper->tags = $request->tags;
            $wallpaper->visibility = $request->visibility;
            $wallpaper->color = $request->color;
            $wallpaper->save();
            
            toastr()->success(admin_lang('Wallpaper has been uploaded successfully.'));
            return redirect()->route('admin.wallpapers.index');
            
        } catch (\Exception $e) {
            toastr()->error(admin_lang('Error uploading wallpaper: ') . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $wallpaper = GeneratedImage::findOrFail($id);
        $categories = WallpaperCategory::where('is_active', true)
                        ->orderBy('name')
                        ->get();
        
        return view('backend.wallpapers.edit', compact('wallpaper', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $wallpaper = GeneratedImage::findOrFail($id);
        
        $request->validate([
            'prompt' => 'required|string',
            'category_id' => 'nullable|exists:wallpaper_categories,id',
            'resolution' => 'required|string',
            'tags' => 'nullable|string',
            'visibility' => 'required|in:0,1',
            'color' => 'nullable|string|max:7',
        ]);
        
        $wallpaper->prompt = $request->prompt;
        $wallpaper->category_id = $request->category_id;
        $wallpaper->resolution = $request->resolution;
        $wallpaper->tags = $request->tags;
        $wallpaper->visibility = $request->visibility;
        $wallpaper->color = $request->color;
        $wallpaper->save();
        
        toastr()->success(admin_lang('Wallpaper has been updated successfully.'));
        return redirect()->route('admin.wallpapers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wallpaper = GeneratedImage::findOrFail($id);
        
        // Get storage provider
        $storageProvider = $wallpaper->storageProvider;
        
        if (!$storageProvider) {
            toastr()->error(admin_lang('Storage provider not found.'));
            return redirect()->route('admin.wallpapers.index');
        }
        
        $storage = Storage::disk($storageProvider->alias);
        
        // Delete the original wallpaper file
        if ($storage->exists($wallpaper->main)) {
            $storage->delete($wallpaper->main);
        }
        
        // Delete the thumbnail
        if ($storage->exists($wallpaper->thumbnail)) {
            $storage->delete($wallpaper->thumbnail);
        }
        
        // Delete the database record
        $wallpaper->delete();
        
        toastr()->success(admin_lang('Wallpaper has been deleted successfully.'));
        return redirect()->route('admin.wallpapers.index');
    }
    
    /**
     * Batch Actions (Delete multiple, change category, etc)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function batchAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,category',
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'category_id' => 'required_if:action,category|nullable|exists:wallpaper_categories,id',
        ]);
        
        $count = 0;
        
        if ($request->action === 'delete') {
            foreach ($request->ids as $id) {
                $wallpaper = GeneratedImage::find($id);
                
                if ($wallpaper) {
                    // Get storage provider
                    $storageProvider = $wallpaper->storageProvider;
                    
                    if ($storageProvider) {
                        $storage = Storage::disk($storageProvider->alias);
                        
                        // Delete the original wallpaper file
                        if ($storage->exists($wallpaper->main)) {
                            $storage->delete($wallpaper->main);
                        }
                        
                        // Delete the thumbnail
                        if ($storage->exists($wallpaper->thumbnail)) {
                            $storage->delete($wallpaper->thumbnail);
                        }
                    }
                    
                    // Delete the database record
                    $wallpaper->delete();
                    $count++;
                }
            }
            
            toastr()->success(admin_lang($count . ' wallpapers have been deleted successfully.'));
        } elseif ($request->action === 'category') {
            $count = GeneratedImage::whereIn('id', $request->ids)->update([
                'category_id' => $request->category_id
            ]);
            
            toastr()->success(admin_lang($count . ' wallpapers have been updated successfully.'));
        }
        
        return redirect()->route('admin.wallpapers.index');
    }
}