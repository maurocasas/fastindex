<?php

namespace App\Livewire;

use App\Models\Site;
use App\Models\Sitemap;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Sitemaps extends Component
{
    use WithPagination;

    public ?Site $site = null;

    #[Title('Sitemaps')]
    public function render()
    {
        $sitemaps = Sitemap::latest('updated_at');

        if (filled($this->site)) {
            $sitemaps->where('site_id', $this->site->id);
        }

        $sitemaps = $sitemaps->paginate(96);

        return view('livewire.sitemaps')
            ->with(compact('sitemaps'));
    }
}
