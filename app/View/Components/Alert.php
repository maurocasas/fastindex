<?php

namespace App\View\Components;

use App\AlertType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string|AlertType $type = 'info')
    {
        if(!$this->type instanceof AlertType) {
            $this->type = AlertType::tryFrom($this->type)->value;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
