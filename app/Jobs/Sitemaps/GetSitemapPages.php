<?php

namespace App\Jobs\Sitemaps;

use App\Jobs\UpdateOrInsertPage;
use App\Models\Sitemap;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class GetSitemapPages implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Sitemap $sitemap)
    {
        //
    }

    public function handle(): void
    {
        if($this->sitemap->busy)
            return;

        $this->sitemap->toggleBusy();

        $response = Http::get($this->sitemap->url);

        if ($response->failed()) {
            $this->fail('Sitemap not reachable');
            return;
        }

        $xml = new SimpleXMLElement($response->body());

        foreach ($xml->url as $item) {
            dispatch(new UpdateOrInsertPage($this->sitemap, (string) $item->loc));
        }

        $this->sitemap->toggleBusy();
    }
}
