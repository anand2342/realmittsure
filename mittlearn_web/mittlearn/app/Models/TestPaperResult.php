<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPaperResult extends Model
{
    use HasFactory;
    protected $fillable = [
        'school_id',
        'test_id',
        'user_id',
        'total_questions',
        'min_passing_percentage',
        'total_attemted_questions',
        'total_marks',
        'obtained_marks',
        'obtained_percentage',
        'result',
        'status',
    ];
}
