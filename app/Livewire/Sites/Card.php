<?php

namespace App\Livewire\Sites;

use App\Models\Site;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Card extends Component
{
    public Site $site;

    #[Computed]
    public function pages(): int
    {
        return $this->site->pages()->count();
    }

    #[Computed]
    public function indexed(): int
    {
        return $this->site->pages()->where('coverage_state', 'Submitted and indexed')->count();
    }

    #[Computed]
    public function errors(): int
    {
        return $this->site->pages()->whereIn('coverage_state', [
            'URL is unknown to Google',
            'Page with redirect',
            'Server error (5xx)',
        ])->count();
    }

    #[Computed]
    public function pending(): int
    {
        return $this->site->pages()->whereNull('coverage_state')->count();
    }

    #[Computed]
    public function service_accounts(): int
    {
        return $this->site->service_accounts()->count();
    }

    #[Computed]
    public function sitemaps(): int
    {
        return $this->site->sitemaps->count();
    }

    public function render()
    {
        return view('livewire.sites.card');
    }
}
