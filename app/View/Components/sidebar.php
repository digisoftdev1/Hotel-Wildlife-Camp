<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class sidebar extends Component
{
    public $menuPages;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->menuPages = \App\Models\Page::whereNull('parent_id')
            ->with(['children'])
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar');
    }
}
