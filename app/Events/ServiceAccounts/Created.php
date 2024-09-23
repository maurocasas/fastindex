<?php

namespace App\Events\ServiceAccounts;

use App\Models\ServiceAccount;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(protected ServiceAccount $serviceAccount)
    {
    }

    public function serviceAccount(): ServiceAccount
    {
        return $this->serviceAccount;
    }
}
