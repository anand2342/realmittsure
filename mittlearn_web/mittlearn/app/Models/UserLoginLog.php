<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginLog extends Model
{
    protected $fillable = ['user_id', 'role', 'login_at', 'logout_at', 'ip_address', 'platform'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function schools()
    {
        return $this->hasOne(Schools::class, 'user_id', 'user_id');
    }
    public function schoolName()
    {
        return $this->hasOneThrough(
            Schools::class,              // Final model you want to reach
            UserAdditionalDetail::class, // Intermediate model
            'user_id',                   // Foreign key on UserAdditionalDetail (points to UserLoginLog)
            'user_id',                        // Primary key on Schools table
            'user_id',                   // Local key on UserLoginLog
            'school_id'                  // Foreign key on UserAdditionalDetail (points to Schools)
        );
    }

    public function state()
    {
        return $this->hasOneThrough(
            State::class,
            Schools::class,
            'user_id',
            'id',
            'user_id',
            'state'
        );
    }
    public function district()
    {
        return $this->hasOneThrough(
            City::class,
            Schools::class,
            'user_id',
            'id',
            'user_id',
            'city'
        );
    }
    public function category()
    {
        return $this->hasOneThrough(
            Category::class,
            UserClass::class,
            'user_id',
            'id',
            'user_id',
            'category_id'
        );
    }
}
