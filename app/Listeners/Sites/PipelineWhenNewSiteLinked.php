<?php

namespace App\Listeners\Sites;

use App\Events\Sites\Linked;
use App\Jobs\Sitemaps\ListSitemaps;
use App\Jobs\Sites\FetchFavicon;

class PipelineWhenNewSiteLinked
{
    public function handle(Linked $event): void
    {
        dispatch(new ListSitemaps($event->site()));
        dispatch(new FetchFavicon($event->site()));
    }
}
