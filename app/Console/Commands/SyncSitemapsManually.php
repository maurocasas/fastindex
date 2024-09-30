<?php

namespace App\Console\Commands;

use App\Models\Sitemap;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Process\Process;

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

            $process = new Process([PHP_BINARY, " ", base_path('artisan'), " ", "app:sync-sitemap {$sitemap->id}"]);
            $process->start();

            Log::debug('Dispatched sitemap sync', [
                $sitemap->id,
                [PHP_BINARY, base_path('artisan'), " app:sync-sitemap {$sitemap->id}"],
                $process->getPid()
            ]);

            $this->line("Dispatched sitemap sync for {$sitemap->url}");

            $progress->advance();
        }

        $this->info('Done.');
    }
}
