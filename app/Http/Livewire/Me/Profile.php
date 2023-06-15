<?php

namespace App\Http\Livewire\Me;

use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
        return view('livewire.me.profile')->layout('layouts.me');
    }
}
