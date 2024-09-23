<?php

namespace App\Listeners\Pages;

use App\Events\Pages\Indexed;

class ToggleIndexedTimestamp
{
    public function handle(Indexed $event): void
    {
        if (blank($event->page()->indexed_at)) {
            $event->page()->touch('indexed_at');
        }
    }
}
