<?php

namespace App\Jobs\Sites;

use App\Jobs\Pages\ListPagesBySitemap;
use App\Models\Site;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterSitemap implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Site $site, protected string $url, protected array $attributes = [])
    {
        //
    }

    public function handle(): void
    {
        Log::debug('Registering sitemap', [ $this->url ]);

        $is_index = $this->isSitemapIndex($this->url);

        $sitemap = $this->site->sitemaps()->updateOrCreate([
            'url' => $this->url
        ], [
            'url' => $this->url,
            'is_index' => $is_index,
            ... $this->attributes
        ]);

        if($is_index) {
            dispatch(new ProcessSitemapIndex($sitemap));
            return;
        }

        dispatch(new ListPagesBySitemap($sitemap));
    }

    protected function isSitemapIndex(string $url): bool
    {
        return Str::contains(@file_get_contents($url), '<sitemapindex');
    }
}
