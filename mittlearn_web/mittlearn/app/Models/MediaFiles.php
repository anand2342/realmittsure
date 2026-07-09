<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFiles extends Model
{
    protected $fillable = ['tbl_id', 'type', 'attachment_file', 'language', 'video_view_type', 'original_name', 'file_name', 'link_url', 'sort_order', 'file_extension', 'file_size', 'uploaded_by', 'mime_type', 'video_duration'];

    protected $table = "media_files";


    public function courseChapter()
    {
        return $this->belongsTo(CourseChapter::class, 'id', 'tbl_id')->with('course');
    }
    public function chapter()
    {
        return $this->belongsTo(CourseChapter::class, 'tbl_id');
    }
}
