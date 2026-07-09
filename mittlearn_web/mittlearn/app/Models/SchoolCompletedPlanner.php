<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolCompletedPlanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'planner_id',
        'school_id',
        'completed_by',
        'user_type',
        'completion_date',
    ];
}


