<?php

namespace App\Jobs\Sitemaps;

use App\Models\Site;
use App\Services\GoogleClientFactory;
use Google\Service\Webmasters;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RemoveSitemap implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Site $site, protected string $sitemap)
    {
        //
    }

    public function handle(GoogleClientFactory $clientFactory): void
    {
        $serviceAccounts = $this->site->service_accounts()->available()->get();

        foreach ($serviceAccounts as $serviceAccount) {
            $clientFactory->boot($serviceAccount);

            $webmasters = new Webmasters($clientFactory->client());

            $webmasters->sitemaps->delete($this->site->gsc_name, $this->sitemap);

            $serviceAccount->logs()->create([
                'model_id' => $this->site->id,
                'model_type' => Site::class,
                'description' => "Removed sitemap {$this->sitemap}",
            ]);

            break;
        }
    }
}
