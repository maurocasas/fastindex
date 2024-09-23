<?php

namespace App\Listeners;

use App\Events\Users\LoggedIn;

class TapLoggedIn
{
    public function handle(LoggedIn $event): void
    {
        $event->user()->touch('last_login_at');
    }
}
