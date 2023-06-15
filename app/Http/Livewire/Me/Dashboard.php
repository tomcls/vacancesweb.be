<?php

namespace App\Http\Livewire\Me;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.me.dashboard')->layout('layouts.me');
    }
}
