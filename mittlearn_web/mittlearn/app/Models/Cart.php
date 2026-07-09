<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'session_id',
        'item_type',
        'item_id',
        'course_id',
        'quantity',
        'full_price',
        'price',
        'discount',
        'coupon_code',
        'added_at',
        'type',
        'status',
        'created_by_admin',
    ];
    protected $dates = ['added_at'];

    public function getCourses()
    {
        return $this->hasOne(Course::class, 'id', 'course_id')->with('getCategoryCourse');
    }
}
