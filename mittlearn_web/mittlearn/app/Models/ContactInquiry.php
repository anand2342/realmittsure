<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $table = 'contact_inquiries';

    protected $fillable = ['name', 'email', 'mobile_no', 'subject', 'message', 'ip', 'read_at', 'response_message', 'resolved_by', 'resolved_at'];

}
