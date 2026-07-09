<?php

namespace App\Console\Commands;

use App\Models\AccessCode;
use Carbon\Carbon; // Replace with your model name
use Illuminate\Console\Command;

class UpdateAccessCodeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-code:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update access code status to expired if end_date matches today';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $previousDate = now()->subDay()->toDateString(); // This will be 1 day before today
        $updated = AccessCode::where('end_date', $previousDate)
            ->update(['status' => 'expired']);

        $this->info("$updated access codes updated to expired for $previousDate.");

        return Command::SUCCESS;

    }
}
