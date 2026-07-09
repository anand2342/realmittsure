<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackUserVideoProgress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_id', 'chapter_id', 'video_id', 'watched_duration', 'video_duration'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->with('metadataValues');
    }

    // Relationship with the Chapter model
    public function chapter()
    {
        return $this->belongsTo(CourseChapter::class, 'chapter_id', 'id');
    }
}
