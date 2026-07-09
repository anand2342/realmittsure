<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TestParticipent extends Model
{
    use HasFactory;
    protected $fillable = [
        'school_id',
        'test_id',
        'user_id',
        'class_id',
        'created_by',
        'status',
        'is_attempted',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('userAdditionalDetail');
    }
    public function userAdditionalDetail()
    {
        return $this->hasOne(UserAdditionalDetail::class, 'user_id', 'user_id');
    }
    public function testPaper()
    {
        return $this->hasOne(TestPaper::class, 'id', 'test_id')->with('Subject');
    }
    public function testPaperQuestions()
    {
        return $this->hasOne(TestPaperQuestion::class, 'paper_id', 'test_id');
    }
    public function result()
    {
        return $this->hasOne(TestPaperResult::class, 'test_id', 'test_id')
            ->where('user_id',  Auth::id());
    }
}
