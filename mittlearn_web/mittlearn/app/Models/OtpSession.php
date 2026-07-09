<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpSession extends Model
{
    // use HasFactory;
    protected $table = "otp_sessions";

    protected $fillable = ['session_id', 'otp', 'mobile_email', 'otp_verified', 'expire_at'];
}
