<?php

namespace App\Http\Livewire\Admin\Geo;

use App\Models\RegionTranslation as ModelsRegionTranslation;
use Livewire\Component;
use Illuminate\Support\Str;

class RegionTranslation extends Component
{
    public ModelsRegionTranslation $regionName;
    public $lang;

    protected $listeners = [
        'refreshContent' => 'refreshName',
        'saveContent' => 'save',
        'setName' => 'setName',
        'isNameValid' => 'isNameValid'
    ];
    protected $rules = [
        'regionName.name' => 'required',
        'regionName.lang' => 'required',
        'regionName.slug' => 'required',
        'regionName.path' => 'required',
    ];
    public function isNameValid()
    {
        $this->validate();
    }
    public function mount(ModelsRegionTranslation $regionName, $lang)
    {
        $this->lang = $lang;
        $this->regionName = $regionName;
    }
    public function refreshName($lang)
    {
        $this->lang = $lang;
    }
    public function setName($lang, $title)
    {
        $this->regionName->slug = Str::slug($title);
    }
    public function save($regionId)
    {
        $this->regionName->region_id = $regionId;
        $this->validate();
        $this->regionName->save();
    }
    public function render()
    {
        return view('livewire.admin.geo.region-translation');
    }
}
