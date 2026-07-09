<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    protected $fillable = ['platform', 'version', 'version_code', 'update_note', 'force_update'];
}
