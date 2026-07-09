<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmSchoolAddon extends Model
{
    protected $table = 'crm_school_addons';

    protected $fillable = [
        'user_id',
        'class_name',
        'series_name',
        'add_ons',
        'mittleance',
        'techlite',
        'created_by',
        'codes_assigned',
        'codes_assigned_at',
        'assigned_school_id',
        'assigned_data',
    ];

    protected $casts = [
        'add_ons' => 'array',  // auto encode/decode JSON
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function school()
    {
        return $this->belongsTo(Schools::class, 'user_id', 'user_id');
    }
}
