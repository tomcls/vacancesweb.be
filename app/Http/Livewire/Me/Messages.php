<?php

namespace App\Http\Livewire\Me;

use Livewire\Component;

class Messages extends Component
{
    public function render()
    {
        return view('livewire.me.messages')->layout('layouts.me');
    }
}
