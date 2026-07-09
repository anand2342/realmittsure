<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessCodeEmbibe extends Model
{
    protected $table = 'access_codes_embibe';

    protected $fillable = [
        'embibe_id',
        'licence_key',
        'ip',
        'device_id',
        'activation_date',
        'activation_updatedAt',
        'org_id',
        'activation_limit',
        'licence_expiry',
        'content_bundle',
        'content_bundle_id',
        'notes',
        'config',
        'requestBy',
        'requestTeam',
        'requestPersonName',
        'customerName',
        'platform',
        'board',
        'grades',
        'resolution',
        'license_createdAt',
        'license_updatedAt',
        'type',
        'status',
        'created_by',
        'school_id',
        'user_id',
        'is_distribute'
    ];

    public function usedBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by', 'id');
    }
    public function schoolName()
    {
        return $this->belongsTo(User::class, 'school_id')->with('schoolDetails');
    }
    public function assigned()
    {
        return $this->hasOne(User::class);
    }
    public function class()
    {
        return $this->hasOne(Classes::class, 'id', 'class_id');
    }

    public function medium()
    {
        return $this->hasOne(Medium::class, 'id', 'medium_id');
    }
    public function board()
    {
        return $this->hasOne(Board::class, 'id', 'board_id');
    }
    public function school()
    {
        return $this->hasOne(Schools::class, 'id', 'school_id');
    }
    public function usedAccessCodes()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function accessCodeLog()
    {
        return $this->hasOne(AccessCodeLog::class, 'user_id', 'user_id');
    }
    public function bookSeries()
    {
        return $this->hasOne(BookSeries::class, 'id', 'book_series_id');
    }
}
