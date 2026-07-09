<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserClass extends Model
{
    protected $table = 'user_classes';

    protected $fillable = [
        'user_role',
        'user_id',
        'medium_id',
        'class_id',
        'category_id',
        'book_series_id',
        'subject_id',
    ];
    public function classLabelName()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
