<?php

namespace App\Console\Commands;

use App\Jobs\ServiceAccounts\ListSites;
use App\Models\ServiceAccount;
use Illuminate\Console\Command;

class MonitorServiceAccounts extends Command
{
    protected $signature = 'app:monitor-service-accounts';
    protected $description = 'Iterate over service accounts to sync GSC sites with system';

    public function handle()
    {
        ServiceAccount::all()->each(function (ServiceAccount $serviceAccount) {
            dispatch(new ListSites($serviceAccount));
        });
    }
}
