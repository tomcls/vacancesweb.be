<?php

namespace App\Http\Livewire\Me\House;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\HouseSeason;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class HouseSeasons extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public HouseSeason $editing;
    public $houseId;

    protected $queryString = ['sorts'];

    public $filters = [
        'startdate-min' => null,
        'enddate-min' => null,
        'startdate-max' => null,
        'enddate-max' => null,
        'day-price-min' => null,
        'week-price-min' => null,
        'weekend-price-min' => null,
        'day-price-max' => null,
        'week-price-max' => null,
        'weekend-price-max' => null,
        'min-nights-min' => null,
        'min-nights-max' => null,
    ];

    public $rules = [
        "editing.startdate_for_editing" => 'required',
        "editing.enddate_for_editing" => 'required',
        "editing.day_price" => 'sometimes',
        "editing.week_price" => 'sometimes',
        "editing.weekend_price" => 'sometimes',
        "editing.min_nights" => 'sometimes',
    ];

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public function mount ($houseId) {
        $this->houseId = $houseId;
        $this->editing = $this->makeBlankSeason();
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' seasons','type'=>'success']);
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankSeason();

        $this->showEditModal = true;
    }

    public function edit(HouseSeason $houseSeason)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($houseSeason)) $this->editing = $houseSeason;

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->editing->house_id = $this->houseId;
        $this->validate();
        $this->editing->save();

        $this->showEditModal = false;

        $this->notify(['message'=>'Season price well saved','type'=>'success']);
    }

    public function makeBlankSeason()
    {
        return HouseSeason::make([]);
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count()) {
            return response()->streamDownload(function () {
                echo $this->selectedRowsQuery->toCsv();
            }, 'seasons.csv');
        }
    }

    public function getRowsQueryProperty()
    {
        $query = HouseSeason::query()
            ->when($this->filters['startdate-min'], fn ($query, $date) => $query->where('startdate', '>=', Carbon::parse($date)))
            ->when($this->filters['startdate-max'], fn ($query, $date) => $query->where('startdate', '<=', Carbon::parse($date)))
            ->when($this->filters['enddate-min'], fn ($query, $date) => $query->where('enddate', '>=', Carbon::parse($date)))
            ->when($this->filters['enddate-max'], fn ($query, $date) => $query->where('enddate', '<=', Carbon::parse($date)))
            ->when($this->filters['day-price-min'], fn ($query, $price) => $query->where('day_price', '>=', $price))
            ->when($this->filters['day-price-max'], fn ($query, $price) => $query->where('day_price', '<=', $price))
            ->when($this->filters['week-price-min'], fn ($query, $price) => $query->where('week_price', '>=', $price))
            ->when($this->filters['week-price-max'], fn ($query, $price) => $query->where('week_price', '<=', $price))
            ->when($this->filters['weekend-price-min'], fn ($query, $price) => $query->where('weekend_price', '>=', $price))
            ->when($this->filters['weekend-price-max'], fn ($query, $price) => $query->where('weekend_price', '<=', $price))
            ->when($this->filters['min-nights-min'], fn ($query, $price) => $query->where('min_nights', '>=', $price))
            ->when($this->filters['min-nights-max'], fn ($query, $price) => $query->where('min_nights', '<=', $price))
            ->whereHouseId($this->houseId);
        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        // return $this->cache(function () {
        //     return $this->applyPagination($this->rowsQuery);
        // });
    }

    public function render()
    {
        return view('livewire.me.house.house-seasons', [
            'seasons' => $this->rows,
        ]);
    }
}
