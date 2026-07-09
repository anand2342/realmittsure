<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPaperQuestion extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'paper_id',
        'question_id',
    ];

    // TestPaperQuestion.php
    public function Question()
    {
        return $this->belongsTo(QuestionBank::class, 'question_id', 'id')
            ->where('is_active', 1);
    }


    // QuestionBank.php
    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }
}
