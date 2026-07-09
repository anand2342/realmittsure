<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserManual extends Model
{
    protected $table = 'user_manuals';
    protected $fillable = [
        'title',
        'description',
        'pdf_path',
        'video_path',
        'created_by',
        'visible_to_roles',
        'is_active',
    ];
    public function getVisibleRoleNamesAttribute()
    {
        $slugs = explode(',', $this->visible_to_roles);
        return Role::whereIn('role_slug', $slugs)->pluck('role_name')->toArray();
    }
}
