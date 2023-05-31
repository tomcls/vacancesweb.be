<?php

namespace App\Http\Livewire;

use App\Models\House;
use App\Models\Region;
use Livewire\Component;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Laravel\Octane\Facades\Octane;
use Illuminate\Support\Facades\App;
use App\Models\HouseTypeTranslation;

class Search extends Component
{
    public $lang;
    public $locationSearch;
    public $locations = [];
    public $types = [];
    protected $listeners = ['selectAutoCompleteItem' => 'setAutoCompleteItem'];
    public function mount() {
        $this->lang = App::currentLocale();
        $this->types = HouseTypeTranslation::whereLang($this->lang)->orderBy('name', 'asc')->get();
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
        //$this->locationId = $id;
        $this->emit('setLocationId',$id);
        logger("type=".$type." text=".$text." id=".$id." typ=".$typ);
    }

    public function render()
    {
        return view('livewire.search');
    }
}
