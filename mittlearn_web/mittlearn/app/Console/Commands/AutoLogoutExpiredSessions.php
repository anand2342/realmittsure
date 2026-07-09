<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserLoginLog;
use Carbon\Carbon;

class AutoLogoutExpiredSessions extends Command
{
    protected $signature = 'sessions:autologout';

    protected $description = 'Automatically logs out users whose sessions expired but were never marked as logged out.';

    public function handle()
    {
        $timeoutMinutes = config('session.lifetime', 120); // default 120 min if not set
        $cutoff = Carbon::now()->subMinutes($timeoutMinutes + 10); // add a buffer

        $count = UserLoginLog::whereNull('logout_at')
            ->where('created_at', '<=', $cutoff)
            ->update(['logout_at' => now()]);

        $this->info("Updated $count expired session(s).");
    }
}
