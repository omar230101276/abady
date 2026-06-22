<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
    ];

    /**
     * Get the collaboration image URL.
     */
    public function getImageUrlAttribute()
    {
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    /**
     * Get the collaboration thumbnail URL.
     */
    public function getThumbnailUrlAttribute()
    {
        if (str_starts_with($this->image, 'http')) {
            return app(\App\Services\CloudinaryService::class)->getThumbnailUrl($this->image);
        }
        return asset('storage/' . $this->image);
    }
}
