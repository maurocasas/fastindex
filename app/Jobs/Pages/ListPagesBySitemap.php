<?php

namespace App\Jobs\Pages;

use App\Models\Sitemap;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
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
        if($this->sitemap->busy)
            return;

        $this->sitemap->toggleBusy();

        $xml_contents = @file_get_contents($this->sitemap->url);

        if (blank($xml_contents)) {
            $this->sitemap->toggleBusy();
            $this->fail('Sitemap not reachable');
            return;
        }

        $xml = new SimpleXMLElement($xml_contents);

        foreach ($xml->url as $item) {
            dispatch(new UpdateOrInsertPage($this->sitemap, (string) $item->loc));
        }

        $this->sitemap->toggleBusy();
    }
}
