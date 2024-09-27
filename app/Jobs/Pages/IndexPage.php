<?php

namespace App\Jobs\Pages;

use App\Models\Page;
use App\Services\GoogleClientFactory;
use Carbon\Carbon;
use Google\Service\Exception;
use Google\Service\SearchConsole;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class IndexPage implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Page $page) {}

    public function handle(GoogleClientFactory $clientFactory): void
    {
        $this->page->update(['busy' => true]);

        $indexed = $this->page->status !== 'success';

        $serviceAccounts = $this->page->site->service_accounts()->available()->get();

        foreach ($serviceAccounts as $serviceAccount) {
            try {
                $clientFactory->boot($serviceAccount);

                $searchConsole = new SearchConsole($clientFactory->client());

                Log::debug(self::class, [$this->page->url]);

                $request = (new SearchConsole\InspectUrlIndexRequest);

                $request->setSiteUrl($this->page->site->gsc_name);
                $request->setInspectionUrl($this->page->url);

                $response = $searchConsole->urlInspection_index->inspect($request);
                $inspectionResult = $response->getInspectionResult();

                $indexStatus = $inspectionResult->getIndexStatusResult();

                $this->page->update([
                    'queried_at' => now(),
                    'coverage_state' => $indexStatus->coverageState,
                    'indexing_state' => $indexStatus->indexingState,
                    'crawled_at' => Carbon::parse($indexStatus->lastCrawlTime),
                ]);

                if (! $indexed && $this->page->status === 'success') {
                    $this->page->touch('indexed_at');
                }

                Log::debug(self::class, [$this->page->url, $inspectionResult]);

                $serviceAccount->logs()->create([
                    'model_id' => $this->page->id,
                    'model_type' => Page::class,
                    'description' => "Status updated for {$this->page->path}",
                ]);

                break;
            } catch (Exception $exception) {
                Log::error($exception);
            }
        }

        $this->page->update(['busy' => false]);
        $this->page->site->touch('updated_at');
    }
}
