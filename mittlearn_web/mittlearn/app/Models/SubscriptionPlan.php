<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    
    // Relationship: A plan can have many features

    public function subscriptionPlanFeature()
    {
        return $this->hasMany(SubscriptionPlanFeature::class, 'plan_id');
    }
    public function subscriptionPlanPrice()
    {
        return $this->hasMany(SubscriptionPlanPrice::class, 'plan_id');
    }
    public function subscriptionPlanPack()
    {
        return $this->hasMany(SubscriptionPlanPack::class, 'plan_id');
    }
    // Relationship: A plan can have many courses in a bundle
    public function courseBundle()
    {
        return $this->hasMany(SubscriptionPlanCourseBundle::class, 'plan_id');
    }

    // Relationship: A plan can have many purchases
    public function purchases()
    {
        return $this->hasMany(SubscriptionPurchase::class, 'plan_id');
    }
}
