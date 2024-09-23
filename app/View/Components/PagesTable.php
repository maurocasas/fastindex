<?php

namespace App\View\Components;

use App\Models\Site;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class PagesTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public LengthAwarePaginator|Collection $pages, public ?Site $site = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pages-table');
    }
}
