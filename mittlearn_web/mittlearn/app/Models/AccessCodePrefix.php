<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessCodePrefix extends Model
{
    use HasFactory;
    protected $table = 'access_code_prefixes';
    protected $fillable = ['prefix', 'description', 'is_active'];

}
