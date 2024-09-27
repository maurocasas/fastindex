<?php

namespace App\Console\Commands;

use App\Jobs\Pages\IndexPage;
use App\Models\Page;
use App\Models\ServiceAccount;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class DispatchIndexingBatches extends Command
{
    protected $signature = 'app:dispatch-indexing-batches';

    protected $description = 'Dispatch batches of indexing to GSC';

    public function handle()
    {
        ServiceAccount::all()->each(function (ServiceAccount $serviceAccount) {
            $this->info("Dispatching pages for service account {$serviceAccount->id}");

            $pages = Page::where('busy', false)
                ->where('not_found', false)
                ->whereIn('site_id', $serviceAccount->sites->pluck('id'))
                ->where(function (Builder $query) {
                    $query->whereNull('coverage_state')
                        ->orWhereNotIn('coverage_state', ['Submitted and indexed']);
                })
                ->orderBy('updated_at', 'asc')
                ->limit((int) file_get_contents(config_path('daily_quota')))
                ->get();

            $pages->each(function (Page $page) {
                $this->info("Checking indexing for {$page->url}");
                dispatch(new IndexPage($page));
            });
        });
    }
}
