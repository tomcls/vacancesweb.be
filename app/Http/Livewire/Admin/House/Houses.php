<?php

namespace App\Http\Livewire\Admin\House;

use App\Models\House;
use App\Models\User;
use Livewire\Component;
use App\Models\HouseType;
use App\Models\HouseTitle;
use Illuminate\Support\Facades\DB;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;
use Illuminate\Support\Facades\App;

class Houses extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $filters = [
        'id' => null,
        'user-id' => null,
        'search' => null,
        'name' => null,
        'has-position' => null,
        'no-position' => null,
        'type-id' => null,
        'lang' => null,
        'is-active' => null,
        'not-active' => null,
        'date-created-min' => null,
        'date-created-max' => null,
    ];

    protected $queryString = ['sorts'];

    protected $listeners = ['selectAutoCompleteItem' => 'setAutoCompleteItem'];

    public $houseTypes = null;

    public $users = null;
    public $userSearch = null;

    public function mount()
    {
        $this->houseTypes = HouseType::all();
        $this->filters['lang'] = App::currentLocale();
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
            }, 'houses.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message' => 'You\'ve deleted ' . $deleteCount . ' houses', 'type' => 'success']);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function new()
    {
        return redirect()->to('/admin/house/house');
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getRowsQueryProperty()
    {
        $query = House::query()
        ->select(DB::raw('
        houses.id, 
        houses.user_id,
        houses.active,
        houses.house_type_id, 
        houses.longitude, 
        houses.latitude, 
        houses.created_at,
        houses.updated_at,
        house_titles.lang,
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
        house_publications.startdate,
        house_publications.enddate '))
        ->leftJoin('house_titles', 'house_titles.house_id', '=', DB::raw('houses.id and house_titles.lang = \'' . $this->filters['lang'] . '\''))
        ->leftJoin('house_types', 'house_types.id', '=', 'houses.house_type_id')
        ->leftJoin('house_type_translations', 'house_type_translations.house_type_id', '=', DB::raw('house_types.id and house_type_translations.lang = \'' . $this->filters['lang'] . '\''))
        ->leftJoin('house_images', 'house_images.house_id', '=', DB::raw('houses.id and house_images.sort = 0 '))
        ->leftJoin('house_publications', 'house_publications.house_id', '=', DB::raw('houses.id and now() between house_publications.startdate and house_publications.enddate and house_publications.startdate is not null and house_publications.enddate is not null '))
        ->leftJoin('house_seasons', 'house_seasons.house_id', '=', DB::raw('houses.id and now() between house_seasons.startdate and house_seasons.enddate'))
        ->when($this->filters['name'], fn ($query, $name) => $query->where('house_titles.name', 'like', '%' . $name . '%'))
        ->when($this->filters['lang'], fn ($query, $lang) => $query->where('house_titles.lang', '=', $lang))
        ->when($this->filters['search'], fn ($query, $search) =>
        $query->where('name', 'like', '%' . $search . '%'))
        ->when($this->filters['type-id'], fn ($query, $id) =>
        $query->where('houses.house_type_id', $id))
        ->when($this->filters['user-id'], fn ($query, $id) =>
        $query->where('user_id', $id))
        ->when($this->filters['has-position'], fn ($query) =>
        $query->whereNotNull('longitude'))
        ->when($this->filters['no-position'], fn ($query) =>
        $query->whereNull('longitude'))
        ->when($this->filters['is-active'], fn ($query) =>
        $query->where('active', '=', 1))
        ->when($this->filters['not-active'], fn ($query) =>
        $query->where('active', '=', 0))
        ->when($this->filters['id'], fn ($query, $id) =>
        $query->where('houses.id', $id));

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        /* return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });*/
    }

    // Fetch records
    public function usersResult()
    {
        if (!empty($this->userSearch)) {
            $query = User::query()->select(DB::raw("id, CONCAT(firstname,' ', lastname) AS title, email as subtitle"))
                ->when($this->userSearch, fn ($query, $name) => $query
                    ->where('firstname', 'like', '%' . $name . '%')
                    ->orWhere('lastname', 'like', '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%'))->limit(5);
            $this->users = $query->get();
        }
    }
    public function setAutoCompleteItem($type, $text, $id)
    {
        switch ($type) {
            case 'filters.user-id':
                $this->userSearch = $text;
                $this->filters['user-id'] = $id;
                $this->users = null;
                break;

            default:
                # code...
                break;
        }
    }
    public function render()
    {
        return view('livewire.admin.house.houses', [
            'houses' => $this->rows,
        ])->layout('layouts.admin');
    }
}
