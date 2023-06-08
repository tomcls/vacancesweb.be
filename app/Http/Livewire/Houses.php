<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\House;
use Livewire\Component;
use App\Models\HouseRegion;
use App\Models\HouseAmenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Houses extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;
    public $contact = [
        "date_from" => null,
    ];
    public $amenities = [];
    public $lang;
    public $locationSearch;
    public $locations = [];
    public $locationId = null;
    public $dateFrom = null;
    public $dateTo = null;
    public $numberPeople = null;
    public $countryId = null;

    public $houseTypes = [];
    public $houseAmenities = [];
    public $houseClassifications = [];

    public $showContactModal = false;

    public $tab = 'amenities';
    public $houseId = null;

    public $searchByUri = null;

    protected $listeners = [
        'numberPeople' => 'setNumberPeople',
        'dateTo' => 'setDateTo',
        'dateFrom' => 'setDateFrom',
        'setLocationId' => 'setLocationId',
        'moreFilters' => 'setMoreFilters',
        'setOrder' => 'setOrder'
    ];
    public function setOrder($item)
    {
        $this->sortBy($item['field'], $item['direction']);
    }
    public function mount(Request $request, $search = null)
    {
        $this->lang = App::currentLocale();
        if($search && $search['houseType']) {
            $this->houseTypes[] = $search['houseType']->house_type_id ?? null;
        }
        $this->locationId = $search['region']->region_id ?? null;
        $this->countryId = $search['country']->country_id ?? null;
        $this->searchByUri = $search ?? null;
    }

    public function getRowsQueryProperty()
    {
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
            houses.acreage, 
            houses.number_people, 
            house_seasons.min_nights, 
            house_seasons.week_price, 
            house_seasons.day_price, 
            house_seasons.weekend_price,
            region_translations.name region_name'))
            ->leftJoin('house_regions', 'house_regions.house_id', '=', DB::raw('houses.id '))
            ->leftJoin('regions', 'house_regions.region_id', '=', DB::raw('regions.id '))
            ->leftJoin('region_translations', 'region_translations.region_id', '=', DB::raw('regions.id and region_translations.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('house_titles', 'house_titles.house_id', '=', DB::raw('houses.id and house_titles.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('house_types', 'house_types.id', '=', 'houses.house_type_id')
            ->leftJoin('house_type_translations', 'house_type_translations.house_type_id', '=', DB::raw('house_types.id and house_type_translations.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('house_publications', 'house_publications.house_id', '=', DB::raw('houses.id and now() between house_publications.startdate and house_publications.enddate and house_publications.startdate is not null and house_publications.enddate is not null '))
            ->leftJoin('house_seasons', 'house_seasons.house_id', '=', DB::raw('houses.id and now() between house_seasons.startdate and house_seasons.enddate'))
            ->when($this->locationId, function ($query, $id) {
                return $query->whereIn(db::raw('houses.id'), HouseRegion::whereRegionId($id)->get()->pluck('house_id'));
            })
            ->when($this->countryId, function ($query, $id) {
                return $query->whereIn(db::raw('houses.id'), HouseRegion::leftJoin('regions', 'regions.id', '=', 'house_regions.region_id')->whereCountryId($id)->get()->pluck('house_id'));
            })
            ->when($this->dateFrom, function ($query, $dateFrom) {
                return $query->where(DB::raw('house_seasons.startdate'), '<=', Carbon::parse($dateFrom));
            })
            ->when($this->dateTo, function ($query, $dateTo) {
                return $query->where(DB::raw('house_seasons.enddate'), '>=', Carbon::parse($dateTo));
            })
            ->when($this->numberPeople, function ($query, $numberPeople) {
                return $query->where('number_people', '>=', $numberPeople);
            })
            ->when($this->amenities, function ($query, $id) {
                return $query->whereIn(
                    db::raw('houses.id'),
                    HouseAmenity::select('house_id')
                        ->whereIn('amenity_id', $this->amenities)
                        ->where(DB::raw('house_id'), '=', DB::raw('houses.id'))
                        ->groupBy('house_id')
                        ->having(DB::raw('count(*)'), '=', count($this->amenities))
                );
            })
            ->when($this->houseTypes, function ($query, $id) {
                return $query->whereIn(DB::raw('houses.house_type_id'), $this->houseTypes);
            })
            ->where('regions.level', '=', 'city')
            ->where('house_titles.lang', '=', App::currentLocale())
            ->where('houses.active', true)
            ->whereNotNull('house_publications.startdate')
            ->whereNotNull('house_publications.enddate')
            ->groupBy('houses.id');
            logger($query->toSql());
        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
    }

    public function setLocationId($id)
    {
        $this->locationId = $id;
    }
    public function setDateFrom($date)
    {
        $this->dateFrom = $date;
    }

    public function setDateTo($date)
    {
        $this->dateTo = $date;
    }
    public function setNumberPeople($p)
    {
        $this->numberPeople = $p;
    }
    public function setMoreFilters($amenities, $types)
    {
        $this->amenities = $amenities;
        $this->houseTypes = $types;
    }

    public function dehydrate()
    {
        $this->emit('loadImages');
    }

    public function render()
    {
        return view('livewire.houses', [
            'rows' => $this->rows,
        ])->layout('layouts.base');
    }
}
