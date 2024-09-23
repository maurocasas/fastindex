<?php

namespace App\Livewire;

use Illuminate\Support\Facades\File;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Settings extends Component
{
    public int $daily_quota = 0;

    public function __construct()
    {
        $this->daily_quota = File::get(config_path('daily_quota'));
    }

    public function store()
    {
        $this->validate([
            'daily_quota' => ['required', 'numeric', 'min:0', 'max:2000'],
        ]);

        File::put(config_path('daily_quota'), $this->daily_quota);

        Toaster::success('Saved.');
    }

    #[Title('Settings')]
    public function render()
    {
        return view('livewire.settings');
    }
}
