<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AccessCode extends Model
{
    use SoftDeletes;
    protected $table = 'access_codes';

    protected $fillable = ['book_series_id', 'type', 'school_id', 'board_id', 'medium_id', 'class_id', 'book_set_id', 'subject_id', 'sku', 'status', 'generated_by', 'start_date', 'end_date', 'generation_type', 'prefix_code', 'postfix_code', 'access_code', 'user_id'];

    public function usedBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by', 'id');
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
