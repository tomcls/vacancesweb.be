<?php

namespace App\Http\Livewire\Admin\House;

use Livewire\Component;
use Illuminate\Http\Request;

class House extends Component
{
    public $tabs = [
        "detail" => true,
        "images" => false,
        "amenities" => false,
        "seasons" => false,
        "costs" => false,
        "reservations" => false,
        "highlights" => false,
        "publications" => false,
        "transactions" => false,
        "documents" => false,
    ];
    public  $houseId = null;
    protected $queryString = ['tabs'];

    protected $listeners = [
        'setHouseId' => 'setHouseId',
    ];
    public function mount(Request $request)
    {
        $this->houseId = $request['id'];
    }

    public function openTab($tabname)
    {
        foreach ($this->tabs as $key => $tab) {
            $this->tabs[$key] = false;
        }
        $this->tabs[$tabname] = true;
    }
    public function setHouseId($houseId) {
        $this->houseId = $houseId;
        logger('houseId='.$houseId);
    }

    public function render()
    {
        return view('livewire.admin.house.house')->layout('layouts.admin');
    }
}
