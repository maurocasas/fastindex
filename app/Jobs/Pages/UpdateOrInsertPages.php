<?php

namespace App\Jobs\Pages;

use App\Models\Site;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateOrInsertPages implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Site $site, protected array $items)
    {
        //
    }

    public function handle(): void
    {
        foreach ($this->items as $item) {
            $url = $item;
            $path = Str::after($url, $this->site->hostname);

            DB::table('pages')
                ->updateOrInsert(
                    ['site_id' => $this->site->id, 'url' => $url],
                    compact('path')
                );
        }
    }
}
