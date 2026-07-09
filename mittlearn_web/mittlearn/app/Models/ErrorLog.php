<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;
    protected $table = 'error_logs';
    protected $fillable = [
        'error_message', 'error_code', 'error_file', 'error_line',
        'error_trace', 'url', 'method', 'request_data', 'user_id'
    ];
}

