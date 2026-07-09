<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonNumber extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lesson_numbers';

    protected $fillable = [
        'number',
        'is_active',
    ];
}
