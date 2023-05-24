<?php

namespace App\Http\Livewire\Admin\House;

use Livewire\Component;
use App\Models\HouseDescription as HouseDescriptionModel;

class HouseDescription extends Component
{
    public HouseDescriptionModel $houseDescription;
    public $lang;

    protected $listeners = ['setDescription' => 'setDescription','refreshContent' => 'refreshDescription','saveContent' => 'save'];

    protected $rules = [
        'houseDescription.house_id' => 'required',
        'houseDescription.description' => 'required',
        'houseDescription.lang' => 'required',
    ];

    public function mount(HouseDescriptionModel $houseDescription, $lang)
    {
        $this->lang = $lang;
        $this->houseDescription = $houseDescription;
    }
    
    public function save($houseId)
    {
        $this->houseDescription->house_id = $houseId;
        $this->validate();
        $this->houseDescription->save();
    }
    public function refreshDescription($lang)
    {
        $this->lang = $lang;
    }
    public function setDescription($description)
    {
        $this->houseDescription->description = $description;
    }
    public function render()
    {
        return view('livewire.admin.house.house-description');
    }
}
