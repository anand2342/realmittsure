<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolPlannerVisibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_id',
        'type',
    ];
}


