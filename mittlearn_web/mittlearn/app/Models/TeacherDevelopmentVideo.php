<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherDevelopmentVideo extends Model
{
    protected $fillable = ['content_id', 'video_title', 'video_url', 'video_file','video'];

    public function content()
    {
        return $this->belongsTo(TeacherDevelopmentContent::class, 'content_id');
    }
}
