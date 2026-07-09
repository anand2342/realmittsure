<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JaduiPitara extends Model
{
    protected $table = 'jadui_pitaras';
    protected $fillable = [
        'class_id',
        'series_id',
        'subject_id',
        'created_by',
        'jadui_pitara_classes_id',
    ];
}
