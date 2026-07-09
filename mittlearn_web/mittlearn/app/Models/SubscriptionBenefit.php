<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionBenefit extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relationship: A benefit belongs to a plan
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    // Relationship: A benefit belongs to a feature
    public function feature()
    {
        return $this->belongsTo(SubscriptionPlanFeature::class, 'feature_id');
    }
}
