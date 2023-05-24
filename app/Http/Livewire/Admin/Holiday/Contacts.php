<?php

namespace App\Http\Livewire\Admin\Holiday;

use App\Models\HolidayContact;
use Livewire\Component;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;
use Carbon\Carbon;

class Contacts extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $filters = [
        'id' => null,
        'search' => null,
        'lang' => null,
        'email' => null,
        'user_id' => null,
        'holiday_id' => null,
        'firstname' => null,
        'lastname' => null,
        'phone' => null,
        'date-created-min' => null,
        'date-created-max' => null,
    ];
    public HolidayContact $editing;

    protected $queryString = ['sorts'];

    protected $listeners = ['refreshContacts' => '$refresh'];

    public function mount()
    {
        $this->editing = $this->makeBlankContact();
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
            }, 'holiday-contacts.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' contacts','type'=>'success']);
    }

    public function makeBlankContact()
    {
        return HolidayContact::make([]);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankContact();

        $this->showEditModal = true;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getRowsQueryProperty()
    {
        $query = HolidayContact::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', $id))
            ->when($this->filters['user_id'], fn ($query, $user_id) => $query->where('user_id', $user_id))
            ->when($this->filters['date-created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['date-created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($this->filters['email'], fn ($query, $name) => $query->where('email',  'like', '%' . $name . '%'))
            ->when($this->filters['phone'], fn ($query, $phone) => $query->where('phone',  'like', '%' . $phone . '%'))
            ->when($this->filters['holiday_id'], fn ($query, $holiday_id) => $query->where('holiday_id',  '=', $holiday_id ))
            ->when($this->filters['firstname'], fn ($query, $name) => $query->where('firstname',  'like', '%' . $name . '%'))
            ->when($this->filters['lastname'], fn ($query, $name) => $query->where('lastname',  'like', '%' . $name . '%'))
            ->when($this->filters['lang'], fn ($query, $lang) => $query->where('lang',  '=', $lang))
            ->when($this->filters['search'], fn ($query, $search) =>
            $query->where('id', '=', $search)
                ->orWhere('firstname', 'like', '%' . $search . '%')
                ->orWhere('lastname', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%'));

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
        return view('livewire.admin.holiday.contacts', [
            'contacts' => $this->rows,
        ])->layout('layouts.admin');
    }
}
