<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'online_class_id', 'user_id', 'join_time', 'user_agent', 'ip_address',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('userAdditionalDetail');
    }
    public $timestamps = false;
}
