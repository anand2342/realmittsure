<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'sent_to',
        'template_key',
        'message',
        'triggered_by',
        'sender_user_id',
        'related_school_id',
        'related_rm_id',
        'status',
        'error_message',
    ];

    // Relationships (optional but useful for admin views)
    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function school()
    {
        return $this->belongsTo(Schools::class, 'related_school_id');
    }

    public function rm()
    {
        return $this->belongsTo(User::class, 'related_rm_id');
    }
}