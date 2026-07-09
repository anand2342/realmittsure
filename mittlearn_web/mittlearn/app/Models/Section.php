<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sections';
    protected $fillable = [
        'section_name',
        'is_active',
    ];
    public function schoolDetails()
    {
        return $this->hasOne(Schools::class, 'user_id', 'school_id');
    }
    public function className()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
