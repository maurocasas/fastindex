<?php

namespace App\Livewire\Sites;

use App\Jobs\ServiceAccounts\ListSites;
use App\Models\Site;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Index extends Component
{

    public function syncSites()
    {
        Toaster::success('Syncing sites');

        dispatch(new ListSites());

        $this->dispatch('close-modal');
    }

    public function visit($url)
    {
        $this->redirect($url, navigate: true);
    }

    #[Title('Sites')]
    public function render()
    {
        $sites = Site::orderBy('hostname', 'asc')->get();

        return view('livewire.sites.index')
            ->with(compact('sites'));
    }
}
