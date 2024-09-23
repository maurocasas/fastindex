<?php

namespace App\Livewire;

use App\Models\Page;
use App\Models\Site;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Pages extends Component
{
    use WithPagination;

    public ?Site $site = null;

    #[Title('Pages')]
    public function render()
    {
        $pages = Page::latest('created_at');

        if (filled($this->site)) {
            $pages->where('site_id', $this->site->id);
        }

        $pages = $pages->paginate(100);

        return view('livewire.pages')
            ->with(compact('pages'));
    }
}
