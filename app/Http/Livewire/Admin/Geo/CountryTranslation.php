<?php

namespace App\Http\Livewire\Admin\Geo;

use App\Models\CountryTranslation as ModelsCountryTranslation;
use Livewire\Component;
use Illuminate\Support\Str;

class CountryTranslation extends Component
{
    public ModelsCountryTranslation $countryName;
    public $lang;

    protected $listeners = [
        'refreshContent' => 'refreshName',
        'saveContent' => 'save',
        'setName' => 'setName',
        'isNameValid' => 'isNameValid'
    ];
    protected $rules = [
        'countryName.name' => 'required',
        'countryName.lang' => 'required',
        'countryName.slug' => 'required',
    ];
    public function isNameValid()
    {
        $this->validate();
    }
    public function mount(ModelsCountryTranslation $countryName, $lang)
    {
        $this->lang = $lang;
        $this->countryName = $countryName;
    }
    public function refreshName($lang)
    {
        $this->lang = $lang;
    }
    public function setName($lang, $title)
    {
        $this->countryName->slug = Str::slug($title);
    }
    public function save($countryId)
    {
        $this->countryName->country_id = $countryId;
        $this->validate();
        $this->countryName->save();
    }
    public function render()
    {
        return view('livewire.admin.geo.country-translation');
    }
}
