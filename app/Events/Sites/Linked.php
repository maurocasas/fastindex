<?php

namespace App\Events\Sites;

use App\Models\Site;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Linked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(protected Site $site) {}

    public function site(): Site
    {
        return $this->site;
    }
}
