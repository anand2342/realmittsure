<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'board_id',
        'medium_id',
        'series_id',
        'class_id',
        'subject_id',
        'chapter_id',
        'question_type',
        'question',
        'description',
        'additional_data',
        'marks',
        'difficulty_level',
        'created_by',
        'is_approved',
        'is_active',
        'suggested_answer'

    ];

    public function class()
    {
        return $this->hasOne(Classes::class, 'id', 'class_id');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id', 'id');
    }

    public function subject()
    {
        return $this->hasOne(Subject::class, 'id', 'subject_id');
    }
    public function testPapers()
    {
        return $this->belongsToMany(TestPaper::class, 'test_paper_questions', 'question_id', 'paper_id');
    }
}
