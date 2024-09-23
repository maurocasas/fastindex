<?php

namespace App\Livewire;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Account extends Component
{

    public string $name = '';
    public string $email = '';
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount()
    {
        $this->fill(auth()->user()->only('email', 'name'));
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())]
        ]);

        auth()->user()->update($this->only('name', 'email'));

        Toaster::success('Saved.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'min:5', 'max:255'],
            'new_password' => ['required', 'min:5', 'max:255', 'confirmed'],
            'new_password_confirmation' => ['required'],
        ]);

        if (!auth()->user()->checkPassword($this->current_password)) {
            $this->addError('current_password', 'Invalid current password.');
            return;
        }

        if (auth()->user()->checkPassword($this->new_password)) {
            $this->addError('new_password', 'Password should be different to current one.');
            return;
        }

        auth()->user()->update(['password' => $this->new_password]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        Toaster::success('Done.');
    }

    #[Title('Account')]
    public function render()
    {
        return view('livewire.account');
    }
}
