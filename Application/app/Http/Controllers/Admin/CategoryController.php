<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WallpaperCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = WallpaperCategory::orderBy('display_order')
                        ->orderBy('name')
                        ->paginate(15);
                        
        return view('backend.categories.index', compact('categories'));
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
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:wallpaper_categories',
            'thumbnail' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        $category = new WallpaperCategory();
        $category->name = $request->name;
        $category->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $category->description = $request->description;
        $category->display_order = $request->display_order ?? 0;
        $category->is_active = $request->has('is_active') ? 1 : 0;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $this->uploadThumbnail($request->file('thumbnail'));
            $category->thumbnail = $thumbnailPath;
        }

        $category->save();

        toastr()->success(admin_lang('Category has been created successfully'));
        return redirect()->route('admin.categories.index');
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
        $category = WallpaperCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:wallpaper_categories,slug,' . $category->id,
            'thumbnail' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        $category->name = $request->name;
        
        if ($request->slug) {
            $category->slug = Str::slug($request->slug);
        }
        
        $category->description = $request->description;
        $category->display_order = $request->display_order ?? 0;
        $category->is_active = $request->has('is_active') ? 1 : 0;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($category->thumbnail) {
                $this->deleteThumbnail($category->thumbnail);
            }
            
            $thumbnailPath = $this->uploadThumbnail($request->file('thumbnail'));
            $category->thumbnail = $thumbnailPath;
        }

        $category->save();

        toastr()->success(admin_lang('Category has been updated successfully'));
        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = WallpaperCategory::findOrFail($id);
        
        // Check if category has wallpapers
        if ($category->wallpapers()->count() > 0) {
            toastr()->error(admin_lang('Cannot delete category with wallpapers. Please reassign wallpapers first.'));
            return redirect()->route('admin.categories.index');
        }
        
        // Delete thumbnail if exists
        if ($category->thumbnail) {
            $this->deleteThumbnail($category->thumbnail);
        }
        
        $category->delete();
        
        toastr()->success(admin_lang('Category has been deleted successfully'));
        return redirect()->route('admin.categories.index');
    }
    
    /**
     * Upload thumbnail image
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string
     */
    private function uploadThumbnail($file)
    {
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = 'categories/' . $filename;
        
        // Create thumbnail
        $img = Image::make($file->getRealPath());
        $img->fit(400, 400, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Store using the selected storage provider
        $storage = Storage::disk(env('FILESYSTEM_DRIVER', 'public'));
        $storage->put($path, (string) $img->encode());
        
        return $storage->url($path);
    }
    
    /**
     * Delete thumbnail
     *
     * @param  string  $thumbnailPath
     * @return void
     */
    private function deleteThumbnail($thumbnailPath)
    {
        // Extract path from URL
        $path = parse_url($thumbnailPath, PHP_URL_PATH);
        $path = ltrim($path, '/');
        
        // Get the storage disk
        $storage = Storage::disk(env('FILESYSTEM_DRIVER', 'public'));
        
        // Check if file exists before deleting
        if ($storage->exists($path)) {
            $storage->delete($path);
        }
    }
}