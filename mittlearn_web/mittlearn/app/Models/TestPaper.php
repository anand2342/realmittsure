<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPaper extends Model
{
    use HasFactory;
    protected $fillable = [
        'paper_type',
        'school_id',
        'board_id',
        'medium_id',
        'series_id',
        'class_id',
        'subject_id',
        'chapter_id',
        'test_term',
        'title',
        'description',
        'start_date_time',
        'end_date_time',
        'duration',
        'logo',
        'min_passing_percentage',
        'is_active',
        'question_order_type',
        'status',
        'created_by',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }
    public function medium()
    {
        return $this->belongsTo(Medium::class, 'medium_id');
    }
    public function questionCount()
    {
        return $this->hasMany(TestPaperQuestion::class, 'paper_id', 'id')->with('Question');
    }
    public function questions()
    {
        return $this->belongsToMany(QuestionBank::class, 'test_paper_questions', 'paper_id', 'question_id')->with('options');
    }
    public function Class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function series()
    {
        return $this->belongsTo(BookSeries::class, 'series_id');
    }
    public function Subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function testParticipent()
    {
        return $this->hasMany(TestParticipent::class, 'test_id', 'id');
    }
    public function getIndianStartDateTimeAttribute()
    {
        if (!$this->start_date_time) return null;

        return \Carbon\Carbon::parse($this->start_date_time)
            ->format('d-m-Y h:i:s A'); // 30-03-2025 12:00:00 AM
    }

    public function getIndianEndDateTimeAttribute()
    {
        if (!$this->end_date_time) return null;

        return \Carbon\Carbon::parse($this->end_date_time)
            ->format('d-m-Y h:i:s A'); // 30-03-2025 03:34:00 AM
    }
}
