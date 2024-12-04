<?php

namespace App\View\Components\Button;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddBtn extends Component
{
    /**
     * Create a new component instance.
     */
    public $button;
    public $href;

    public function __construct($button, $href)
    {
        $this->button = $button;
        $this->href = $href;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.button.add-btn');
    }
}
