<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'cloudinary_public_id',
        'secure_url',
        'media_type',
        'width',
        'height',
        'file_size',
    ];
}
