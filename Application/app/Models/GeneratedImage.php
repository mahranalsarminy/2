<?php

namespace App\Models;

// Assuming the original model exists with most functionality
// This is a partial update

class GeneratedImage extends Model
{
    // Add these to fillable array
    protected $fillable = [
        // existing fields...
        'category_id',
        'resolution',
        'color',
        'tags'
    ];

    /**
     * Get the category for this wallpaper
     */
    public function category()
    {
        return $this->belongsTo(WallpaperCategory::class, 'category_id');
    }
    
    /**
     * Get wallpapers by category
     */
    public static function byCategory($categoryId)
    {
        return static::where('category_id', $categoryId)
                     ->notExpired()
                     ->public()
                     ->orderByDesc('id');
    }
    
    /**
     * Get wallpapers by tag
     */
    public static function byTag($tag)
    {
        return static::where('tags', 'like', '%' . $tag . '%')
                     ->notExpired()
                     ->public()
                     ->orderByDesc('id');
    }
    
    /**
     * Get wallpapers by color
     */
    public static function byColor($color)
    {
        return static::where('color', $color)
                     ->notExpired()
                     ->public()
                     ->orderByDesc('id');
    }
    
    /**
     * Get wallpapers by resolution
     */
    public static function byResolution($resolution)
    {
        return static::where('resolution', $resolution)
                     ->notExpired()
                     ->public()
                     ->orderByDesc('id');
    }
}