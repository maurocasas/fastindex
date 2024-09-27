<?php

namespace App\Jobs\Pages;

use App\Models\Sitemap;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class UpdateOrInsertPage implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Sitemap $sitemap, protected string $url)
    {
        //
    }

    public function handle(): void
    {
        $url = $this->url;
        $path = Str::after($url, $this->sitemap->site->hostname);

        $this->sitemap->pages()->updateOrCreate(compact('url'), [
            ...compact('url'),
            'path' => blank($path) ? '/' : $path,
        ]);
    }
}
