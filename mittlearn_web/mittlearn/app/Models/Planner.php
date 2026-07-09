<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planner extends Model
{
    use HasFactory;
    protected $fillable = [
        'board_id',
        'medium_id',
        'type',
        'series_id',
        'class_id',
        'batch_id',
        'subject_id',
        'chapter_id',
        'allotted_days',
        'start_date',
        'completion_date',
        'academic_session_id',
        'total_periods',
    ];
    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }
    public function medium()
    {
        return $this->belongsTo(Medium::class, 'medium_id');
    }
    public function series()
    {
        return $this->belongsTo(BookSeries::class, 'series_id');
    }
    public function batch()
    {
        return $this->belongsTo(AcademicSession::class, 'batch_id');
    }
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function chapter()
    {
        return $this->belongsTo(CourseChapter::class, 'chapter_id');
    }
    public function details()
    {
        return $this->hasMany(AdditionalDataRow::class, 'model_id', 'id');
    }
}
