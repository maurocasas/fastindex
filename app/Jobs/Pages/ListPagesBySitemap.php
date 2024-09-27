<?php

namespace App\Jobs\Pages;

use App\Models\Sitemap;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use SimpleXMLElement;

class ListPagesBySitemap implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Sitemap $sitemap)
    {
        //
    }

    public function handle(): void
    {
        if ($this->sitemap->busy) {
            return;
        }

        $this->sitemap->toggleBusy();

        $xml_contents = @file_get_contents($this->sitemap->url);

        if (blank($xml_contents)) {
            $this->sitemap->toggleBusy(false);
            $this->fail('Sitemap not reachable');
            return;
        }

        try {
            $xml = new SimpleXMLElement($xml_contents);
        } catch (Exception $exception) {
            $this->sitemap->toggleBusy(false);
            $this->fail($exception);
            return;
        }

        $existingPages = $this->sitemap->site->pages()->pluck('url')->toArray();

        $pages = [];

        foreach ($xml->url as $item) {
            $url = (string) $item->loc;
            $path = Str::after($url, $this->sitemap->site->hostname);

            if (in_array($url, $existingPages)) {
                continue;
            }

            $pages[] = [
                'site_id' => $this->sitemap->site_id,
                'url' => (string)$item->loc,
                'path' => blank($path) ? '/' : $path,
            ];
        }

        DB::transaction(function () use ($pages) {
            DB::table('pages')->insert($pages);
        });

        $this->sitemap->toggleBusy(false);
    }

    private function upsertChunk($chunk)
    {

    }
}
