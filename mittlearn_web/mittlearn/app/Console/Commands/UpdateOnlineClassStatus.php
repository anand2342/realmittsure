<?php
namespace App\Console\Commands;

use App\Models\OnlineClass;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateOnlineClassStatus extends Command
{
    protected $signature   = 'update:online-class-status';
    protected $description = 'Update the status of online classes based on current time';

    public function handle()
    {
        $now = Carbon::now();

        // Update past classes
        OnlineClass::where('class_date', '<', $now->toDateString())
            ->orWhere(function ($query) use ($now) {
                $query->where('class_date', '=', $now->toDateString())
                    ->where('end_time', '<', $now->toTimeString());
            })
            ->update(['status' => 'past']);

        // Update ongoing classes
        OnlineClass::where('class_date', '=', $now->toDateString())
            ->where('start_time', '<=', $now->toTimeString())
            ->where('end_time', '>=', $now->toTimeString())
            ->update(['status' => 'ongoing']);

        // Update upcoming classes
        OnlineClass::where('class_date', '>', $now->toDateString())
            ->orWhere(function ($query) use ($now) {
                $query->where('class_date', '=', $now->toDateString())
                    ->where('start_time', '>', $now->toTimeString());
            })
            ->update(['status' => 'upcoming']);

        $this->info('Online class statuses updated successfully.');
    }
}
