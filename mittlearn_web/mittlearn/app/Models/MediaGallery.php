<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaGallery extends Model
{
    use HasFactory;

    protected $table = 'media_gallery';

    protected $fillable = [
        'parent_id',
        'gallery_name',
        'available_to_users',
        'event_name',
        'media_link',
        'description',
        'validity_date',
    ];

    public function mediaGalleryFiles()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'id')->where('type', 'school_media_gallery');
    }
}
