<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'heading',
        'academic_description',
        'non_academic_description',
        'instructor_title',
        'instructor_description',
        'heading_1',
        'sub_heading_1',
        'group_academic_title',
        'group_non_academic_title',
        'academic_image',
        'non_academic_image',
    ];
}
