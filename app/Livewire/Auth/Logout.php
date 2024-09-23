<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    public function exit()
    {
        auth()->logout();
        $this->redirectRoute('login');

    }

    public function render()
    {
        return view('livewire.auth.logout');
    }
}
