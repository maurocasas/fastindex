<?php

namespace App\Jobs\Sites;

use App\Models\Site;
use App\Services\GoogleClientFactory;
use Carbon\Carbon;
use Google\Exception;
use Google\Service\Webmasters;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
        $serviceAccounts = $this->site->service_accounts()->get();

        foreach ($serviceAccounts as $serviceAccount) {
            $clientFactory->boot($serviceAccount);

            $webmasters = new Webmasters($clientFactory->client());

            foreach ($webmasters->sitemaps->listSitemaps($this->site->gsc_name) as $sitemapItem) {
                dispatch(new RegisterSitemap($this->site, $sitemapItem->getPath(), [
                    'downloaded_at' => Carbon::parse($sitemapItem->getLastDownloaded()),
                    'submitted_at' => Carbon::parse($sitemapItem->getLastSubmitted()),
                ]));

                $serviceAccount->logs()->create([
                    'description' => 'Sitemaps synced',
                    'model_id' => $this->site->id,
                    'model_type' => Site::class,
                ]);
            }
        }

        $this->site->refreshing_sitemaps = false;
        $this->site->save();
    }
}
