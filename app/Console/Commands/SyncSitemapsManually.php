<?php

namespace App\Console\Commands;

use App\Jobs\Pages\ListPagesBySitemap;
use App\Models\Sitemap;
use Illuminate\Console\Command;

class SyncSitemapsManually extends Command
{
    protected $signature = 'app:sync-sitemaps-manually';
    protected $description = 'Useful when working with big sitemaps';

    public function handle()
    {
        $sitemaps = Sitemap::all();

        if (!$this->confirm("Are you sure you want to dispatch {$sitemaps->count()} monitors?")) {
            return;
        }

        $progress = $this->output->createProgressBar($sitemaps->count());

        foreach ($sitemaps as $sitemap) {
            $progress->setMessage($sitemap->url);

            dispatch(new ListPagesBySitemap($sitemap));

            $this->line("Dispatched sitemap sync for {$sitemap->url}");

            $progress->advance();
        }

        $this->info('Done.');
    }
}
