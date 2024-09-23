<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';

    public function attempt()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(!auth()->attempt($this->only('email', 'password'))) {
            $this->addError('email', 'Invalid credentials');
            return;
        }

        $this->redirectRoute('dashboard');
    }

    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
