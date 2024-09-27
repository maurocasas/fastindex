<?php

namespace App\Livewire\Sites;

use App\Jobs\Sites\ListSitemapsBySite;
use App\Jobs\Sites\RegisterSitemapIntoGsc;
use App\Jobs\Sites\RemoveSitemapFromSite;
use App\Models\Site;
use App\Models\Sitemap;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Show extends Component
{
    use WithPagination;

    public Site $site;

    public string $tab = 'pages';

    public string $sitemap_url = '';

    protected $listeners = [
        'sitemaps-updated' => '$refresh',
    ];

    public function getSitemaps()
    {
        if (! $this->site->refreshing_sitemaps) {
            $this->site->refreshing_sitemaps = true;
            $this->site->save();

            dispatch(new ListSitemapsBySite($this->site));
        }

        Toaster::success('Your sitemaps are being refreshed.');
    }

    public function createSitemap()
    {
        $this->validate([
            'sitemap_url' => ['required', 'url'],
        ]);

        if ($this->site->sitemaps()->where('path', $this->sitemap_url)->exists()) {
            $this->addError('sitemap_url', 'Sitemap already registered.');

            return;
        }

        $xmlData = Http::get($this->sitemap_url);

        if (blank($xmlData)) {
            $this->addError('sitemap_url', 'Make sure the URL is public and reachable.');

            return;
        }

        dispatch(new RegisterSitemapIntoGsc($this->site->sitemaps()->create([
            'url' => $this->sitemap_url,
            'content' => [],
        ])));

        $this->reset('sitemap_url');

        Toaster::success('Sitemap pushed onto submission queue.');

        $this->dispatch('close-modal');
    }

    #[Computed]
    public function sitemaps()
    {
        return $this->site->sitemaps;
    }

    public function deleteSitemap(Sitemap $sitemap)
    {
        $url = $sitemap->url;
        $site = $sitemap->site;

        $sitemap->delete();

        dispatch(new RemoveSitemapFromSite($site, $url));

        Toaster::success('Sitemap deleted.');
    }

    public function toggleAutoindex()
    {
        $this->site->update(['auto_index' => ! $this->site->auto_index]);

        Toaster::success('Auto-indexing setting updated.');
    }

    #[Computed]
    public function service_accounts()
    {
        return $this->site->service_accounts->count();
    }

    #[Computed]
    public function totalPages(): int
    {
        return $this->site->pages()->count();
    }

    #[Computed]
    public function pages()
    {
        return $this->site->pages()->latest('crawled_at')->limit(10)->get();
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

    public function render()
    {
        return view('livewire.sites.show')
            ->title($this->site->hostname);
    }
}
