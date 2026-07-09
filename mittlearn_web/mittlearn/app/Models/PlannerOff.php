<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerOff extends Model
{
    protected $fillable = [
        'planner_id',
        'date',
    ];
    public $timestamps = false;
    public function planner()
    {
        return $this->belongsTo(Planner::class);
    }

}
