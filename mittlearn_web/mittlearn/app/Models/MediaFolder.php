<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFolder extends Model
{
    use HasFactory;

    public function fileCount()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'id')->where('type', 'content_upload');
    }

    public function mediaFolderFiles()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'id')->where('type', 'content_upload');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
    // Relationship to BookSeries (based on comma-separated IDs)
    public function getSeriesListAttribute()
    {
        $ids = array_filter(explode(',', $this->distribute_series_ids));
        return BookSeries::whereIn('id', $ids)->get();
    }

    // Relationship to Roles (based on comma-separated slugs)
    public function getRoleListAttribute()
    {
        $slugs = array_filter(explode(',', $this->distribute_role_slug));
        return Role::whereIn('role_slug', $slugs)->get();
    }
}
