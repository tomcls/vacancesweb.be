<?php

namespace App\Http\Livewire\Me\House;

use App\Data\Enums\AmenityCategoryEnum;
use Illuminate\Support\Facades\App;
use App\Models\AmenityTranslation;
use App\Models\HouseAmenity;
use Livewire\Component;

class HouseAmenities extends Component
{
    public $houseAmenities = [];
    public $houseClassifications = [];
    public $comforts = [];
    public $classifications = [];
    public $options = [];
    public $arounds = [];
    public $services = [];
    public $houseId = null;
    public $lang = null;
    
    protected $rules = [
        'classifications.*.id' => 'required'
    ];

    public function mount($houseId) {
        $this->lang = App::currentLocale();
        $this->houseId = $houseId;
        $this->comforts = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Confort))->orderBy('name', 'asc')->get();
        $this->options = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Options))->orderBy('name', 'asc')->get();
        $this->arounds = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Around))->orderBy('name', 'asc')->get();
        $this->services = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Services))->orderBy('name', 'asc')->get();
        $this->classifications = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Classification))->orderBy('name', 'asc')->get();
        $this->houseAmenities = HouseAmenity::whereHouseId($houseId)->get()->pluck('amenity_id');
        $this->houseClassifications = HouseAmenity::whereHouseId($houseId)->whereNotNull('value')->get()->pluck( 'value','amenity_id');
    }

    public function save() {
        HouseAmenity::whereHouseId($this->houseId)->delete();
        $houseAmenities = [];
        foreach ($this->houseAmenities as $value) {
            array_push($houseAmenities,['house_id'=>$this->houseId, 'amenity_id'=>$value, 'value'=>$this->houseClassifications[$value]??null]);
        }
        if(count($houseAmenities)) {
            HouseAmenity::insert($houseAmenities);
        }
        $this->notify(['message'=>'Amenities well saved','type'=>'success']);
    }
    
    public function render()
    {
        return view('livewire.me.house.house-amenities');
    }
}
