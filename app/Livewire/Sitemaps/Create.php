<?php

namespace App\Livewire\Sitemaps;

use App\Jobs\Sitemaps\PushSitemap;
use App\Models\Site;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use XMLReader;

class Create extends Component
{
    public Site $site;
    public ?string $path = null;

    public function store()
    {
        $this->validate([
            'path' => ['required', 'url']
        ]);

        if ($this->site->sitemaps()->where('path', $this->path)->exists()) {
            throw ValidationException::withMessages([
                'path' => 'Sitemap already registered.'
            ]);
        }

        $xmlData = @ file_get_contents($this->path);

        if (blank($xmlData) || !$xmlData) {
            throw ValidationException::withMessages([
                'path' => 'Make sure the URL is public and reachable.'
            ]);
        }

        $xml = simplexml_load_string($xmlData);

        dispatch(new PushSitemap($this->site, $this->site->sitemaps()->create([
            'path' => $this->path,
            'content' => []
        ])));

        $this->reset();

        Toaster::success('Sitemap pushed onto submission queue.');
    }

    public function render()
    {
        return view('livewire.sitemaps.create');
    }
}
