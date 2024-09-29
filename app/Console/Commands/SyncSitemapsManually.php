<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Sitemap;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleXMLElement;

class SyncSitemapsManually extends Command
{
    protected $signature = 'app:sync-sitemaps-manually';
    protected $description = 'Useful when working with big sitemaps';

    public function handle()
    {
        $sitemaps = Sitemap::all();

        $progress = $this->output->createProgressBar($sitemaps->count());

        foreach ($sitemaps as $sitemap) {
            $progress->setMessage($sitemap->url);

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

            $upsert = [];

            foreach ($xml->url as $item) {
                $url = (string)$item->loc;

//                $this->line("Processing page {$url}");

                $path = Str::after($url, $sitemap->site->hostname);

                $upsert[] = [
                    'site_id' => $sitemap->site_id,
                    'path' => $path,
                    'url' => $url
                ];

//                DB::table('pages')
//                    ->updateOrInsert(
//                        ['site_id' => $sitemap->site_id, 'url' => $url],
//                        compact('path')
//                    );

//                $pages->advance();
            }

            $chunks = collect($upsert)->chunk(1000);

            $pages = $this->output->createProgressBar($chunks->count());

            $chunks->each(function (Collection $chunk) use($pages) {
                Page::upsert($chunk, ['url']);
                $pages->advance();
            });

            $progress->advance();
        }
    }
}
