<?php

namespace App\Http\Livewire\Me;

use Livewire\Component;

class Invoices extends Component
{
    public function render()
    {
        return view('livewire.me.invoices')->layout('layouts.me');
    }
}
