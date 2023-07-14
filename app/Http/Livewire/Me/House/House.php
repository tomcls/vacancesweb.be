<?php

namespace App\Http\Livewire\Me\House;

use Livewire\Component;
use Illuminate\Http\Request;

class House extends Component
{
    public $tab = "detail";
    public  $houseId = null;
    protected $queryString = ['tab'];

    protected $listeners = [
        'setHouseId' => 'setHouseId',
    ];
    public function mount(Request $request)
    {
        $this->houseId = $request['id'];
    }

    public function openTab($tabname)
    {
        $this->tab = $tabname;
    }
    public function setHouseId($houseId) {
        $this->houseId = $houseId;
    }

    public function render()
    {
        return view('livewire.me.house.house')->layout('layouts.me');
    }
}
