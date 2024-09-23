<?php

namespace App\Livewire;

use App\Models\User;
use App\UserRole;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Team extends Component
{
    public string $email = '';

    public string $name = '';

    public string $password = '';

    public function toggleRole(User $user)
    {
        $user->update([
            'role' => $user->role->value === UserRole::ADMIN->value ? UserRole::MEMBER : UserRole::ADMIN,
        ]);

        Toaster::success('User updated.');
    }

    public function create()
    {
        $this->validate([
            'email' => ['required', 'email', Rule::unique('users')],
            'name' => ['required', 'max:255'],
            'password' => ['required', 'min:5'],
        ]);

        User::create($this->only('name', 'email', 'password'));

        $this->reset();

        Toaster::success('User created.');

        $this->dispatch('close-modal');
    }

    #[Title('Team')]
    public function render()
    {
        $users = User::all();

        return view('livewire.team')
            ->with(compact('users'));
    }
}
