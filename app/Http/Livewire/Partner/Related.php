<?php

namespace App\Http\Livewire\Partner;

use Livewire\Component;

class Related extends Component
{
    public $related = [];
    public function render()
    {
        return view('livewire.partner.related');
    }
}
