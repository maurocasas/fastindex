<?php

namespace App\Jobs\Sitemaps;

use App\AlertType;
use App\Models\Sitemap;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
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
        $response = Http::get($this->sitemap->url);

        if ($response->failed()) {
            $this->sitemap->alert('Unreachable sitemap URL.', AlertType::ERROR);
            return;
        }

        $xml = new SimpleXMLElement($response->body());

        foreach ($xml->url as $item) {
            $url = (string) $item->loc;
            $path = Str::after($url, $this->sitemap->site->hostname);

            $this->sitemap->pages()->updateOrCreate(compact('url'), [
                ... compact('url'),
                'path' => blank($path) ? '/' : $path
            ]);
        }
    }

}
