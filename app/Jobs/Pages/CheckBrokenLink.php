<?php

namespace App\Jobs\Pages;

use App\Events\Pages\BrokenLink;
use App\Models\Page;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class CheckBrokenLink implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Page $page) {}

    public function handle(): void
    {
        if (Http::get($this->page->url)->notFound()) {
            event(new BrokenLink($this->page));
        }
    }
}
