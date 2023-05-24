<?php

namespace App\Http\Livewire;

use App\Data\Enums\AmenityCategoryEnum;
use App\Models\AmenityTranslation;
use App\Models\Country;
use App\Models\House;
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

    protected $listeners = ['selectAutoCompleteItem' => 'setAutoCompleteItem'];

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

    public function locationsResult()
    {
        if (!empty($this->locationSearch)) {

            [$regionsResult, $countriesResult, $housesResult] = Octane::concurrently(
                [
                    fn () => Region::query()
                        ->select(DB::raw("regions.id as id, region_translations.name as title, country_translations.name as subtitle, 'region'  as type "))
                        ->leftJoin('region_translations', 'region_translations.region_id', '=', DB::raw('regions.id and region_translations.lang = \'' . App::currentLocale() . '\''))
                        ->leftJoin('countries', 'countries.id', '=', 'regions.country_id')
                        ->leftJoin('country_translations', 'country_translations.country_id', '=', DB::raw('countries.id and country_translations.lang = \'' . App::currentLocale() . '\''))
                        ->when($this->locationSearch, fn ($query, $name) =>
                        $query->where('region_translations.name', 'like', '%' . $name . '%'))
                        ->where('region_translations.lang', '=', App::currentLocale())
                        ->limit(5)->get()->toArray(),
                    fn () => Country::query()
                        ->select(DB::raw("countries.id as id, country_translations.name as title, iso_code as subtitle, 'country'  as type"))
                        ->leftJoin('country_translations', 'country_translations.country_id', '=', DB::raw('countries.id and country_translations.lang = \'' . App::currentLocale() . '\''))
                        ->when($this->locationSearch, fn ($query, $name) =>
                        $query->where('country_translations.name', 'like', '%' . $name . '%'))
                        ->where('country_translations.lang', '=', App::currentLocale())
                        ->limit(5)->get()->toArray(),
                    fn () => House::query()
                        ->select(DB::raw("houses.id as id, house_titles.name as title, house_type_translations.name as subtitle, house_images.name as image, 'house'  as type"))
                        ->leftJoin('house_titles', 'house_titles.house_id', '=', DB::raw('houses.id and house_titles.lang = \'' . App::currentLocale() . '\''))
                        ->leftJoin('house_types', 'house_types.id', '=', 'houses.house_type_id')
                        ->leftJoin('house_type_translations', 'house_type_translations.house_type_id', '=', DB::raw('house_types.id and house_type_translations.lang = \'' . App::currentLocale() . '\''))
                        ->leftJoin('house_images', 'house_images.house_id', '=', DB::raw('houses.id and house_images.sort = 0 '))
                        ->when($this->locationSearch, fn ($query, $name) =>
                        $query->where('house_titles.name', 'like', '%' . $name . '%'))
                        ->where('house_titles.lang', '=', App::currentLocale())
                        ->limit(5)->get()->toArray(),
                ]
            );
            $this->locations = array_merge($regionsResult, $countriesResult, $housesResult);
        }
    }
    public function setAutoCompleteItem($type, $text, $id, $typ)
    {
        $this->locationSearch = $text;
        $this->locationId = $id;
        logger("type=".$type." text=".$text." id=".$id." typ=".$typ);
    }
    public function getRowsQueryProperty()
    {
        $query = House::query()
            ->select(DB::raw('
            houses.id, 
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
            house_seasons.weekend_price '))
            ->leftJoin('house_titles', 'house_titles.house_id', '=', DB::raw('houses.id and house_titles.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('house_types', 'house_types.id', '=', 'houses.house_type_id')
            ->leftJoin('house_type_translations', 'house_type_translations.house_type_id', '=', DB::raw('house_types.id and house_type_translations.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('house_images', 'house_images.house_id', '=', DB::raw('houses.id and house_images.sort = 0 '))
            ->leftJoin('house_publications', 'house_publications.house_id', '=', DB::raw('houses.id and now() between house_publications.startdate and house_publications.enddate and house_publications.startdate is not null and house_publications.enddate is not null '))
            ->leftJoin('house_seasons', 'house_seasons.house_id', '=', DB::raw('houses.id and now() between house_seasons.startdate and house_seasons.enddate'))
            ->when($this->locationId, function ($query, $id)  {
                return $query->whereExists(function ($query) use ($id) {
                    $query->select("house_regions.region_id")
                          ->from('house_regions')
                          ->whereRaw('house_regions.region_id ='.$id);
                });
            })
            ->where('house_titles.lang', '=', App::currentLocale())->whereActive(true)
            ->whereNotNull('house_publications.startdate')
            ->whereNotNull('house_publications.enddate');
        logger($query->toSql());
        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        /* return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });*/
    }
    public function render()
    {
        return view('livewire.houses', [
            'rows' => $this->rows,
        ])->layout('layouts.base');
    }
}
