<?php

namespace App\Jobs\Sites;

use App\Models\Site;
use AshAllenDesign\FaviconFetcher\Facades\Favicon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AttemptFetchFavicon implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Site $site) {}

    public function handle(): void
    {
        $favicon = Favicon::fetch("http://{$this->site->hostname}")->getFaviconUrl() ?: '';

        $this->site->update(compact('favicon'));
    }
}
