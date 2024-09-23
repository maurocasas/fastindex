<?php

namespace App\Events\Sitemaps;

use App\Models\Sitemap;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(protected Sitemap $sitemap)
    {
    }

}
