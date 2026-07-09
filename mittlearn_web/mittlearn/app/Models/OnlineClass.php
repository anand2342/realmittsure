<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'agenda',
        'instructor_id',
        'class_id',
        'subject_id',
        'class_date',
        'start_time',
        'end_time',
        'join_link',
        'status',
        'parent_id',
    ];
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function joinLogs()
    {
        return $this->hasMany(JoinLog::class, 'online_class_id');
    }
   
}
