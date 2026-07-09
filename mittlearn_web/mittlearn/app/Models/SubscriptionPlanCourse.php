<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanCourse extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    // Relationship: A course bundle belongs to a plan
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
    
}
