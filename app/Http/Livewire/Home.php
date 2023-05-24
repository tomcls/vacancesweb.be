<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class Home extends Component
{
    public $homepage = [];
    public $lang = null;
    public $locations = [];
    public function mount()
    {
        $json = json_decode(Storage::get("homepage.json"), true);
        $this->homepage = $json;
        $this->lang = App::currentLocale();

    }
    public function render()
    {
        return view('livewire.home')->layout('layouts.home');
    }
}
