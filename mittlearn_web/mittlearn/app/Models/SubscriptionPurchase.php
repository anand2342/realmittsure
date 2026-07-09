<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'course_id',
        'start_date',
        'end_date',
        'plan_json',
        'courses_json',
        'transaction_id',
        'status',
    ];

    protected $guarded = ['id'];

    // Relationship: A purchase belongs to a plan
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    // Relationship: A purchase belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function planDetails()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
    public function planPrice()
    {
        return $this->hasOne(SubscriptionPlanPrice::class, 'plan_id', 'plan_id');
    }
    public function transaction()
    {
        return $this->hasOne(TransactionLog::class, 'plan_id', 'plan_id');
    }
    public function planFeatures()
    {
        return $this->hasMany(SubscriptionPlanFeature::class, 'plan_id', 'plan_id');
    }
}
