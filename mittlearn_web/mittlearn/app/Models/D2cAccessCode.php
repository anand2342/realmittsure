<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class D2cAccessCode extends Model
{
    use SoftDeletes;
    protected $table = 'd2c_access_code';

    protected $fillable = ['d2c_digital_content_id', 'access_code', 'status', 'user_id'];
}
