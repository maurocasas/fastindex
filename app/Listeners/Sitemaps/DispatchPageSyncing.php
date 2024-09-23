<?php

namespace App\Listeners\Sitemaps;

use App\Events\Sitemaps\Synced;
use App\Jobs\Sitemaps\GetSitemapPages;

class DispatchPageSyncing
{
    public function handle(Synced $event): void
    {
        dispatch(new GetSitemapPages($event->sitemap()));
    }
}
