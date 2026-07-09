<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['holiday_name', 'country', 'from_date', 'to_date', 'holiday_type', 'state_id', 'day', 'is_active'];
}
