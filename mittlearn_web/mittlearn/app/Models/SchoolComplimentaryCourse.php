<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolComplimentaryCourse extends Model
{

    protected $table = "school_complimentary_courses";
    protected $fillable = ['name', 'school_id', 'category_id', 'course_id'];
    public function courses()
    {
        return $this->hasMany(Course::class, 'id', 'course_id')->with('metadata');
    }
    public function schools()
    {
        return $this->belongsTo(Schools::class, 'id', 'school_id');
    }
}
