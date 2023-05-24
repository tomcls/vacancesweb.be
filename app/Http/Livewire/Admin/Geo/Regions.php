<?php

namespace App\Http\Livewire\Admin\Geo;

use App\Models\Region;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Regions extends Component
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
            }, 'regions.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' regions','type'=>'success']);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }
    public function new()
    {
        return redirect()->to('/admin/geo/region');
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getRowsQueryProperty()
    {
        $query = Region::query()
            ->select('regions.id', 'name', 'slug', 'lang', 'path', 'level', 'longitude', 'latitude', 'sw_lat', 'sw_lon', 'ne_lat', 'ne_lon', 'active','custom')
            ->selectSub(function ($q) {
                $q->from('holiday_regions')
                    ->select(DB::raw('count(*) as total'))
                    ->whereColumn('regions.id', 'holiday_regions.region_id')
                    ->groupBy('holiday_id')
                    ->limit(1);
            }, 'total_holidays')
            ->selectSub(function ($q) {
                $q->from('house_regions')
                    ->select(DB::raw('count(*) as total'))
                    ->whereColumn('regions.id', 'house_regions.region_id')
                    ->groupBy('house_id')
                    ->limit(1);
            }, 'total_houses')
            ->leftJoin('region_translations', 'region_translations.region_id', '=', 'regions.id', 'AND region_translations.lang = ',App::currentLocale())
            ->when($this->filters['name'], fn ($query, $name) =>
                $query->where('name', 'like', '%' . $name . '%'))
            ->when($this->filters['search'], fn ($query,$search) =>
                $query->where('name', 'like', '%' . $search . '%'))
            ->when($this->filters['is-active'], fn ($query) =>
                $query->where('active', '=', 1))
            ->when($this->filters['not-active'], fn ($query) =>
                $query->where('active', '=', 0))
            ->when($this->filters['id'], fn ($query, $id) =>
                $query->where('id', '=', $id))
            ->where('lang', '=', $this->filters['lang']);

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
        return view('livewire.admin.geo.regions', [
            'regions' => $this->rows,
        ])->layout('layouts.admin');
    }
}
