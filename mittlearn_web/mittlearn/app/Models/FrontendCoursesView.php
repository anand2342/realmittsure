<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendCoursesView extends Model
{
    use HasFactory;
    protected $fillable = [
        'series_id',
        'classes_ids',
    ];
}
