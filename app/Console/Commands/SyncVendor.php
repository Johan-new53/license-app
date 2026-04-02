<?php

namespace App\Console\Commands;

use App\Services\VendorSyncService;
use Illuminate\Console\Command;

class SyncVendor extends Command
{
    protected $signature = 'sync:vendor';
    protected $description = 'Sync vendor dari API PRF';

    public function handle(VendorSyncService $vendorSyncService)
    {
        $result = $vendorSyncService->sync('cronjob');

        $this->info($result['message']);

        return self::SUCCESS;
    }
}
