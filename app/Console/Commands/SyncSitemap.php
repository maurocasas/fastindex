<?php

namespace App\Console\Commands;

use App\Models\Sitemap;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            Log::warning("Sitemap not found", [ $this->argument('sitemap') ]);
            $this->error("Sitemap not found.");
            return;
        }

        if($sitemap->busy) {
            Log::warning("Sitemap busy!", [ $this->argument('sitemap') ]);
            $this->warn('Sitemap currently busy by another task.');
            return;
        }

        Log::info('Syncing sitemap', [ $this->argument('sitemap') ]);

        $sitemap->toggleBusy(true);

        $xml_contents = @file_get_contents($sitemap->url);

        $checksum = md5($xml_contents);

        if ($sitemap->checksum === $checksum) {
            $this->line("{$sitemap->url} hasn't changed. Skipping.");
            Log::info('Unchanged sitemap', [ $this->argument('sitemap') ]);
            return;
        }

        if (blank($xml_contents)) {
            $sitemap->toggleBusy(false);
            $this->error("Sitemap {$sitemap->id} failed to fetch.");
            Log::error('Unreachable sitemap', [ $this->argument('sitemap') ]);
            return;
        }

        try {
            $xml = new SimpleXMLElement($xml_contents);
        } catch (Exception $exception) {
            $sitemap->toggleBusy(false);
            $this->error("Sitemap {$sitemap->id} failed to parse XML.");
            Log::error('Invalid sitemap XML', [ $this->argument('sitemap') ]);
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
