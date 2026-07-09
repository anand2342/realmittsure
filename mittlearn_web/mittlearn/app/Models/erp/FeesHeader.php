<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeesHeader extends Model
{
    use HasFactory;

    protected $table = 'erp_fee_headers';
    protected $fillable = [
        'fee_name',
        'fees_type',
        'fees_cycle',
        'school_id',
    ];
}


