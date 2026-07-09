<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentSkillQrCourse extends Model
{
    use HasFactory;

    protected $table = 'talent_skill_qr_courses';

    protected $fillable = [
        'user_role',
        'user_id',
        'category_id',
        'subcategory_id',
        'course_ids',
    ];
}
