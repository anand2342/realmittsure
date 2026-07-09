<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AccessCodeOlympiad extends Model
{
    protected $table = 'access_codes_olympiad';

    protected $fillable = [
        'serial_number',
        'book_series_id',
        'series_name',
        'class_id',
        'class_name',
        'subject_id',
        'subject_name',
        'access_code',
        'prefix',
        'code_length',
        'status',
        'generation_date',
        'expiration_date',
        'code_generator_name',
        'created_by',
        'user_id',
    ];
    public function usedBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function assigned()
    {
        return $this->hasOne(User::class);
    }
    public function class()
    {
        return $this->hasOne(Classes::class, 'id', 'class_id');
    }
    public function subject()
    {
        return $this->hasOne(Subject::class, 'id', 'subject_id');
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
