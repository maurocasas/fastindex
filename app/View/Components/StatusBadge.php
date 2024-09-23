<?php

namespace App\View\Components;

use App\AlertType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public AlertType|string|null $status = 'pending')
    {
        if (! $this->status instanceof AlertType) {
            $this->status = AlertType::tryFrom($status);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.status-badge');
    }
}
