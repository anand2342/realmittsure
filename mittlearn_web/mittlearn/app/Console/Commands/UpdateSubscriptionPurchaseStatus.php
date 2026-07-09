<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateSubscriptionPurchaseStatus extends Command
{
    protected $signature = 'subscriptions:update-status';
    protected $description = 'Update the status of expired subscriptions to "expired"';

    public function handle()
    {
        $now = now();

        // Update all subscriptions where end_date <= current time and status is not expired
        $updated = DB::table('subscription_purchases')
            ->where('end_date', '<=', $now)
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired', 'updated_at' => $now]);

        $this->info("$updated subscription(s) updated to expired.");
    }
}
