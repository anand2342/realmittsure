<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsAboutUs extends Model
{
    use HasFactory;
    protected $table = 'cms_about_us';

    protected $fillable = ['title', 'banner_description', 'at_glance', 'mittsure_section', 'versatile_activities_description', 'versatile_activities', 'category_id', 'description', 'leadership', 'vision_description', 'vision_image', 'about_vision', 'program_description', 'programs'];
}
