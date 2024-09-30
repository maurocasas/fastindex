<?php

namespace App\Jobs\Pages;

use App\Models\Sitemap;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class ListPagesBySitemap implements ShouldQueue
{
    use Queueable;

    public $timeout = 900;

    public function __construct(protected Sitemap $sitemap)
    {
        //
    }

    public function handle(): void
    {
        Artisan::call("app:sync-sitemap {$this->sitemap->id}");
    }
}
