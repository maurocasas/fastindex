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

        $pages = [];

        foreach ($xml->url as $item) {
            $pages[] = (string) $item->loc;
        }

        LazyCollection::make($pages)
            ->chunk(1000)
            ->each(fn($chunk) => $this->upsertChunk($chunk));

        $this->sitemap->toggleBusy(false);
    }

    private function upsertChunk($chunk)
    {
        DB::transaction(function () use ($chunk) {
            $values = $chunk->map(function ($url) {
                $path = Str::after($url, $this->sitemap->site->hostname);

                return [
                    'site_id' => $this->sitemap->site_id,
                    'url' => $url,
                    'path' => blank($path) ? '/' : $path,
                ];
            })->toArray();

            $this->sitemap->site->pages()->upsert($values, ['url']);
        });
    }
}
