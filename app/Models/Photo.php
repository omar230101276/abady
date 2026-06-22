<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id',
        'image_path',
    ];

    /**
     * Get the photo image URL.
     */
    public function getImageUrlAttribute()
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get the photo thumbnail URL.
     */
    public function getThumbnailUrlAttribute()
    {
        if (str_starts_with($this->image_path, 'http')) {
            return app(\App\Services\CloudinaryService::class)->getThumbnailUrl($this->image_path);
        }
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get the album that owns the photo.
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
