<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WallpaperCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'description',
        'is_active',
        'display_order'
    ];

    /**
     * Get the wallpapers in this category
     */
    public function wallpapers()
    {
        return $this->hasMany(GeneratedImage::class, 'category_id');
    }
    
    /**
     * Get active categories
     */
    public static function active()
    {
        return static::where('is_active', true)
                    ->orderBy('display_order')
                    ->orderBy('name');
    }
}