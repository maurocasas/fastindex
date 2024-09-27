<?php

namespace App\Jobs\Sites;

use App\Jobs\Pages\ListPagesBySitemap;
use App\Models\Site;
use App\Models\Sitemap;
use App\Services\GoogleClientFactory;
use Carbon\Carbon;
use Google\Exception;
use Google\Service\Webmasters;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ListSitemapsBySite implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Site $site)
    {
        //
    }

    /**
     * @throws Exception
     */
    public function handle(GoogleClientFactory $clientFactory): void
    {
        $serviceAccounts = $this->site->service_accounts()->available()->get();

        foreach ($serviceAccounts as $serviceAccount) {
            $clientFactory->boot($serviceAccount);

            $webmasters = new Webmasters($clientFactory->client());

            foreach ($webmasters->sitemaps->listSitemaps($this->site->gsc_name) as $sitemapItem) {
                Log::debug(self::class, [
                    'url' => $sitemapItem->getPath(),
                    'last_download_at' => Carbon::parse($sitemapItem->getLastDownloaded()),
                    'submitted_at' => Carbon::parse($sitemapItem->getLastSubmitted()),
                    'pending' => $sitemapItem->getIsPending(),
                    'warnings' => $sitemapItem->getWarnings(),
                    'errors' => $sitemapItem->getErrors(),
                    'content' => $sitemapItem->getContents(),
                ]);

                /** @var Webmasters\WmxSitemapContent $content */
                $content = $sitemapItem->getContents();

                $payload = [
                    'url' => $sitemapItem->getPath(),
                    'last_download_at' => Carbon::parse($sitemapItem->getLastDownloaded()),
                    'submitted_at' => Carbon::parse($sitemapItem->getLastSubmitted()),
                    'pending' => $sitemapItem->getIsPending(),
                    'warnings' => $sitemapItem->getWarnings(),
                    'errors' => $sitemapItem->getErrors(),
                    'submitted' => collect($content)->map(fn ($item) => $item->getSubmitted())->sum(),
                    'indexed' => collect($content)->map(fn ($item) => $item->getIndexed())->sum(),
                ];

                /** @var Sitemap $sitemap */
                $sitemap = $this->site->sitemaps()->updateOrCreate([
                    'url' => $sitemapItem->getPath(),
                ], $payload);

                $serviceAccount->logs()->create([
                    'description' => 'Sitemaps synced',
                    'model_id' => $sitemap->id,
                    'model_type' => Sitemap::class,
                ]);

                dispatch(new ListPagesBySitemap($sitemap));
            }
        }

        $this->site->refreshing_sitemaps = false;
        $this->site->save();
    }
}
