<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolAssignedClass extends Model
{
    const UPDATED_AT = null;
    protected $table = 'school_classes';
    protected $fillable = [
        'school_id',
        'class_id',
    ];

    public function class()
    {
        return $this->hasOne(SchoolClass::class, 'id', 'class_id');
    }
}
