<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = ['user_id', 'updated_by', 'approved_by','title','uri','action_as','action_date','json_data','log_type','log_date'];

   

}
