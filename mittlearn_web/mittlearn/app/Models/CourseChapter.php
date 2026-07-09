<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CourseChapter extends Model
{
    use LogsActivity, HasFactory;

    protected $table = 'course_chapters';
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('course-chapter-activity');
    }
    protected $fillable = [
        'course_id',
        'chapter_name',
        'chapter_description',
        'topic_covered',
        'content_creation_date',
        'sort_order',
        'supporting_folder_id',
        'created_by',
        'created_date',
        'is_approved',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function details()
    {
        return $this->hasMany(AdditionalDataRow::class, 'model_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function chapters()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'id')->where('type', 'course_chapter')->orderBy('sort_order', 'asc');
    }
    public function chapterListing()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'id')->where('type', 'course_chapter')->orderBy('sort_order', 'asc');
    }
    public function activityListing()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'id')->where('type', 'activity_worksheet_link')->orderBy('sort_order', 'asc');
    }
    public function chapterSupportingFiles()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'supporting_folder_id')->where('type', 'content_upload');
    }
    public function folder()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'supporting_folder_id')->where('type', 'content_upload');
    }
    public function resources()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'id')->where('type', 'course_chapter_extra');
    }
    public function documents()
    {
        return $this->hasMany(MediaFolder::class, 'id', 'supporting_folder_id')->where('parent_id', Auth::id());
    }
    public function mediaFiles()
    {
        return $this->hasMany(MediaFiles::class, 'tbl_id', 'id')->where('type', 'course_chapter');
    }


    protected $appends = ['chapter_files'];


    public function getChapterFilesAttribute()
    {
        // Check if request comes from the mobile app
        $isAppRequest = request()->is('api/*');
        // If not app → return all files (default web behaviour)
        if (!$isAppRequest) {
            return MediaFiles::where('tbl_id', $this->id)
                ->whereIn('type', ['course_chapter', 'course_chapter_extra'])
                ->get()
                ->map(function ($file) {
                    return [
                        'file_name' => $file->file_name ?: $file->original_name,
                        'video_id'  => $file->id,
                        'file_path' => Storage::url('uploads/course_chapter_files/' . $file->attachment_file),
                        'file_type' => $file->file_extension,
                    ];
                });
        }

        // If app request → filter by language
        $language = request()->input('language', 'bilingual');

        return MediaFiles::where('tbl_id', $this->id)
            ->whereIn('type', ['course_chapter', 'course_chapter_extra'])
            ->where('language', $language)
            ->get()
            ->map(function ($file) {
                return [
                    'file_name' => $file->file_name ?: $file->original_name,
                    'video_id'  => $file->id,
                    'file_path' => Storage::url('uploads/course_chapter_files/' . $file->attachment_file),
                    'file_type' => $file->file_extension,
                    'language' => $file->language,
                ];
            });
    }
}
