<?php

namespace App\Models\erp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsessionCategory extends Model
{
    use HasFactory;

    protected $table = 'erp_consession_categories';
    protected $fillable = [
        'category',
        'school_id',
    ];
}


