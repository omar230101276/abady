<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cover_image',
    ];

    /**
     * Get the cover image URL.
     */
    public function getCoverImageUrlAttribute()
    {
        if (empty($this->cover_image)) {
            $firstPhoto = $this->photos()->first();
            if ($firstPhoto) {
                return $firstPhoto->image_url;
            }
            return asset('images/default-album.png');
        }
        
        if (str_starts_with($this->cover_image, 'http')) {
            return $this->cover_image;
        }
        
        return asset('storage/' . $this->cover_image);
    }

    /**
     * Get the cover image thumbnail URL.
     */
    public function getCoverThumbnailUrlAttribute()
    {
        if (empty($this->cover_image)) {
            $firstPhoto = $this->photos()->first();
            if ($firstPhoto) {
                return $firstPhoto->thumbnail_url;
            }
            return asset('images/default-album.png');
        }
        
        if (str_starts_with($this->cover_image, 'http')) {
            return app(\App\Services\CloudinaryService::class)->getThumbnailUrl($this->cover_image);
        }
        
        return asset('storage/' . $this->cover_image);
    }

    /**
     * Get the photos for the album.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}
