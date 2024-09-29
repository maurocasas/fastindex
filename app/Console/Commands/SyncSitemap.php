<?php

namespace App\Console\Commands;

use App\Models\Sitemap;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleXMLElement;

class SyncSitemap extends Command
{
    protected $signature = 'app:sync-sitemap {sitemap}';
    protected $description = 'Useful when working with big sitemaps';

    public function handle()
    {
        $sitemap = Sitemap::find($this->argument('sitemap'));

        if(blank($sitemap)) {
            $this->error("Sitemap not found.");
            return;
        }

        if($sitemap->busy) {
            $this->warn('Sitemap currently busy by another task.');
            return;
        }

        $sitemap->toggleBusy(true);

        $xml_contents = @file_get_contents($sitemap->url);

        $checksum = md5($xml_contents);

        if ($sitemap->checksum === $checksum) {
            $this->line("{$sitemap->url} hasn't changed. Skipping.");
        }

        if (blank($xml_contents)) {
            $sitemap->toggleBusy(false);
            $this->error("Sitemap {$sitemap->id} failed to fetch.");
            return;
        }

        try {
            $xml = new SimpleXMLElement($xml_contents);
        } catch (Exception $exception) {
            $sitemap->toggleBusy(false);
            $this->error("Sitemap {$sitemap->id} failed to parse XML.");
        }

        $pages = $this->output->createProgressBar($xml->count());

        foreach ($xml->url as $item) {
            $url = (string)$item->loc;

            $pages->setMessage($url);

            $path = Str::after($url, $sitemap->site->hostname);

            DB::table('pages')
                ->updateOrInsert(
                    ['site_id' => $sitemap->site_id, 'url' => $url],
                    compact('path')
                );

            $pages->advance();
        }

        $sitemap->update([
            'checksum' => $checksum,
            'busy' => false,
        ]);
    }
}
