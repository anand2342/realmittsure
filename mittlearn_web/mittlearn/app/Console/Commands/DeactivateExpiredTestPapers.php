<?php

namespace App\Console\Commands;

use App\Models\TestPaper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeactivateExpiredTestPapers extends Command
{
    protected $signature = 'test-papers:deactivate-expired';
    protected $description = 'Deactivate test papers whose end date has passed';

    public function handle()
    {
        $now = Carbon::now();

        $expiredPapers = TestPaper::where('is_active', 1)
            ->where('end_date_time', '<', $now)
            ->get();

        if ($expiredPapers->isEmpty()) {
            $this->info('No expired test papers found.');
            return;
        }

        foreach ($expiredPapers as $paper) {
            $paper->update(['is_active' => 2]);
            $this->info("Test Paper ID {$paper->id} deactivated.");
        }

        $this->info('Expired test papers deactivated successfully.');
    }
}
