<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanCourseBundle extends Model
{
    use HasFactory;

    protected $table = 'subscription_plan_course_bundle';
    
    protected $guarded = ['id'];
    
    // Relationship: A course bundle belongs to a plan
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}
