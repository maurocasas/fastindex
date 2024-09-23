<?php

namespace App\Jobs\Sitemaps;

use App\Models\ServiceAccount;
use App\Models\Sitemap;
use App\Services\GoogleClientFactory;
use Google\Service\Webmasters;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PushSitemap implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Sitemap $sitemap)
    {
        //
    }

    public function handle(GoogleClientFactory $clientFactory): void
    {
        $serviceAccounts = $this->sitemap->site->service_accounts()->available()->get();

        foreach ($serviceAccounts as $serviceAccount) {
            $clientFactory->boot($serviceAccount);

            $webmasters = new Webmasters($clientFactory->client());

            $webmasters->sitemaps->submit($this->sitemap->site->gsc_name, $this->sitemap->path);

            $serviceAccount->logs()->create([
                'model_id' => $this->sitemap->id,
                'model_type' => Sitemap::class,
                'description' => "Registered sitemap {$this->sitemap->path}"
            ]);

            break;
        }
    }
}
