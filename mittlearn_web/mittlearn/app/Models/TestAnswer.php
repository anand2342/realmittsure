<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'school_id',
        'test_id',
        'user_id',
        'question_id',
        'sub_index',
        'answer',
        'valid_answer',
        'is_correct',
        'score',
        'is_checked',
    ];
    public function questionBank()
    {
        return $this->hasOne(QuestionBank::class, 'id', 'question_id')->with('options');
    }

    public function optionsQuestion()
    {
        return $this->hasMany(QuestionOption::class, 'id', 'answer');
    }
}
