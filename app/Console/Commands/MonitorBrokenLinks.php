<?php

namespace App\Console\Commands;

use App\Jobs\Pages\CheckIfPageIsNotFound;
use App\Models\Page;
use Illuminate\Console\Command;

class MonitorBrokenLinks extends Command
{
    protected $signature = 'app:monitor-broken-links';

    protected $description = 'Iterate pages to find broken links';

    public function handle()
    {
        Page::where('not_found', false)->each(function (Page $page) {
            dispatch(new CheckIfPageIsNotFound($page));
        });
    }
}
