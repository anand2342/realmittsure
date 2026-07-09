<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessCodeLog extends Model
{
    protected $table = 'access_code_logs';

    protected $fillable = ['user_id', 'title', 'action_as', 'action_by', 'json_data'];

}
