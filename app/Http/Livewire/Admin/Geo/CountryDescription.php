<?php

namespace App\Http\Livewire\Admin\Geo;

use App\Models\CountryDescription as ModelsCountryDescription;
use Livewire\Component;

class CountryDescription extends Component
{
    public ModelsCountryDescription $countryDescription;
    public $lang;
    public $keyLang;
    public $keyType;
    public $type;

    protected $listeners = ['refreshContent' => 'refreshDescription','saveContent' => 'save'];

    protected $rules = [
        'countryDescription.country_id' => 'required',
        'countryDescription.description' => 'required',
        'countryDescription.lang' => 'required',
        'countryDescription.type' => 'required',
    ];

    public function mount(ModelsCountryDescription $countryDescription, $lang, $type)
    {
        $this->lang = $lang;
        $this->type = $type;
        $this->countryDescription = $countryDescription;
    }
    
    public function save($countryId)
    {
        $this->countryDescription->country_id = $countryId;
        $this->validate();
        $this->countryDescription->save();
    }

    public function refreshDescription($lang, $type)
    {
        $this->lang = $lang;
        $this->type= $type;
    }

    public function render()
    {
        return view('livewire.admin.geo.country-description');
    }
}
