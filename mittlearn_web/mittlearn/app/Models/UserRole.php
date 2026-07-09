<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $fillable = ['user_id', 'role_slug'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('userAdditionalDetail');
    }

    

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_slug', 'role_slug');  // Foreign key is 'slug' in UserRole and 'role_slug' in Role
    }

    public $timestamps = false;
}
