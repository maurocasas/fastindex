<?php

namespace App\Console\Commands;

use App\Jobs\Pages\UpdateOrInsertPage;
use App\Models\Sitemap;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;

class SyncSitemap extends Command
{
    protected $signature = 'app:sync-sitemap {sitemap} {--batch}';
    protected $description = 'Useful when working with big sitemaps';

    public function handle()
    {
        $sitemap = Sitemap::find($this->argument('sitemap'));

        if (blank($sitemap)) {
            Log::warning("Sitemap not found", [$this->argument('sitemap')]);
            $this->error("Sitemap not found.");
            return;
        }

        try {
            if ($sitemap->busy) {
                $this->warn("Sitemap busy!");
                Log::warning('Sitemap busy!', [$this->argument('sitemap')]);
                return;
            }

            Log::info('Syncing sitemap', [$this->argument('sitemap')]);

            $xml_contents = @file_get_contents($sitemap->url);

            $checksum = md5($xml_contents);

            if ($sitemap->checksum === $checksum) {
                $this->line("{$sitemap->url} hasn't changed. Skipping.");
                Log::info('Unchanged sitemap', [$this->argument('sitemap')]);
                return;
            }

            $sitemap->toggleBusy(true);

            if (blank($xml_contents)) {
                throw new Exception("Sitemap {$sitemap->id} failed to fetch.");
            }

            $xml = new SimpleXMLElement($xml_contents);

            $pages = $this->output->createProgressBar($xml->count());

            foreach ($xml->url as $item) {
                $url = (string)$item->loc;

                $pages->setMessage($url);

                $path = Str::after($url, $sitemap->site->hostname);

                if($this->option('batch')) {
                    DB::table('pages')
                        ->updateOrInsert(
                            ['site_id' => $sitemap->site_id, 'url' => $url],
                            compact('path')
                        );
                } else {
                    dispatch(new UpdateOrInsertPage($sitemap->site, $url));
                }

                $pages->advance();
            }

            if($this->option('batch')) {
                $this->info("Sitemap {$sitemap->id} done syncing.");
                Log::info('Finished sync', [$this->argument('sitemap')]);
            } else {
                $this->info("Sitemap {$sitemap->id} dispatched all pages for syncing.");
                Log::info('Finished sync dispatching', [$this->argument('sitemap')]);
            }

            $sitemap->update(compact('checksum'));
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
            Log::error($exception->getMessage(), [$this->argument('sitemap')]);
        }

        $sitemap->toggleBusy(false);
    }
}
