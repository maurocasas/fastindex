<?php

namespace App\Jobs\ServiceAccounts;

use App\Events\Sites\Linked;
use App\Models\ServiceAccount;
use App\Models\Site;
use App\Services\GoogleClientFactory;
use Google\Service\Exception;
use Google\Service\SearchConsole;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ListSites implements ShouldQueue
{
    use Queueable;

    public function __construct(protected ServiceAccount $serviceAccount) {}

    /**
     * @throws Exception
     * @throws \Google\Exception
     */
    public function handle(GoogleClientFactory $clientFactory): void
    {
        $clientFactory->boot($this->serviceAccount);

        $searchConsole = new SearchConsole($clientFactory->client());

        $sites = [];

        foreach ($searchConsole->sites->listSites()->getSiteEntry() as $site) {
            $gsc_name = $site->getSiteUrl();

            $sites[] = Site::firstOrCreate(compact('gsc_name'), [
                ...compact('gsc_name'),
                'hostname' => str_replace('sc-domain:', '', $gsc_name),
            ])->id;
        }

        $sync = $this->serviceAccount->sites()->syncWithoutDetaching($sites);

        foreach ($sync['attached'] as $site_id) {
            event(new Linked(Site::find($site_id)));
        }
    }
}
