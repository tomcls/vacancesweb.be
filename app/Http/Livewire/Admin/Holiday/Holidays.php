<?php

namespace App\Http\Livewire\Admin\Holiday;

use Livewire\Component;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;
use App\Models\HolidayTitle;
use App\Models\HolidayType;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Holidays extends Component
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

    public $holidayTypes = null;

    public $users = null;
    public $userSearch = null;

    public function mount()
    {
        $this->holidayTypes = HolidayType::all();
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
            }, 'holidays.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message' => 'You\'ve deleted ' . $deleteCount . ' holidays', 'type' => 'success']);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function new()
    {
        return redirect()->to('/admin/holiday');
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getRowsQueryProperty()
    {
        $query = HolidayTitle::query()
            ->when($this->filters['name'], fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'))
            ->when($this->filters['lang'], fn ($query, $lang) => $query->where('lang', '=', $lang))
            ->when($this->filters['search'], fn ($query, $search) =>
            $query->where('name', 'like', '%' . $search . '%'))
            ->when($this->filters['type-id'], fn ($query, $id) =>
            $query->whereHas('holiday', fn ($query) => $query->where('holiday_type_id', $id)))
            ->when($this->filters['user-id'], fn ($query, $id) =>
            $query->whereHas('holiday', fn ($query) => $query->where('user_id', $id)))
            ->when($this->filters['has-position'], fn ($query) =>
            $query->whereHas('holiday', fn ($query) => $query->whereNotNull('longitude')))
            ->when($this->filters['no-position'], fn ($query) =>
            $query->whereHas('holiday', fn ($query) => $query->whereNull('longitude')))
            ->when($this->filters['is-active'], fn ($query) =>
            $query->whereHas('holiday', fn ($query) => $query->where('active', '=', 1)))
            ->when($this->filters['not-active'], fn ($query) =>
            $query->whereHas('holiday', fn ($query) => $query->where('active', '=', 0)));

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
        return view('livewire.admin.holiday.holidays', [
            'holidays' => $this->rows,
        ])->layout('layouts.admin');
    }
}
