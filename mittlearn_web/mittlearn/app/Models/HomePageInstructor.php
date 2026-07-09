<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageInstructor extends Model
{
    protected $fillable = ['name', 'category','profile_image','instructor_description','facebook', 'linkedin', 'twitter'];
    
}
