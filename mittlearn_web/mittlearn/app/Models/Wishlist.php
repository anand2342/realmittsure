<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'item_type',
        'item_id',
        'course_id',
        'quantity',
        'added_at',
        'type',
        'status',
        'created_by_admin',
    ];
    protected $dates = ['added_at'];

}
