<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAsUserLog extends Model
{
    protected $fillable = [
        'user_id', 'uri', 'action_as', 'controller', 'method', 'json_data', 'log_date',
    ];

}
