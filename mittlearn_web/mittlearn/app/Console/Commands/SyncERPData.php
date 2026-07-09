<?php
namespace App\Console\Commands;

use App\Services\DataSyncService;
use Illuminate\Console\Command;

class SyncERPData extends Command
{
    protected $signature   = 'sync:erp-data';
    protected $description = 'Sync data from ERP to LMS and log to erp_sync_lms';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DataSyncService $syncService)
    {
        $this->info('Starting user synchronization...');
        $syncService->syncUsers();
        $this->info('User synchronization completed.');
    }
}
