<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class invoiceBtn extends Component
{
    /**
     * Create a new component instance.
     */


    public $btn_invoice;
    public $href;

    public function __construct($btn_invoice, $href)
    {
        $this->btn_invoice = $btn_invoice;
        $this->href = $href;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.button.invoice-btn');
    }
}
