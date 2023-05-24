<?php

namespace App\Http\Livewire\Admin\Geo;

use App\Models\RegionDescription as ModelsRegionDescription;
use Livewire\Component;

class RegionDescription extends Component
{
    public ModelsRegionDescription $regionDescription;
    public $lang;
    public $keyLang;
    public $keyType;
    public $type;

    protected $listeners = ['refreshContent' => 'refreshDescription','saveContent' => 'save'];

    protected $rules = [
        'regionDescription.region_id' => 'required',
        'regionDescription.description' => 'required',
        'regionDescription.lang' => 'required',
        'regionDescription.type' => 'required',
    ];

    public function mount(ModelsRegionDescription $regionDescription, $lang, $type)
    {
        $this->lang = $lang;
        $this->type = $type;
        $this->regionDescription = $regionDescription;
    }
    
    public function save($regionId)
    {
        $this->regionDescription->region_id = $regionId;
        $this->validate();
        $this->regionDescription->save();
    }

    public function refreshDescription($lang, $type)
    {
        $this->lang = $lang;
        $this->type= $type;
    }
    public function render()
    {
        return view('livewire.admin.geo.region-description');
    }
}
