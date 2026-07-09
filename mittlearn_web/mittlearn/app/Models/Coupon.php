<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_cart_value',
        'max_cart_value',
        'upto_discount',
        'is_active',
        'is_clubable',
        'applicable_for',
        'applicable_for_ids',
        'usage_limit',
        'used_count',
        'per_user_limit',
        'uses_frequency',
        'start_date',
        'end_date',
    ];


    protected $dates = ['start_date', 'end_date'];

}
