<?php

namespace App\Jobs\Sitemaps;

use App\Models\Sitemap;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
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
            $this->fail('Sitemap not reachable');
            return;
        }

        $pages = [];

        $xml = new SimpleXMLElement($response->body());

        foreach ($xml->url as $item) {
            $url = (string)$item->loc;
            $path = Str::after($url, $this->sitemap->site->hostname);

            $pages[] = [
                ...compact('url'),
                'path' => blank($path) ? '/' : $path,
            ];
        }

        foreach ($pages as $page) {
            DB::table('pages')->updateOrInsert(
                [
                    'site_id' => $this->sitemap->site_id,
                    'sitemap_id' => $this->sitemap->id,
                    'url' => $page['url']
                ],
                [
                    'path' => $page['path'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
