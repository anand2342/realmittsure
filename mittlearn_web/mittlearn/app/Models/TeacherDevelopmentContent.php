<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class TeacherDevelopmentContent extends Model
{
    protected $fillable = ['type', 'title', 'description', 'is_for_all_schools'];

    public function videos()
    {
        return $this->hasMany(TeacherDevelopmentVideo::class, 'content_id');
    }

    public function schools()
    {
        return $this->belongsToMany(Schools::class, 'teacher_development_content_schools', 'content_id', 'school_id');
    }

}