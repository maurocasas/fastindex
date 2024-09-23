<?php

namespace App\Services;

use App\Models\ServiceAccount;
use Google\Client;
use Google\Exception;
use Google\Service\SearchConsole;
use Google\Service\Webmasters;
use Illuminate\Support\Facades\Log;

class GoogleClientFactory
{
    protected Client $client;

    /**
     * @throws Exception
     */
    public function boot(ServiceAccount $serviceAccount): void
    {
        $this->client = new Client;
        $this->client->setAuthConfig($serviceAccount->credentials);
        $this->client->addScope([Webmasters::WEBMASTERS]);
    }

    public function client(): Client
    {
        return $this->client;
    }

    public function validate(): bool
    {
        try {
            $searchConsole = new SearchConsole($this->client());
            $result = $searchConsole->sites->listSites();

            Log::debug(self::class, [$result]);

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);

            return false;
        }
    }
}
