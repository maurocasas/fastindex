<?php

namespace App\Events\Users;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoggedIn
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected User $user)
    {
        //
    }

    public function user(): User
    {
        return $this->user;
    }
}
