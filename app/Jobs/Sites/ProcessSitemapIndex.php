<?php

namespace App\Jobs\Sites;

use App\Models\Sitemap;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class ProcessSitemapIndex implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Sitemap $sitemap)
    {
        //
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        Log::debug('Processing sitemap index');

        $xml = new SimpleXMLElement(@file_get_contents($this->sitemap->url));

        foreach ($xml->sitemap as $sitemap) {
            dispatch(new RegisterSitemap(
                $this->sitemap->site,
                (string)$sitemap->loc,
                $this->sitemap->only('downloaded_at', 'submitted_at')
            ));
        }
    }
}
