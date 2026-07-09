<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{

    use HasFactory;
    protected $fillable = [
        'plan_id',
        'user_id',
        'txn_id',
        'coupon_id',
        'payment_gateway',
        'payment_id',
        'cart',
        'total_amount',
        'currency',
        'quantity',
        'transaction_for',
        'payment_details',
        'payment_state',
        'payer_payment_method',
        'payer_status',
    ];
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
    public function userDetail()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
