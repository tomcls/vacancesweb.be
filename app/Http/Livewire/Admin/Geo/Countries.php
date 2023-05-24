<?php

namespace App\Http\Livewire\Admin\Geo;

use App\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithPerPagePagination;
use App\Traits\DataTable\WithSorting;
use Livewire\Component;

class Countries extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $filters = [
        'id' => null,
        'search' => null,
        'name' => null,
        'has-houses' => null,
        'has-holidays' => null,
        'lang' => 'fr',
        'is-active' => null,
        'not-active' => null,
    ];

    protected $queryString = ['sorts'];

    protected $listeners = ['selectAutoCompleteItem' => 'setAutoCompleteItem'];

    public $holidayTypes = null;

    public function mount()
    {

    }
    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count()) {
            return response()->streamDownload(function () {
                echo $this->selectedRowsQuery->toCsv();
            }, 'countries.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' countries','type'=>'success']);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }
    public function new()
    {
        return redirect()->to('/admin/geo/country');
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }
    public function getRowsQueryProperty()
    {
        $query = Country::query()
            ->select('*')
            ->selectSub(function ($q) {
                $q->from('holiday_regions')
                    ->select(DB::raw('count(*) as total'))
                    ->leftJoin('regions', 'regions.id', '=', 'holiday_regions.region_id')
                    ->whereColumn('regions.country_id', 'countries.id')
                    ->where('level','=','city')
                    ->groupBy('holiday_id')
                    ->limit(1);
            }, 'total_holidays')
            ->selectSub(function ($q) {
                $q->from('house_regions')
                    ->select(DB::raw('count(*) as total'))
                    ->leftJoin('regions', 'regions.id', '=', 'house_regions.region_id')
                    ->whereColumn('regions.country_id', 'countries.id')
                    ->where('level','=','city')
                    ->groupBy('house_id')
                    ->limit(1);
            }, 'total_houses')
            ->when($this->filters['name'], fn ($query, $name) =>
                $query->whereHas('translations', fn ($query) =>
                    $query->where('name', 'like', '%' . $name . '%')))
            ->when($this->filters['search'], fn ($query,$search) =>
                $query->whereHas('translations', fn ($query) =>
                    $query->where('name', 'like', '%' . $search . '%')))
            ->when($this->filters['is-active'], fn ($query) =>
                $query->where('active', '=', 1))
            ->when($this->filters['not-active'], fn ($query) =>
                $query->where('active', '=', 0))
            ->when($this->filters['id'], fn ($query, $id) =>
                $query->where('id', '=', $id));

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
        return view('livewire.admin.geo.countries', [
            'countries' => $this->rows,
        ])->layout('layouts.admin');
    }
}
