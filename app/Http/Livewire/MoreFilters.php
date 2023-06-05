<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\AmenityTranslation;
use Illuminate\Support\Facades\App;
use App\Data\Enums\AmenityCategoryEnum;
use App\Models\HouseTypeTranslation;
use Illuminate\Support\Facades\Cache;

class MoreFilters extends Component
{
    public $tab = 'types';
    public $comforts = [];
    public $classifications = [];
    public $options = [];
    public $arounds = [];
    public $services = [];
    public $lang;
    
    public $types = [];
    public $houseTypes = [];
    public $houseAmenities = [];
    public $houseClassifications = [];

    public function mount(Request $request)
    {
        $this->lang = App::currentLocale();
        $this->comforts = Cache::remember('comforts', 10000, function () {
            return AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Confort))->orderBy('name', 'asc')->get();
        });
        $this->options = Cache::remember('options', 10000, function () {
            return AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Options))->orderBy('name', 'asc')->get();
        });
        $this->arounds = Cache::remember('arounds', 10000, function () {
            return AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Around))->orderBy('name', 'asc')->get();
        });
        $this->services = Cache::remember('services', 10000, function () {
            return AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Services))->orderBy('name', 'asc')->get();
        });
        $this->classifications = Cache::remember('services', 10000, function () {
            return AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Classification))->orderBy('name', 'asc')->get();
        });
        $this->types = Cache::remember('house_types', 10000, function () {
            return HouseTypeTranslation::whereLang($this->lang)->orderBy('name', 'asc')->get();
        });
    }
    public function search() {
        $this->emitUp('moreFilters',$this->houseAmenities,$this->houseTypes );
    }
    public function render()
    {
        return view('livewire.more-filters');
    }
}
