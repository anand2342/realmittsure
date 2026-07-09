<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolAssignedDigitalContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_id',
        'subject_id',
        'series_id',
        'created_by',
    ];
// App\Models\SchoolAssignedDigitalContent

public function series()
{
    return $this->belongsTo(\App\Models\BookSeries::class, 'series_id');
}
   
}


