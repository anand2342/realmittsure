<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanFeature extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relationship: A feature can belong to many plans via SubscriptionBenefit
    public function plans()
    {
        return $this->hasMany(SubscriptionBenefit::class, 'feature_id');
    }
}
