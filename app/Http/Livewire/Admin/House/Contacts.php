<?php

namespace App\Http\Livewire\Admin\House;

use App\Models\HouseContact;
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
        'house_id' => null,
        'firstname' => null,
        'lastname' => null,
        'phone' => null,
        'date-created-min' => null,
        'date-created-max' => null,
    ];
    public HouseContact $editing;

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
            }, 'house-contacts.csv');
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
        return HouseContact::make([]);
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
        $query = HouseContact::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', $id))
            ->when($this->filters['user_id'], fn ($query, $user_id) => $query->where('user_id', $user_id))
            ->when($this->filters['date-created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['date-created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($this->filters['email'], fn ($query, $name) => $query->where('email',  'like', '%' . $name . '%'))
            ->when($this->filters['phone'], fn ($query, $phone) => $query->where('phone',  'like', '%' . $phone . '%'))
            ->when($this->filters['house_id'], fn ($query, $house_id) => $query->where('house_id',  '=', $house_id ))
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
        return view('livewire.admin.house.contacts', [
            'contacts' => $this->rows,
        ])->layout('layouts.admin');
    }
}
