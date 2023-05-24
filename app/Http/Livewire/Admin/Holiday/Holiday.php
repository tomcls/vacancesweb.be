<?php

namespace App\Http\Livewire\Admin\Holiday;

use Illuminate\Http\Request;
use Livewire\Component;

class Holiday extends Component
{
    public $tabs = [
        "detail" => true,
        "images" => false,
        "prices" => false,
        "transactions" => false,
        "documents" => false,
    ];
    public  $holidayId = null;
    protected $queryString = ['tabs'];

    public function mount(Request $request) {
        $this->holidayId = $request['id'];
    }

    public function openTab($tabname) {
        foreach ($this->tabs as $key => $tab) {
            $this->tabs[$key]=false;
        }
        $this->tabs[$tabname] = true;
    }

    public function render()
    {
        return view('livewire.admin.holiday.holiday')->layout('layouts.admin');
    }
}
