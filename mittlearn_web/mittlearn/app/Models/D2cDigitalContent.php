<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class D2cDigitalContent extends Model
{
    protected $table = 'd2c_digital_contents';
    protected $fillable = [
        'd2c_content_id',
        'category_id',
        'sub_category_id',
        'class_id',
        'course_id',
        'qr_name',
        'qr_code_link',
        'created_by',
    ];
    public function className()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
}
