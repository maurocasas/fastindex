<?php

namespace App\Console\Commands;

use App\Models\Sitemap;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleXMLElement;

class SyncSitemapsManually extends Command
{
    protected $signature = 'app:sync-sitemaps-manually';
    protected $description = 'Useful when working with big sitemaps';

    public function handle()
    {
        foreach (Sitemap::all() as $sitemap) {
            $xml_contents = @file_get_contents($sitemap->url);

            if (blank($xml_contents)) {
                $this->error("Sitemap {$sitemap->id} failed to fetch.");
                return;
            }

            try {
                $xml = new SimpleXMLElement($xml_contents);
            } catch (Exception $exception) {
                $this->error("Sitemap {$sitemap->id} failed to parse XML.");
            }

            foreach ($xml->url as $item) {
                $url = (string)$item->loc;
                $path = Str::after($url, $sitemap->site->hostname);

                DB::table('pages')
                    ->updateOrInsert(
                        ['site_id' => $sitemap->site_id, 'url' => $url],
                        compact('path')
                    );
            }
        }
    }
}
