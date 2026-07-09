<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'type',
        'minimum_marks',
        'description',
        'start_date_time',
        'end_date_time',
        'time_of_test',
        'number_of_questions',
        'is_active'
    ];
}
