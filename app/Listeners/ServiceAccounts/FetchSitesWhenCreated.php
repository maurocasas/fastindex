<?php

namespace App\Listeners\ServiceAccounts;

use App\Events\ServiceAccounts\Created;
use App\Jobs\ServiceAccounts\ListSites;

class FetchSitesWhenCreated
{
    public function handle(Created $event): void
    {
        dispatch(new ListSites($event->serviceAccount()));
    }
}
