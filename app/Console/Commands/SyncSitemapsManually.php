<?php

namespace App\Console\Commands;

use App\Models\Sitemap;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\NullOutput;

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

            Artisan::call(
                "app:sync-sitemap {$sitemap->id}",
                [],
                outputBuffer: new NullOutput()
            );

            $progress->advance();
        }

        $this->info('Done.');
    }
}
