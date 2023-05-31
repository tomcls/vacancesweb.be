<?php

namespace App\Http\Livewire;

use App\Data\Enums\AmenityCategoryEnum;
use App\Models\AmenityTranslation;
use App\Models\Country;
use App\Models\House;
use App\Models\HouseRegion;
use App\Models\Region;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Octane\Facades\Octane;
use Illuminate\Support\Facades\App;
use App\Models\HouseTypeTranslation;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Houses extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;
    public $types = [];
    public $amenities = [];
    public $lang;
    public $locationSearch;
    public $locations = [];
    public $locationId = null;


    public $houseAmenities = [];
    public $houseClassifications = [];

    public $comforts = [];
    public $classifications = [];
    public $options = [];
    public $arounds = [];
    public $services = [];

    public $tab = 'amenities';

    protected $listeners = ['setLocationId' => 'setLocationId'];

    public function mount(Request $request)
    {
        $this->lang = App::currentLocale();
        $this->types = HouseTypeTranslation::whereLang($this->lang)->orderBy('name', 'asc')->get();
        
        $this->comforts = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Confort))->orderBy('name', 'asc')->get();
        $this->options = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Options))->orderBy('name', 'asc')->get();
        $this->arounds = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Around))->orderBy('name', 'asc')->get();
        $this->services = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Services))->orderBy('name', 'asc')->get();
        $this->classifications = AmenityTranslation::with('amenity')->whereLang($this->lang)->whereHas('amenity', fn ($query) => $query->where('category', AmenityCategoryEnum::Classification))->orderBy('name', 'asc')->get();
    }

    
    public function getRowsQueryProperty()
    {
        logger('getRowsQueryProperty');
        $query = House::query()
            ->select(DB::raw('
            houses.id, 
            houses.active, 
            houses.house_type_id, 
            houses.longitude, 
            houses.latitude, 
            house_titles.name as title, 
            house_titles.slug, 
            house_type_translations.name as type_name, 
            houses.house_type_id, 
            houses.acreage, 
            houses.number_people, 
            house_seasons.min_nights, 
            house_seasons.week_price, 
            house_seasons.day_price, 
            house_seasons.weekend_price,
            region_translations.name region_name'))
            ->leftJoin('house_regions', 'house_regions.house_id', '=',DB::raw( 'houses.id '))
            ->leftJoin('regions', 'house_regions.region_id', '=', DB::raw('regions.id '))
            ->leftJoin('region_translations', 'region_translations.region_id', '=', DB::raw('regions.id and region_translations.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('house_titles', 'house_titles.house_id', '=', DB::raw('houses.id and house_titles.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('house_types', 'house_types.id', '=', 'houses.house_type_id')
            ->leftJoin('house_type_translations', 'house_type_translations.house_type_id', '=', DB::raw('house_types.id and house_type_translations.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('house_publications', 'house_publications.house_id', '=', DB::raw('houses.id and now() between house_publications.startdate and house_publications.enddate and house_publications.startdate is not null and house_publications.enddate is not null '))
            ->leftJoin('house_seasons', 'house_seasons.house_id', '=', DB::raw('houses.id and now() between house_seasons.startdate and house_seasons.enddate'))
            ->when($this->locationId, function ($query, $id)  {
                return $query->whereIn(db::raw('houses.id'), HouseRegion::whereRegionId($id)->get()->pluck('house_id') );
            })
            ->where('regions.level', '=', 'city')
            ->where('house_titles.lang', '=', App::currentLocale())
            ->where('houses.active',true)
            ->whereNotNull('house_publications.startdate')
            ->whereNotNull('house_publications.enddate');
        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        /* return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });*/
    }
    public function setLocationId($id) {
        logger('locationId='.$id);
        $this->locationId = $id;
    }
    public function hydrate() {
        logger('hydrate');
    }
    public function dehydrate() {
        $this->emit('loadImages');
    }
    public function updated() {
        logger('updated');
    }
    public function render()
    {
        logger('render');
        return view('livewire.houses', [
            'rows' => $this->rows,
        ])->layout('layouts.base');
    }
}
