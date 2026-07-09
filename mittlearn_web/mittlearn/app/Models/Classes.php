<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'is_active',
    ];

    public function accessCode()
    {
        return $this->hasMany(AccessCode::class, 'class_id', 'id')->where('is_active', 1);
    }
}
